<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_article extends ms_model {

    var $table = 'dbpre_articles';
    var $field_table = 'dbpre_article_data';
    var $key = 'articleid';
    var $model_flag = 'article';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'article';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_article() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('city_id,catid,sid,subject,att,uid,author,copyfrom,keywords,introduce,status,closed_comment,content,picture,dateline');
        $this->add_field_fun('city_id,catid,att,uid', 'intval');
        $this->add_field_fun('subject,author,copyfrom,introduce,picture', '_T');
        $this->add_field_fun('content', '_HTML');
    }

    function read($articleid,$select='*') {
        $join_field = $select=='*' || strposex($select,'content');
        if($join_field)  {
            $this->db->join($this->table,'a.articleid',$this->field_table,'ad.articleid','LEFT JOIN');
        } else  {
            $this->db->from($this->table, 'a');
        }
        $this->db->select($select);
        $this->db->where('a.articleid',$articleid);
        $result = $this->db->get_one();
        if($result && !$result['articleid']) $result['articleid'] = $articleid;
        return $result;
    }

    function search($select,$orderby,$start,$offset) {
        $sid = _get('sid',null,MF_INT_KEY);
        if($sid > 0) {
            $this->db->join('dbpre_subjectlink', 'sl.flagid', $this->table, 'a.articleid','LEFT JOIN');
        } else {
            $this->db->from($this->table,'a');
        }
        $this->db->where_in('city_id', array(0,(int)$this->global['city']['aid']));
        $_GET['catid'] = (int) $_GET['catid'];
        if(is_numeric($_GET['catid']) && $_GET['catid'] > 0) {
            $this->loader->helper('misc','article');
            $this->db->where_in('catid',misc_article::category_catids($_GET['catid']));
        }
        if($sid > 0) {
            $this->db->where('sl.sid', $sid);
            $this->db->where('sl.flag', 'article');
        }
        isset($_GET['status']) && $this->db->where('status', (int)$_GET['status']);
        $_GET['keyword'] = _T($_GET['keyword']);
        if($_GET['keyword']) $this->db->where_like('subject', '%'.$_GET['keyword'].'%');

        $result = array(0,'');
        if(!$result[0] = $this->db->count()) {
            return $result;
        }
        $this->db->sql_roll_back('from,where');
		$this->db->select($select?$select:'a.*');
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

    function save($post,$articleid=null,$role='member') {
        $edit = $articleid != null;
        if($this->modcfg['post_filter']) {
            $W =& $this->loader->model('word');
        }
        $sids = '';
        if($edit) {
            if(!$detail = $this->read($articleid)) redirect('article_empty');
            if(!$this->in_admin) {
                unset($post['status']);
                if(!$this->modcfg['select_city']) unset($post['city_id']);
            }
            //判断权限
            $access = $this->check_post_access('edit',$role,$post['sid'],$this->global['user']->uid);
        } else {
            
            if(!$this->in_admin) {
                $post['uid'] = $this->global['user']->uid;
                $post['author'] = $this->global['user']->username;
                $post['status'] = $this->modcfg['post_check'] ? 0 : 1;
                if(!$this->modcfg['select_city']) $post['city_id'] = (int) $this->global['city']['aid'];
            } else {
                $post['uid'] = 0;
            }
            if($this->modcfg['post_filter']) {
                $W->check($post['content']) && $post['status'] = 0;
            }
            //判断权限
            $access = $this->check_post_access('add',$role,$post['sid'],$post['uid']);
        }
        $post['dateline'] = strtotime($post['dateline']);
        if(!$post['dateline']) {
            if(!$edit)
                $post['dateline'] = $this->global['timestamp'];
            else
                unset($post['dateline']);
        }
        if(!$access) redirect('global_op_access');
        if($this->modcfg['post_filter']) {
            $post['content'] = $W->filter($post['content']);
        }
        //自动截取简介
        if(!$post['introduce'] && $post['content']) {
            $post['introduce'] = substr_ex(str_replace("\r\n",". ",_NL(strip_tags($post['content']))), 0, 255);
        }
        //上传图片部分
        if($post['picture'] && strposex($post['picture'], '/temp/')) {
            if($pic = $this->upload_thumb($post['picture'])) {
                $post['havepic'] = 1;
                $post['picture'] = $pic['picture'];
                $post['thumb'] = $pic['thumb'];
            }
        } elseif ($post['picture']=='N') {
            $post['picture'] = '';
            $post['thumb'] = '';
            $del_thumb = TRUE;
        } else {
            unset($post['picture']);
        }
        //主题关联
        isset($post['sid']) && $sids = $post['sid'];
        //转换
        $post = $this->convert_post($post);
        //合并
        $detail && $post = array_merge($detail, $post);
        $this->check_post($post,$edit,$role);
        //过滤
        if($detail) {
            foreach($detail as $key => $val) {
                if($key=='content' && $this->check_down_images()) continue;
                if(isset($post[$key]) && $val == $post[$key]) {
                    unset($post[$key]);
                }
            }
        }
        if($post) {
            if(isset($post['content'])) {
                $content = $post['content'];
                unset($post['content']);
            }
            $post && $articleid = parent::save($post, $articleid, 0, 0, 0);
        } else {
            define('RETURN_EVENT_ID', $detail['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
            return $articleid;
        }
        if($edit && ($post['thumb']||$del_thumb)) {
            @unlink(MUDDER_ROOT . $detail['thumb']);
            @unlink(MUDDER_ROOT . $detail['picture']);
        }
        $content && $this->save_field($content,$articleid,$edit);
        //更新分类统计
        $status = FALSE;
        if(!$edit && $post['status'] == 1) {
            $this->category_num_add($post['catid'], 1); //新入不需要审核+1
            $this->user_point_add($post['uid']); //会员积分
            if($post['sid']) {
                $sids = $post['sid'];
                $exec_sid = true;
            }
            $status = TRUE;
        } elseif($edit) {
            if($detail['status'] != 1 && $post['status'] == 1) {
                $exec_sid = isset($sids); //当存在关联主题的id集合是，则加入到关联主题表
            } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                $sids = ''; //删除在关联表里的关联数据
                $exec_sid = true;
            } elseif($post['status'] == 1 || (!isset($post['status'])&&$detail['status']=='1')) {
                if(isset($post['sid'])) {
                    $exec_sid = true;
                    $sids = $post['sid'];
                }
            }
            //更新分类数量
            if(isset($post['catid']) && $detail['catid'] != $post['catid']) { //是否更换了分类
                if($detail['status'] == 1) $this->category_num_dec($detail['catid']); //原分类通过审核的数量-1
                if((isset($post['status']) && $post['status']==1)||(!isset($post['status']) && $detail['status']==1)) {
                    $this->category_num_add($detail['catid']); //新分类数量+1
                }
            } else {
                if($detail['status'] != 1 && $post['status'] == 1) {
                    $this->category_num_add($detail['catid']); //通过审核+1
                    $this->user_point_add($detail['uid']);
                    $detail['uid'] > 0 && $this->_feed($articleid); //feed
                } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                    $this->category_num_dec($detail['catid']); //更改审核状态-1
                }
            }
            $status = $detail['status'] == 1; //返回表示旨在前台使用，不必考虑后台
        }
        define('RETURN_EVENT_ID', $status ? 'global_op_succeed' : 'global_op_succeed_check');
        //更新关联主题表
        if($exec_sid) {
            $SLike =& $this->loader->model('item:subjectlink');
            $dateline = $edit ? $detail['dateline'] : $this->global['timestamp'];
            if(!$dateline) $dateline = $this->global['timestamp'];
            $SLike->save($sids, $articleid, 'article', $dateline);
        }
        if(!$edit && $post['status'] == 1) {
            !$this->in_admin && $this->_feed( $articleid ); //feed
        }
        return $articleid;
    }

    function save_field($content, $articleid, $edit) {
        $this->db->from($this->field_table);
        $this->check_down_images() && $content = $this->down_images($content);
        $this->db->set('content', $content);
        if($edit) {
            $this->db->where('articleid',$articleid);
            $this->db->update();
        } else {
            $this->db->set('articleid',$articleid);
            $this->db->insert();
        }
    }

    function check_post(&$post, $edit=false, $role = 'member') {
        if(!$post['subject']) redirect('article_post_subject_empty');
        if(strlen($post['subject'])<2 || strlen($post['subject'])>60) redirect(lang('article_post_subject_len',array(2,60)));
        if(!$post['catid']) redirect('article_post_catid_empty');
        if(!$post['author']) redirect('article_post_author_empty');
        if(!$post['introduce']) redirect('article_post_introduce_empty');
        if(strlen($post['introduce'])<10 || strlen($post['introduce'])>255) redirect(lang('article_post_introduce_len',array(10,255)));
        if(!$post['content']) redirect('article_post_content_empty');
        $content_min = $this->modcfg['content_min']>0 ? $this->modcfg['content_min'] : 10;
        $content_max = $this->modcfg['content_max']>0 ? $this->modcfg['content_max'] : 20000;
        if($content_min>$content_max) list($content_min, $content_max) = array($content_max ,$content_min);
        if(strlen($post['content'])<$content_min || strlen($post['content'])>$content_max) redirect(lang('article_post_content_len',array($content_min,$content_max)));
        if($post['city_id'] > 0) {
            $area = $this->loader->variable('area');
            if(!isset($area[$post['city_id']])) redirect('article_post_city_id_invalid');
        }
        if(!$this->in_admin) {
            if($role=='owner' && !$post['sid']) redirect('article_post_sid_empty');
            if($role!='owner' && $post['sid']>0 && !$this->modcfg['member_bysubject']) redirect('article_post_sid_member_disable');
        }
    }

    function check_down_images() {
        return (!$this->in_admin && $this->modcfg['dwon_image_bf'])||($this->in_admin && $this->modcfg['dwon_image_cp']);
    }

    function checkup($ids,$uppoint=true) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where_in('articleid',$ids);
        $this->db->where('status',0);
        $this->db->select('articleid,uid,status,catid,thumb,picture,sid,dateline');
        if(!$q=$this->db->get()) return;
        $artids = $catids = $uids = $up_sids = array();
        while($v=$q->fetch_array()) {
            $artids[] = $v['articleid'];
            if($v['sid']) $up_sids[$v['articleid']] = $v['sid'];
            if(isset($catids[$v['catid']])) {
                $catids[$v['catid']]++;
            } else {
                $catids[$v['catid']] = 1;
            }
            if(!$uppoint||!$v['uid']) continue;
            if(isset($uids[$v['uid']])) {
                $uids[$v['uid']]++;
            } else {
                $uids[$v['uid']] = 1;
            }
            //$v['uid'] > 0 && $this->_feed($v['articleid']); //feed
        }
        $q->fetch_array();
        if($up_sids) {
            $SLike =& $this->loader->model('item:subjectlink');
            foreach($up_sids as $articleid => $sids) {
                $SLike->save(trim($sids), $articleid, 'article', $this->timestamp);
            }
        }
        if($catids) {
            foreach($catids as $catid => $num) {
                $this->category_num_add($catid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_add($uid, $num);
            }
        }
        if($artids) {
            $this->db->from($this->table);
            $this->db->set('status',1);
            $this->db->where_in('articleid',$artids);
            $this->db->update();
            foreach ($artids as $id) {
                $this->_feed($id);
            }
        }
    }

    //删除文章
    function delete($ids,$up_point=false) {
        $ids = parent::get_keyids($ids);
        $this->_delete(array('articleid'=>$ids), TRUE, $up_point);
    }

    //删除某些分类的文章
    function delete_catids($catids) {
        if(!$catids) return;
        $this->_delete(array('catid'=>$catids), FALSE, FALSE);
    }

    //删除某些主题的文章
    function delete_sids($sids) {
        if(empty($sids)) return;
        $where = array('sid'=>$sids);
        $this->_delete($where);
    }

    //批量下载文章图片
    function batch_down_images($catid,$day,$page) {
        if(!$day && $day < 1) redirect('article_down_image_day_invalid');
        $mytime = $this->global['timestamp'] - ($day * 24 * 3600);
        if(!$catid || $catid < 1) redirect('article_down_image_catid_invalid');
        $C =& $this->loader->model('article:category');
        if(!$catids = $C->get_sub_cats($catid)) redirect('article_down_image_catid_empty');
        $total = _GET('total',null,MF_INT);

        $this->db->join($this->table,'a.articleid',$this->field_table,'ad.articleid');
        $this->db->where('a.catid', array_keys($catids));
        $this->db->where_more('dateline',$mytime);
        $this->db->where_more('status',1);
        if(!$total) {
            if(!$total = $this->db->count()) return 0;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select('a.subject,ad.*');
        $this->db->order_by('a.articleid');
        $start = get_start($page,1);
        $this->db->limit($start, 1);
        if(!$detail = $this->db->get_one()) return 0;
        if(!$result = $this->down_images($detail['content'],true)) return $total;
        list($content,$img_total,$img_succeed,$img_lost) = $result;
        if($content != $detail['content']) {
            $this->db->from($this->field_table);
            $this->db->set('content',$content);
            $this->db->where('articleid',$detail['articleid']);
            $this->db->update();
        }
        return array($total,$detail['subject'],$img_total,$img_succeed,$img_lost);
    }

    function _delete($where, $up_total = TRUE, $up_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select('articleid,sid,uid,status,catid,thumb,picture');
        if(!$q=$this->db->get()) return;
        if(!$this->in_admin) {
            $S =& $this->loader->model('item:subject');
            $mysubjects = $S->mysubject($this->global['user']->uid);
        }
        $artids = $catids = $uids = array();
        while($v=$q->fetch_array()) {
            //权限判断
            $access = $this->in_admin || $this->check_delete_access($v['uid'],$v['sid'],$mysubjects);
            if(!$access) redirect('global_op_access');
            $artids[] = $v['articleid'];
            @unlink(MUDDER_ROOT.$v['thumb']);
            @unlink(MUDDER_ROOT.$v['picture']);
            if($v['status']=='1') {
                if($up_total) {
                    if(isset($catids[$v['catid']])) {
                        $catids[$v['catid']]++;
                    } else {
                        $catids[$v['catid']] = 1;
                    }
                }
                if(!$up_point||!$v['uid']) continue;
                if(isset($uids[$v['uid']])) {
                    $uids[$v['uid']]++;
                } else {
                    $uids[$v['uid']] = 1;
                }
            }
        }
        if($up_total && $catids) {
            foreach($catids as $catid => $num) {
                $this->category_num_dec($catid, $num);
            }
        }
        if($uids) {
            foreach($uids as $uid => $num) {
                $this->user_point_dec($uid, $num);
            }
        }
        if($artids) {
            parent::delete($artids);
            $this->_delete_fields($artids);
            $this->_delete_comments($artids);
            $this->_delete_subjectlinks($artids);
        }
    }

    // 排序
    function listorder($post) {
        if(!$post && !is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $listorder = (int) $val['listorder'];
            $this->db->from($this->table);
            $this->db->set('listorder',$listorder);
            $this->db->where('articleid',$id);
            $this->db->update();
        }
    }

    // 更新att
    function upatt($ids,$att) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->set('att',$att);
        $this->db->where_in('articleid',$ids);
        $this->db->update();
    }

    // 更新城市
    function upcity($ids,$city_id) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->set('city_id', $city_id);
        $this->db->where_in('articleid',$ids);
        $this->db->update();
    }

    // 更新浏览量
    function pageview($articleid, $num=1) {
        $num = intval($num);
        if(empty($num)) return;
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('articleid', $articleid);
        $this->db->update();
    }

    //上传图片
    function upload_thumb($pic) {
        $sorcuefile = MUDDER_ROOT . $pic;
        if(!is_file($sorcuefile)) {
            redirect('article_post_thumb_not_found');
        }
        if(function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
            @unlink($sorcuefile);
            redirect('global_upload_image_unkown');
        }

        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 125;

        $this->loader->lib('image', null, false);
        $IMG = new ms_image();
        $IMG->watermark_postion = $this->global['cfg']['watermark_postion'];
        $IMG->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $IMG->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $wtext = $this->global['cfg']['watermark_text'] ? $this->global['cfg']['watermark_text'] : $this->global['cfg']['sitename'];
        if($this->global['user']->username) {
            $IMG->set_watermark_text(lang('item_picture_wtext',array($wtext, $this->global['user']->username)));
        } else {
            $IMG->set_watermark_text($this->global['cfg']['sitename']);
        }

        $name = basename($sorcuefile);
        $path = 'uploads' . DS . 'article';
        $subdir = date('Y-m', $this->timestamp);
        $dirs = explode(DS, $subdir);
        foreach ($dirs as $val) {
            $path .= DS . $val;
            if(!@is_dir(MUDDER_ROOT . $path)) {
                if(!@mkdir(MUDDER_ROOT . $path, 0777)) {
                    show_error(sprintf('global_mkdir_no_access'), str_replace($path));
                }
            }
        }
        $result = array();
        $filename = $path . DS . $name;
        $result['picture'] = str_replace(DS, '/', $filename);
        if(!copy($sorcuefile, MUDDER_ROOT . $filename)) {
            redirect('article_post_thumb_can_not_copy');
        }
        if($this->global['cfg']['watermark']) {
            $wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
            $IMG->watermark(MUDDER_ROOT.$filename, MUDDER_ROOT.$filename, $wfile);
        }
        $dest_img_file = $path . DS . 'thumb_' . $name;
        $result['thumb'] = str_replace(DS, '/', $dest_img_file);
        $IMG->thumb($sorcuefile, MUDDER_ROOT . $dest_img_file, $thumb_w, $thumb_h);
        //@unlink($sorcuefile);
        return $result;
    }

    //统计分类数量
    function total_cat_mun($catid) {
        $this->loader->helper('misc',$this->model_flag);
        $this->db->from($this->table);
        $this->db->where('catid',misc_article::category_catids($catid));
        return $this->db->count();
    }

    // 增加分类统计数量
    function category_num_add($catid, $num=1) {
        $this->db->from('dbpre_article_category');
        $this->db->set_add('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // 较少分类统计数量
    function category_num_dec($catid, $num=1) {
        $this->db->from('dbpre_article_category');
        $this->db->set_dec('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // 增加积分
    function user_point_add($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_article', FALSE, $num);
        /*
        if(!$BOOL) return;
        $this->db->set_add('articles', $num);
        $this->db->update();
        */
    }

    // 减少积分
    function user_point_dec($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_article', TRUE, $num);
        /*
        if(!$BOOL) return;
        $this->db->set_dec('articles', $num);
        $this->db->update();
        */
    }

    //会员组权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='article_post') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('article_access_disable');
            }
        } elseif($key=='article_delete') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                redirect('article_access_delete');
            }
        }
        return TRUE;
    }

    //判断2种角色的提交权限
    function check_post_access($op='add',$role='member',$sid,$uid) {
        if($this->in_admin) return TRUE;
        if($op=='add'||$op=='edit') {
            if(!$this->global['user']->check_access('article_post', $this, 0)) {
                !$sid && redirect('article_post_sid_empty');
                $S=&$this->loader->model('item:subject');
                $r = true;
                foreach(explode(',',$sid) as $s) {
                    if(!$S->is_mysubject($s, $uid)) $r = false;
                }
                return $r;
            } else {
                return true;
            }
        } else {
            if($role=='owner') {
                !$sid && redirect('article_post_sid_empty');
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
        if($sid) foreach(explode(',',$sid) as $id) {
            if(in_array($id, $mysubjects)) return true;
        }
        if($uid>0 && $uid == $this->global['user']->uid && $this->global['user']->check_access('article_delete',$this,0)) return true;
        return false;
    }

    //统计
    function status_total($uid=null) {
        $this->db->from($this->table);
        $this->db->select('status');
        $this->db->select('*', 'count', 'COUNT( ? )');
        $uid && $this->db->where('uid',$uid);
        $this->db->group_by('status');
        if(!$q = $this->db->get())return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['status']] = $v['count'];
        }
        $q->free_result();
        return $result;
    }

    //顶一下
    function digg($id) {
        $f = _cookie('article_digg_'.$id) == '1';
        if($f) return false;
        $this->db->from($this->table);
        $this->db->set_add('digg', 1);
        $this->db->where('articleid',$id);
        $this->db->update();
        set_cookie('article_digg_'.$id, '1', 24*3600);
        return true;
    }

    //生成rss聚合
    function rss($catid) {
        $category = $this->variable('category');

        $this->loader->lib('rss',null,0);
        $RSS = new ms_rss();
        $RSS->title = $this->global['cfg']['sitename'] . '-' . $category[$catid]['name'];
        $RSS->link = url('article/list/catid/'.$catid,'',1);
        $RSS->description = lang('article_rss_des', array($this->global['cfg']['sitename'], $category[$catid]['name']));
        $RSS->copyright = lang('global_rss_copyright');

        $this->db->from($this->table);
        $this->db->select('articleid,subject,author,introduce,dateline');
        if($catid > 0) {
            $this->loader->helper('misc',$this->model_flag);
            $this->db->where_in('catid', misc_article::category_catids($catid));
        }
        $this->db->where('status',1);
        if($q = $this->db->get()) {
            while($v=$q->fetch_array()) {
                $RSS->add_item($v['subject'], url('article/detail/id/'.$v['articleid'],'',1), $v['author'], $v['introduce'], $v['dateline']);
            }
            $q->free_result();
        }

        $result = $RSS->create_xml();
        unset($RSS);
        return $result;
    }

    function down_images($content, $return_total = false) {
        preg_match_all("/(src|SRC)=\"(http:\/\/(.+)(\.gif|\.jpg|\.jpeg|\.png))/isU", $content, $img_array);
        $img_array = array_unique($img_array[2]);
        @set_time_limit(900);

        $siteurl = _G('cfg','siteurl');
        $siteurl = str_replace(array('http://','https://'), '', _G('cfg','siteurl'));
        $url = get_fl_domain($siteurl);

        $total = $succed = $lost = 0;

        foreach ($img_array as $key => $value) {
            if(strpos($value, $url)) {
                continue;
            }
            $total++;
            $filepath = "uploads/article/" . date("Y-m", $this->global['timestamp']) . "/"; //图片保存的路径目录
            $filename = date("YmdHis", $this->global['timestamp']) . '_' . $key . "." . substr($value, -3 , 3); 
            if($this->_down_images($value, $filename, $filepath)) {
                $succed++;
                $fileArray[] = $filepath . $filename;
                $image_url = URLROOT . '/' . $filepath . $filename;
                $content = preg_replace("/".addcslashes($value,"/")."/isU", $image_url, $content);
            } else {
                $lost++;
                //$lostfile[] = $value;
            }
        }
        //$total,$succed,$lost
        if(!$return_total) return $content;
        return array($content,$total,$succed,$lost);
    }



    //下载图片
    function _down_images($url, $filename, $filepath) {
        
        if(!@is_dir(MUDDER_ROOT . $filepath)) {
            if(!@mkdir(MUDDER_ROOT . $filepath, 0777)) {
                return false;
            }
        }
        $fullname = MUDDER_ROOT . $filepath . $filename;

        $t = parse_url($url);
        $host = $t['host'];
        $file = $t['path'];
        $errno = 0;
        $errstr = '';
        $time_out = 10;

        $fp = @fsockopen($host, 80, $errno, $errstr, $time_out);
        if(!$fp) {
            echo $errstr;
            return false;
        } else {
            $header = "GET $file HTTP/1.1\r\n";
            $header .= "Host: $host\r\n";
            $header .= "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; zh-CN; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1\r\n";
            $header .= "Referer: http://$host\r\n";
            $header .= "Connection: Close\r\n\r\n";
            @fwrite($fp, $header);
            $jpg = @fopen($fullname, "wb");
            //跳过HTTP头信息
            while(!feof($fp)) {
                if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) break;
            }
            //保存图片数据
            while (!feof($fp)) {
                $s = @fgets($fp,128);
                @fwrite($jpg,$s);
            }
            @fclose($jpg);
            @fclose($fp);
            return true;
        }
    }

    // 删除附表
    function _delete_fields($ids) {
        $this->db->from($this->field_table);
        $this->db->where_in('articleid',$ids);
        $this->db->delete();
    }

    // 删除文章评论表
    function _delete_comments($ids) {
        //删除评论
        if(check_module('comment')) {
            $CM =& $this->loader->model(':comment');
            $CM->delete_id('article', $ids);
        }
    }

    // 删除主题关联表数据
    function _delete_subjectlinks($ids) {
        if(check_module('item')) {
            $SL =& $this->loader->model('item:subjectlink');
            $SL->clear($ids, 'article');
        }
    }

    // feed
    function _feed($articleid) {
        if(!$articleid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($articleid)) return;
        if($detail['uid'] > 0) {
            $param = array();
            $param['flag'] = $this->model_flag;
            //$param['sid'] = $detail['sid'];
            $param['uid'] = $detail['uid'];
            $param['username'] = $detail['author'];
            $param['icon'] = lang('article_feed_icon');

            $feed = array();
            $feed['title_template'] = lang('article_feed_title_template');
            $feed['title_data'] = array (
                'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['author'] . '</a>',
            );
            $feed['body_template'] = lang('article_feed_body_template');
            $feed['body_data'] = array (
                'subject' => '<a href="'.url("article/detail/id/$detail[articleid]").'">' . $detail['subject'] . '</a>',
            );
            $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['introduce'])), 150);

            $FEED->save_ex($param, $feed);            
        }
        //主题的feed信息
        if($detail['sid']) {
            $this->_feed_subject($FEED, $detail);
        }

        //$FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['author'], $feed);
        //$FEED->add($feed['icon'], $detail['uid'], $detail['author'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], $feed['body_general']);
    }

    function _feed_subject(&$FEED, &$detail) {
        $this->db->join('dbpre_subjectlink','sl.sid','dbpre_subject','s.sid');
        $this->db->where('flag','article');
        $this->db->where('flagid',$detail['articleid']);
        $this->db->select('s.sid,s.name,s.subname,s.pid,s.catid');
        $q = $this->db->get();
        if(!$q) return;
        
        $this->global['fullalways'] = TRUE;

        $param = array();
        $param['flag'] = 'item';
        $param['uid'] = 0;
        $param['username'] = '';
        $param['icon'] = lang('article_feed_icon');
        while ($v = $q->fetch_array()) {
            $param['sid'] = $v['sid'];
            $feed = array();
            $feed['title_template'] = lang('article_feed_subject_title_template');
            $feed['title_data'] = array (
                'item_name' => '<a href="'.url("item/list/catid/$v[pid]").'">' . display('item:model', "catid/$v[pid]/keyname/item_name") . '</a>',
                'subject' => '<a href="'.url("item/detail/id/$v[sid]").'">' . $v['name'] . '</a>',
            );
            $feed['body_template'] = lang('article_feed_subject_body_template');
            $feed['body_data'] = array (
                'subject' => '<a href="'.url("article/detail/id/$detail[articleid]").'">' . $detail['subject'] . '</a>',
            );
            $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['introduce'])), 150);
            $FEED->save_ex($param, $feed);
        }
    }

}
?>