<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_coupon extends ms_model {

    var $table = 'dbpre_coupons';
	var $key = 'couponid';
    var $model_flag = 'coupon';
    var $subject_table = 'dbpre_subject';
    var $table_print = 'dbpre_coupon_print';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'coupon';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_coupon() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('city_id,catid,sid,starttime,endtime,subject,des,content,status');
		$this->add_field_fun('catid,sid,starttime,endtime,status', 'intval');
        $this->add_field_fun('subject,des', '_T');
        $this->add_field_fun('content', '_HTML');
	}

    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE, $join_select='') {
	    if($join_select) {
            $this->db->join($this->table, 'c.sid', $this->subject_table, 's.sid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'c');
        }
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        $join_select && $this->db->select($join_select);
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //按 负数页数 换算读取位置
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    function save($post, $couponid = NULL,$role='member') {
        $edit = $couponid != null;
        if($edit) {
            if(!$detail = $this->read($couponid)) redirect('coupon_empty');
            //判断权限
            $access = $this->check_post_access('edit',$role,$detail['sid'],$detail['uid']);
        } else {
            $post['dateline'] = $this->global['timestamp'];
            if(!$this->in_admin) {
                $post['status'] = $this->modcfg['check'] ? 0 : 1;
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
            }
            //判断权限
            $access = $this->check_post_access('add',$role,$post['sid'],$post['uid']);
        }
        !$access and redirect('coupon_access_post_item_disabled');
        if($post['starttime']) $post['starttime'] = strtotime($post['starttime']);
        if($post['endtime']) $post['endtime'] = strtotime($post['endtime']);
        //检测管理主题
        if($post['sid']) {
            $I =& $this->loader->model('item:subject');
            if(!$subject = $I->read($post['sid'])) redirect('item_empty');
            $post['city_id'] = $subject['city_id']; //字段设置所属城市
        }
        //上传图片部分
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
            unset($img);
        }
        $couponid = parent::save($post,$detail?$detail:$couponid);
        if($edit) {
            if($post['status']=='1') {
                if($detail['status']!='1') {
                    $this->update_category_num($post['catid'], 1);
                    $this->update_subject_num($post['sid'], 1);
                } else {
                    if($post['catid'] != $detail['catid']) $this->update_category_num($detail['catid'],-1);
                    if($post['sid'] != $detail['sid']) $this->update_subject_num($detail['sid'],-1);
                }
                if($detail['uid'] && !$detail['status']) $this->user_point_add($detail['uid'], 1);
            } elseif($post['status'] == '2') {
                if($detail['status'] == '1') {
                    $this->update_category_num($detail['catid'],-1);
                    $this->update_subject_num($detail['sid'],-1);
                }
            }
        } else {
            if($post['status']=='1') {
                $this->update_category_num($post['catid'],1);
                $this->update_subject_num($post['sid'],1);
                if($post['uid']) {
                    $this->user_point_add($post['uid'],1);
                }
                $this->_feed($couponid,'add');
            }
        }
        return $couponid;
    }

    function checkup($couponids) {
        $ids = parent::get_keyids($couponids);
        $this->db->from($this->table);
        $this->db->where_in('couponid',$ids);
        $this->db->select('couponid,status,catid,uid,sid');
        if(!$q=$this->db->get()) return;
        $cids = $catids = $sids = array();
        while($v=$q->fetch_array()) {
            $cids[] = $v['couponid'];
            if(isset($catids[$v['catid']])) {
                $catids[$v['catid']]++;
            } else {
                $catids[$v['catid']] = 1;
            }
            if($v['sid']) {
                if(isset($sids[$v['sid']])) {
                    $sids[$v['sid']]++;
                } else {
                    $sids[$v['sid']] = 1;
                }
            }
            if(!$v['uid']) continue;
            if(isset($uids[$v['uid']])) {
                $uids[$v['uid']]++;
            } else {
                $uids[$v['uid']] = 1;
            }
            $this->_feed($v['couponid'],'add');
        }
        $q->free_result();
        if($catids) {
            foreach($catids as $catid => $num) {
                $this->update_category_num($catid, $num);
            }
        }
        if($sids) {
            foreach($sids as $sid => $num) {
                $this->update_subject_num($sid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_add($uid, $num);
            }
        }
        $this->db->from($this->table);
        $this->db->where_in('couponid',$cids);
        $this->db->set('status',1);
        $this->db->update();
    }

    function delete($couponids) {
        $ids = parent::get_keyids($couponids);
        $where = array('couponid'=>$ids);
        $this->_delete($where);
    }

    //删除某写主题的优惠券
    function delete_sids($sids) {
        if(empty($sids)) return;
        $where = array('sid'=>$sids);
        $this->_delete($where, TRUE, FALSE);
    }

    //删除某些分类的优惠券
    function delete_catids($catids) {
        $ids = $this->get_keyids($catids);
        $where = array('catid'=>$ids);
        $this->_delete($where, FALSE);
    }

    function check_post(& $post, $edit = false) {
        if(!$post['subject']) redirect('coupon_post_subject_empty');
        if(!$post['sid']) redirect('coupon_post_sid_empty');
        if(!$post['catid']) redirect('coupon_post_catid_empty');
        if(!$post['picture'] && !$edit) redirect('coupon_post_picture_empty');
        if(!$post['starttime']||!$post['endtime']) redirect('coupon_post_time_empty');
        if(!$post['des']) redirect('coupon_post_des_empty');
        if(!$post['content']) redirect('coupon_post_content_empty');
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 160;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 100;
        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->modcfg['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 's_', $thumb_w, $thumb_h);
        $img->use_mment = !empty($this->modcfg['autosize']); //关闭最大尺寸限制
        $img->upload('coupon');
    }

    //批处理优惠券是否过期
    function batch_valid() {
        $this->db->from($this->table);
        $this->db->select('couponid,sid,status');
        $this->db->where('endtime',$this->global['timestamp']);
        $this->db->where('status',1);
        if(!$r=$this->db->get()) return;
        $ids = $sids = $catids = array();
        while($v=$r->fetch_array()) {
            $ids[] = $v['couponid'];
            if(in_array($v['catid'],$catids)) {
                $catids[$v['catid']]++;
            } else {
                $catids[$v['catid']]=1;
            }
            if(!$v['sid']) continue;
            if(in_array($v['sid'],$sids)) {
                $sids[$v['sid']]++;
            } else {
                $sids[$v['sid']]=1;
            }
        }
        $r->free_result();

        if(!$ids) return;

        $this->db->from($this->table);
        $this->db->set('status',2); //2表示过期
        $this->db->where_in('couponid', $ids);

        //更新分类数量
        if($catids) foreach($catids as $catid => $num) {
            $this->update_category_num($catid, -$num);
        }

        //更新主题里的数量索引
        if($sids) foreach($sids as $sid=>$num) {
            $this->update_subject_num($sid,-$num);
        }
    }

    function check_valid($couponid, $catid, $sid, $endtime, $status) {
        $time = strtotime(date('Y-m-d', $this->global['timestamp']));
        if($endtime >= $time ) return true;
        if($status != '1') return false;
        $this->update_category_num($catid, -1);
        $this->update_subject_num($sid, -1);
        $this->db->from($this->table);
        $this->db->set('status',2);
        $this->db->where('couponid', $couponid);
        $this->db->update();
        return false;
    }

    //会员组权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='coupon_post') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('coupon_access_disable');
            }
        } elseif($key=='coupon_print') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('coupon_print_disable');
            }
        }
        return TRUE;
    }

    //判断2种角色的提交权限
    function check_post_access($op='add',$role='member',$sid,$uid) {
        if($this->in_admin) return TRUE;
        if($op == 'add') {
            if($role=='owner')  {
                !$sid && redirect('coupon_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } else {
                return $this->global['user']->check_access('coupon_post', $this, 0);
            }
        } else {
            if($role=='owner') {
                !$sid && redirect('coupon_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                return $S->is_mysubject($sid, $uid);
            } elseif($this->global['user']->uid == $uid) {
                return TRUE;
            }
        }
        return false;
    }

    //判断删除权限
    function check_delete_access($uid,$sid,&$mysubjects) {
        if($this->in_admin) return true;
        if($sid>0 && in_array($sid,$mysubjects)) return true;
        //if($uid>0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('coupon_delete',$this,0)) return true;
        return false;
    }

    //判读是否为主题管理员
    function check_owner($sid) {
        if(!$sid || $sid < 0) return false;
        $S =& $this->loader->model('item:subject');
        return $S->is_mysubject($sid,$this->global['user']->uid);
    }

    //更新主题内的统计
    function update_subject_num($sid,$num=1) {
        if(!$sid||!$num) return;
        $this->db->from($this->subject_table);
        if($num>0) $this->db->set_add('coupons',$num);
        if($num<0) $this->db->set_dec('coupons',abs($num));
        $this->db->where('sid',$sid);
        $this->db->update();
    }

    //更新分类统计数量
    function update_category_num($catid,$num=1) {
        if(!$catid||!$num) return;
        $this->db->from('dbpre_coupon_category');
        if($num>0) $this->db->set_add('num',$num);
        if($num<0) $this->db->set_dec('num',abs($num));
        $this->db->where('catid',$catid);
        $this->db->update();
    }

    function user_point_add($uid,$num) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_coupon', FALSE, $num);
    }

    function user_point_print($uid,$num) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'print_coupon', TRUE, $num);
    }

    //统计状态数量
    function status_total($uid=null,$sid=null) {
        $this->db->from($this->table);
        $this->db->select('status');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $uid && $this->db->where('uid',$uid);
        $sid && $this->db->where('sid',$sid);
        $this->db->group_by('status');
        if(!$q = $this->db->get())return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['status']] = $v['count'];
        }
        $q->free_result();
        return $result;
    }

    function pageview($couponid,$num=1) {
        if(!$couponid) return;
        $this->db->from($this->table);
        $this->db->set_add('pageview',$num);
        $this->db->where('couponid',$couponid);
        $this->db->update();
    }

    function update_effect($couponid,$effect='effect1') {
        if(!$couponid) return;
        if(!$effect) redirect(lang('member_effect_unkown_effect'));
        $idtype = str_replace('dbpre_', '', $this->table);

        $M =& $this->loader->model('member:membereffect');
        $M->add_idtype($idtype, 'coupon', 'couponid');

        $M->save($couponid, $idtype, $effect, FALSE);
        //自身表增加
        $this->db->from($this->table);
        $this->db->set_add('effect1',1);
        $this->db->where('couponid',$couponid);
        $this->db->update();
    }

    function print_coupon($couponid) {
        //打印判断
        $this->global['user']->checK_access('coupon_print', $this);
        //判断是否已经打印过
        if($id=$this->check_print($couponid,$this->global['user']->uid)) return;
        //没有打印过就扣积分
        if(!$id) {
            $P =& $this->loader->model('member:point');
            $P->allow_del = false; //不允许扣除成为负数，即会员积分不足时，进行提示
            $P->update_point($this->global['user']->uid,'print_coupon',TRUE,1,FALSE,TRUE,lang('coupon_print_coin_dec_des'));
            unset($P);
        }
        //增加打印次数
        $this->db->from($this->table);
        $this->db->set_add('prints', 1);
        $this->db->where('couponid', $couponid);
        $this->db->update();
        //添加打印数据
        $this->db->from($this->table_print);
		$set = array();
		$set['couponid'] = $couponid;
		$set['uid'] = $this->global['user']->uid;
		$set['username'] = $this->global['user']->isLogin ? $this->global['user']->username : $this->global['ip'];
		$set['dateline'] = $this->global['timestamp'];
		$this->db->set($set);
		$this->db->insert();
        //添加打印事件
        $this->_feed($couponid,'print');
    }

    function get_subject_total($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('status',1);
        return $this->db->count();
    }

    function check_print($couponid,$uid) {
        $this->db->from($this->table_print);
        $this->db->where('couponid',$couponid);
        $this->db->where('uid',$uid);
        if(!$result = $this->db->get_one()) return false;
        return $result['id'];
    }

    function _delete($where,$up_total = TRUE, $up_subject_total = TRUE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('couponid,sid,uid,status,catid,thumb,picture');
        if(!$q=$this->db->get()) return;
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }
        $cids = $catids = $sids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid'],$v['sid'],$mysubjects);
            if(!$access) redirect('global_op_access');
            $cids[] = $v['couponid'];
            @unlink(MUDDER_ROOT.$v['thumb']);
            @unlink(MUDDER_ROOT.$v['picture']);
            if($v['status']=='1')  {
                if($up_total) {
                    if(isset($catids[$v['catid']])) {
                        $catids[$v['catid']]++;
                    } else {
                        $catids[$v['catid']] = 1;
                    }
                }
                if($up_subject_total) {
                    if(!$v['sid']) continue;
                    if(isset($sids[$v['sid']])) {
                        $sids[$v['sid']]++;
                    } else {
                        $sids[$v['sid']] = 1;
                    }
                }
            }
        }
        if($up_total && $catids) {
            foreach($catids as $catid => $num) {
                $this->update_category_num($catid, -$num);
            }
        }
        if($$up_subject_total && $sids) {
            foreach($sids as $sid => $num) {
                $this->update_subject_num($sid, -$num);
            }
        }
        if($cids) parent::delete($cids);
    }

    function _feed($couponid,$type='add') {
        if(!$couponid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($couponid)) return;
        if($type=='add') {
            $uid = $detail['uid'];
            $username = $detail['username'];
        } else {
            $uid = $this->global['user']->uid;
            $username = $this->global['user']->username;
        }
        if($uid > 0) {
            $feed = array();
            $feed['icon'] = lang('coupon_feed_'.$type.'_icon');
            $feed['title_template'] = lang('coupon_feed_'.$type.'_title_template');

            $feed['title_data'] = array (
                'username' => '<a href="'.url("space/index/uid/$uid").'">'.$username.'</a>',
            );
            $feed['body_template'] = lang('coupon_feed_'.$type.'_body_template');
            $feed['body_data'] = array (
                'subject' => '<a href="'.url("coupon/detail/id/$detail[couponid]").'">'.$detail['subject'].'</a>',
            );
            $feed['body_general'] = strip_tags(preg_replace("/\[.+?\]/is", '', $detail['des']));
            $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $uid, $username, $feed);
        }
        if($type == 'add') $this->_feed_subject($FEED, $detail);

    }

    function _feed_subject(&$FEED, &$detail) {
        if(!$subject = $this->loader->model('item:subject')->read($detail['sid'])) return;
        $this->global['fullalways'] = TRUE;
        $param = array();
        $param['flag'] = 'item';
        $param['uid'] = 0;
        $param['username'] = '';
        $param['icon'] = lang('coupon_feed_add_icon');
        $param['sid'] = $detail['sid'];

        $feed = array();
        $feed['title_template'] = lang('coupon_feed_subject_title_template');
        $feed['title_data'] = array (
            'item_name' => '<a href="'.url("item/list/catid/$subject[pid]").'">' . display('item:model', "catid/$subject[pid]") . '</a>',
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_template'] = lang('coupon_feed_subject_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("coupon/detail/id/$detail[couponid]").'">'.$detail['subject'].'</a>',
        );
        $feed['body_general'] = strip_tags(preg_replace("/\[.+?\]/is", '', $detail['des']));
        $FEED->save_ex($param, $feed);
    }


}
?>