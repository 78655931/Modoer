<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_review extends ms_model {

    var $table = 'dbpre_review';
    var $key = 'rid';

    var $idtypes = null;
    var $modcfg = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'review';
        $this->init_field();
        $this->load_hook();
        $this->modcfg = $this->variable('config');
    }

    function msm_review() {
        $this->__construct();
    }

    function init_field() {
        parent::add_field('idtype,id,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,price,best,digest,title,content,taggroup,pictures');
        parent::add_field_fun('id,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,price,best,digest', 'intval');
        parent::add_field_fun('idtype,title', '_T');
        parent::add_field_fun('content', '_TA');
    }

    function load_hook() {
        $modules =& $this->global['modules'];
        foreach($modules as $k => $v) {
            $hookfile = MUDDER_MODULE . $v['flag'] . DS . 'inc' . DS . 'review_hook.php';
            if(!is_file($hookfile)) continue;
            if(!$tmp = read_cache($hookfile)) continue;
            foreach($tmp as $k2 => $v2) {
                $this->idtypes[$k2] = $v2;
            }
        }
    }

    function get_type($idtype) {
        //item_subject
        if(isset($this->idtypes[$idtype])) 
            return $this->idtypes[$idtype];
        return null;
    }

    function & read($rid, $select = '*', $join_member = FALSE) {
        if(!$rid) redirect(lang('global_sql_keyid_invalid', $this->key));
        if($join_member) {
            $this->db->join($this->table, 'r.uid', 'dbpre_members', 'm.uid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'r');
        }
        $this->db->where('rid', $rid);
        $this->db->select($select);
        $result = $this->db->get_one();

        return $result;
    }

    function find($select, $where, $orderby, $start, $offset, $total = TRUE, $join_subject = FALSE, $join_member = FALSE) {
        if($join_member) {
            $this->db->join($this->table, 'r.uid', 'dbpre_members', 'm.uid', 'LEFT JOIN');
        }
        if($join_subject) {
             if(!$join_member) $this->db->join($this->table, 'r.id', 'dbpre_subject', 's.sid', 'LEFT JOIN');
             if($join_member) $this->db->join_together('r.rid', 'dbpre_subject', 's.uid', 'LEFT JOIN');
        }
        if(!$join_subject && !$join_member) {
            $this->db->from($this->table, 'r');
        }
        $this->db->where($where);

        $result = array(0, '');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    //获取我的点评列表
    function myreviewed($uid, $start, $offset, $total = TRUE) {
        $this->db->from($this->table,'r');
        $where = array();
        $this->db->where('r.uid',$uid);
        $this->db->where('r.status',1);
        $result = array(0, '');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select('r.rid,r.idtype,r.id,r.pcatid,r.subject,r.city_id,r.title,r.subject');
        $this->db->group_by('r.id');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    //保存点评
    function save($post, $rid = null) {
        //点评对象类型
        $idtype = $post['idtype'];
        $id = $post['id'];
        if(!$typeinfo = $this->get_type($idtype)) {
            redirect('review_idtype_empty');
        }

        $OBJ = &$this->loader->model($typeinfo['model_name']);
        if(!$object = $OBJ->read($id)) redirect('review_object_empty');

        $is_edit = $rid > 0;
        if(!$this->in_admin) $post['ip'] = $this->global['ip'];

        $W =& $this->loader->model('word'); //过滤字串和检测敏感字类
        if($is_edit) {
            if(!$detail = $this->read($rid)) {
                redirect('review_empty');
            }
            if(!$this->in_admin && $this->global['user']->uid != $detail['uid']) {
                redirect('global_op_access');
            }
            if(!$this->in_admin) {
                if(isset($post['status'])) unset($post['status']);
                $post['isupdate'] = 1; //点评更新操作标记
            }
        } else {
            if($object['status'] != '1') redirect('review_status_invalid');
            //检测会员组权限
            if(!$this->in_admin) {
                $review_access = $OBJ->review_access($object);
                if($review_access['code'] != 1) {
                    $this->redirect_access($review_access);
                }
            }
            if($this->global['user']->isLogin) {
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
            } else {
                //游客点评
                $post['uid'] = 0;
                $post['username'] = '';
            }
        }

        $post['posttime'] = $this->global['timestamp'];
        //上传的图片的应用
        if(is_array($post['pictures']) && $post['pictures']) {
            $post['pictures'] = $this->get_pictures($post['pictures']);
        } else {
            $post['pictures'] = '';
        }
        $post['havepic'] = $post['pictures'] ? 1 : 0;
        $post['subject'] = $OBJ->get_subject($object);//点评对象名称
        $post['city_id'] = $OBJ->get_city_id($object);//所属城市
        $post['pcatid'] = $OBJ->get_obj_pid($object);//主分类id
        $config = $OBJ->get_review_config($object);//参数配置
        if(!$this->in_admin && !$this->global['user']->isLogin && !$config['guest_review']) {
            // 针对游客的判断
            $forward = $this->global['web']['reuri'] ? ($this->global['web']['url'] . $this->global['web']['reuri']) : url('modoer','',1);
            redirect('member_op_not_login', url('member/login/forward/'.base64_encode($forward)));
        }
        //标题
        //if(!$post['title']) $post['title'] = $post['subject'];
        if(!$is_edit) {
            $post['status'] = $config['reviewcheck'] ? 0 : ($W->check($post['content']) ? 0 : 1);
            //告之 控制页面 当前的状态
            define('RETURN_EVENT_ID', $post['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
        } elseif($is_edit && !$this->in_admin) {
            //去掉前台可能的状态设置
            if(isset($post['status'])) unset($post['status']);
        }
        $post['content'] = $W->filter($post['content']); //检测和过滤敏感词

        $this->check_post($config, $post, !empty($rid)); //提交检测

        //处理标签(仅支持主题模块)
        if($idtype == 'item_subject' && isset($post['taggroup'])) {
            $TAG =& $this->loader->model('item:tag');
            if($detail['taggroup']) {
                $detail['taggroup'] = unserialize($detail['taggroup']); //反序列化，替换成数组形式
            }
            //检测标签并返回符合的标签组
            if($taggroup = $TAG->check_post($config['taggroup'], $post['taggroup'], $is_edit)) {
                if(!$is_edit) { //创建点评的情况
                    if($post['status']) {
                        //状态正常，直接将数据写入TAG表，并返回标签数组键名是tagid的标签数组
                        $TAG->add_batch($post['city_id'], $post['id'], $taggroup);
                    }
                } elseif($is_edit) { //编辑点评的情况
                    if($detail['status'] && (!isset($post['status']) || $post['status'])) {
                        //删除已经取消的标签，写入新增加的标签
                        $TAG->replace_batch($post['city_id'], $detail['id'], $taggroup, $detail['taggroup']);
                    } elseif($detail['status'] && isset($post['status']) && !$post['status']) {
                        //回到未审核状态时，删除之前写入的标签
                        $TAG->delete_batch($post['city_id'], $detail['id'], $detail['taggroup']);
                    } elseif(!$detail['status'] && $post['status']) {
                        //写入新标签，如同新建点评一般
                        $TAG->add_batch($post['city_id'], $post['id'], $taggroup);
                    }
                }
                $post['taggroup'] = serialize($taggroup);//重新序列化标签数组
            } elseif($is_edit && $detail['taggroup']) {
                //如果是编辑状态下，同时没有合适的标签，则删除之前存在的标签
                $TAG->delete_batch($post['city_id'], $detail['id'], $detail['taggroup']);
                $post['taggroup'] = '';
            } else {
                $post['taggroup'] = '';
            }
        }

        if($is_edit) {
            //不提交没有改动过的字段
            foreach($detail as $key => $val) {
                if($val == $post[$key]) {
                    unset($post[$key]);
                }
            }
            if($post['taggroup'] == serialize($detail['taggroup'])) {
                unset($post['taggroup']);
            }
            if(count($post) == 1 && isset($post['posttime'])) {
                unset($post['posttime']);
            }
            $post && parent::save($post, $rid, FALSE, FALSE);
        } else {
            if(!isset($post['taggroup'])) $post['taggroup'] = '';
            $rid = parent::save($post, null, FALSE, FALSE);
        }

        //更新分类统计
        if(!$is_edit && $post['status']) {
            $this->update_review_point($idtype, $post['id'], $config);
            $this->add_user_point($post['uid']);
            $this->activity($post['uid'], $post['username']);
            $this->_feed($rid);
            //同步到第三方平台
            if($_POST['share']) {
                $this->_share($_POST['share'], $rid);
            }
        } elseif($is_edit) {
            if(!$detail['status'] && $post['status']) {
                $this->update_review_point($idtype, $detail['id'], $config);
                if(!$detail['isupdate']) {
                    $this->add_user_point($detail['uid']);
                    $this->activity($detail['uid'], $detail['username']);
                    $this->_feed($rid);
                }
            }
            if($detail['status'] && isset($post['status']) && $post['status']=='0') {
                $OBJ->review_total($detail['id'], -1); 
            }
            if($detail['status']) {
                $update_point = FALSE;
                //对比是否更改了分数，来判断是否需要更新主题的积分
                for($i = 1; $i <= 8; $i++) {
                    $sortflag = 'sort'. $i;
                    if(isset($post[$sortflag]) && $detail[$sortflag] != $post[$sortflag]) {
                        $update_point = true;
                    }
                }
                $update_point && $this->update_review_point($idtype, $detail['id'], $config);
            }
        }
        return $rid;
    }

    //审核点评
    function checkup($rids) {
        $rids = $this->get_keyids($rids);
        $this->db->from($this->table,'r');
        $this->db->select('r.rid,r.idtype,r.id,r.uid,r.username,r.status,r.taggroup,r.isupdate,r.city_id,r.pcatid');
        $this->db->where('r.status',0);
        $this->db->where_in('rid', $rids);
        if(!$query = $this->db->get()) return;
        $upids = $ids = $subjects = array();
        $TAG =& $this->loader->model('item:tag');
        while($val = $query->fetch_array()) {
            $upids[] = $val['rid'];
            //处理标签
            if($val['taggroup'] && $val['idtype']=='item_subject') {
                $taggroup = unserialize($val['taggroup']);
                $TAG->add_batch($val['city_id'], $val['id'], $taggroup);
                /*
                $this->db->from($this->table);
                $this->db->set('taggroup', $tag ? serialize($tag) : '');
                $this->db->where('rid', $val['rid']);
                $this->db->update();
                */
            }
            //需要更新的主题
            if(!is_array($subjects[$val['idtype']])) $subjects[$val['idtype']] = array();
            if(!in_array($val['id'], $subjects[$val['idtype']])) {
                $subjects[$val['idtype']][$val['id']] = array(
                    'pid' => $val['pcatid'],
                    'idtype' => $val['idtype'],
                    'id' => $val['id'],
                );
            }

            if(!$val['isupdate'] && $val['uid'] > 0) {
                $this->add_user_point($val['uid']);
                $this->activity($val['uid'], $val['username']);
                $this->_feed($val['rid']);
            }
        }
        $query->free_result();

        //更新点评状态
        $this->db->from($this->table);
        $this->db->set('status',1);
        $this->db->where_in('rid', $upids);
        $this->db->update();
        //更新主题分数和统计
        if($subjects) foreach($subjects as $idtype => $subject) {
            if(!$typeinfo = $this->get_type($idtype)) continue;
            $OBJ = &$this->loader->model($typeinfo['model_name']);
            foreach($subject as $val) {
                $config = $OBJ->get_review_config($val);
                $this->update_review_point($idtype,$val['id'],$config);
            }
        }
    }

    //删除点评
    function delete($rids, $update_total = TRUE, $delete_point = FALSE) {
        $rids = parent::get_keyids($rids);
        $where = array('rid'=>$rids);
        $this->_delete($where,$update_total,$delete_point);
    }

    //删除点评
    function delete_idtype($idtype, $ids, $update_total = TRUE, $delete_point = FALSE) {
        $ids = parent::get_keyids($ids);
        $where = array();
        $where['idtype'] = $idtype;
        $where['id'] = $ids;
        $this->_delete($where,$update_total,$delete_point);
    }

    //更新点评表里主题分类
    function update_category($idtype, $ids, $pid) {
        $ids = parent::get_keyids($ids);
        $this->db->from($this->table);
        $this->db->set('pcatid',$pid);
        $this->db->where('idtype',$idtype);
        $this->db->where_in('id',$ids);
        $this->db->update();
    }

    //检测当前会员是否已经点评过
    function reviewed($idtype, $id, $return_count = false) {
        $this->db->from($this->table);
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        if($this->global['user']->isLogin) {
            $this->db->where('uid', $this->global['user']->uid);
        } else {
            $this->db->where('uid', 0);
            $this->db->where('ip', $this->global['ip']);
        }
        if($return_count) {
            $this->db->select('rid', 'num', 'COUNT(?)');
            $this->db->select('posttime');
            $this->db->group_by('id');
            return $this->db->get_one();
        }
        $this->db->select('rid');
        $rid = $this->db->get_value();
        return !$rid ? FALSE : $rid;
    }

    //获取最后一次点评的内容
    function get_last($idtype, $id) {
        $this->db->from($this->table);
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        if($this->global['user']->isLogin) {
            $this->db->where('uid', $this->global['user']->uid);
        } else {
            $this->db->where('uid', 0);
            $this->db->where('ip', $this->global['ip']);
        }
        $this->db->order_by('posttime','DESC');
        return $this->db->get_one();
    }

    //点评前的必要检测，并返回主题信息
    function check_review_before($idtype, $id, $isedit = FALSE, $goto = '') {
        if(!$id) redirect(lang('global_sql_keyid_invalid', 'id'));
        if(!$typeinfo = $this->get_type($idtype)) continue;
        $OBJ =& $this->loader->model($typeindo['model_name']);
        if(!$detail = $OBJ) redirect('review_object_empty');
        if($detail['status'] != '1') redirect('review_object_status_invalid');
        //检测是否登录
        if(!$isedit && $rid = $this->reviewed($idtype, $id)) {
            if($goto) {
                $url = str_replace('_rid_', $rid, $goto);
                location($url);
                exit;
            }
            redirect('item_reviewed');
        }
        return $detail;
    }

    //点评提交检测
    function check_post($config, $post, $isedit) {
        //点评项判断
        $rogid = $config['review_opt_gid'];
        $opts = $this->variable('opt_' . $rogid);
        foreach($opts as $val) {
            $flag = $val['flag'];
            if(!isset($post[$flag]) || !is_numeric($post[$flag]) || $post[$flag] < 1 || $post[$flag] > 5) {
                redirect(lang('review_pot_invalid', $val['name']));
            }
            unset($post[$flag]);
        }
        //价格字段
        if($config['useprice']) {
            if($config['useprice_required'] && (!isset($post['price']) || !is_numeric($post['price']))) {
                redirect(lang('review_price_empty', $config['useprice_title']));
            } elseif(isset($post['price']) && !is_numeric($post['price'])) {
                if($config['useprice_required']) redirect(lang('review_price_empty', $config['useprice_title']));
            }
        }
        //喜欢程度
        if(!isset($post['best']) || !is_numeric($post['best']) || $post[$flag] < 0 || $post[$flag] > 1) {
            redirect('review_best_invalid');
        }
        //字数限制
        if(!$post['content']) {
            redirect('review_content_empty');
        } elseif(strlen($post['content']) < $this->modcfg['review_min'] || strlen($post['content']) > $this->modcfg['review_max']) {
            redirect(lang('review_content_charlen', array($this->modcfg['review_min'], $this->modcfg['review_max'])));
        }
        //如果还存在没开启的点评项时，则报错
        foreach(array_keys($post) as $key) {
            if(preg_match("/^sort[0-9]+$/", $key)) {
                if(!empty($post[$key])) redirect('review_form_invalid');
            }
        }
    }

    //获取引用的图片，并返回格式化的文本
    function get_pictures($picids) {
        $this->db->from('dbpre_pictures');
        $this->db->where('picid', $picids);
        if(!$q = $this->db->get()) return '';
        $result = '';
        while($v=$q->fetch_array()) {
            $result[$v['picid']] = array('thumb'=>$v['thumb'],'picture'=>$v['filename']);
        }
        return serialize($result);
    }

    //列表更新
    function update($review) {
        if($review) foreach($review as $rid => $val) {
            unset($val['rid']);
            $val['digest'] = (int)$val['digest'];
            if(!$val) continue;
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('rid',$rid);
            $this->db->update();
        }
    }

    function add_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_review', 0, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_add('reviews', $num);
        $this->db->update();
    }

    function dec_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_review', TRUE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_dec('reviews', $num);
        $this->db->update();
    }

    function update_review_point($idtype,$id,$config,$exec = TRUE) {
        if(!$typeinfo = $this->get_type($idtype)) {
            return;
        }
        $OBJ = &$this->loader->model($typeinfo['model_name']);
        if(!$object = $OBJ->read($id)) redirect('review_object_empty');

        if(!$rogid = $config['review_opt_gid']) return;
        if(!$opts = $this->variable('opt_' . $rogid)) return;

        $st = round(($this->modcfg['scoretype'] ? $this->modcfg['scoretype'] : 5) / 5); //显示分数制 5分，10分，百分
        $dl = empty($this->modcfg['decimalpoint']) || $this->modcfg['decimalpoint'] < 0 ? 0 : $this->modcfg['decimalpoint']; //小数点位数

        $this->db->from($this->table);
        $this->db->select('status', 'num', 'SUM( ? )');
        $this->db->select('best', 'best', 'SUM( ? )');
        $this->db->where('idtype',$idtype);
        $this->db->where('id', $id);
        $this->db->where('status', 1);

        $set = array(); // 需要更新的字段
        foreach($opts as $key => $val) {
            $flag = $val['flag'];
            $this->db->select($flag, $flag, 'SUM( ? )');
            $set[$flag] = 0;
        }
        $grade = $this->db->get_one();
        if(empty($grade['num'])) {
            $this->db->from($typeinfo['table_name']);
            $this->db->set(array('avgsort'=>0,'avgprice'=>0,'reviews'=>0,'best'=>0,'sort1'=>0,'sort2'=>0,'sort3'=>0,'sort4'=>0,
                'sort5'=>0,'sort6'=>0,'sort7'=>0,'sort8'=>0));
            $this->db->where($typeinfo['key_name'], $id);
            $this->db->update();
            return;
        }

        foreach($opts as $key => $val) {
            $flag = $val['flag'];
            $set[$flag] = round( ($grade[$flag] / $grade['num'] * $st), $dl);
            $set['avgsort'] += $set[$flag];
        }
        $set['avgsort'] = round(($set['avgsort'] / count($opts)), $dl);
        $set['reviews'] = (int)$grade['num'];
        $set['best'] = (int)$grade['best'];

        //价格字段
        if($config['useprice']) {
            $this->db->from($this->table);
            $this->db->select('price', 'price', 'ROUND(AVG( ? ))');
            $this->db->where('idtype',$idtype);
            $this->db->where('id', $id);
            $this->db->where('status', 1);
            $this->db->where_more('price', 1); //金额必须大于等于1
            $avgprice = intval($this->db->get_value());
            $set['avgprice'] = $avgprice;
        } else {
            $set['avgprice'] = 0;
        }

        //更新点评对象
        if($detail) foreach($set as $key => $val) {
            if($val == $detail[$key]) {
                unset($set[$key]);
            }
        }
        if(!count($set)) return $set;
        //如果不更新数据库，则返回更新字段
        if(!$exec) return $set;
        //更新
        $this->db->from($typeinfo['table_name']);
        $this->db->set($set);
        $this->db->where($typeinfo['key_name'],$id);
        $this->db->update();
    }

    //活跃度
    function activity($uid,$username) {
        if(!$uid && !$username) return;
        $post = array();
        if(!$uid || !$username) {
            $this->db->from('dbpre_members');
            $this->db->select('uid,username');
            if($uid) $this->db->where('uid', $uid);
            if($username) $this->db->where('username', $username);
            if(!$res = $this->db->get_one()) return;
            $uid = $res['uid'];
            $username = $res['username'];
        }
        $A =& $this->loader->model($this->model_flag.':activity');
        $A->save($uid, $username);
    }

    //提示权限信息
    function redirect_access($result) {
        $lang = lang('review_access_'.$result['code']);
        $lang = str_replace('{S}', $result['extra'], $lang);
        if($result['code']=='-2') {
            redirect($lang, url('member/login'));
        } else {
            redirect($lang);
        }
    }

    //权限判断
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='review_num') {
            $value = (int) $value;
            if($value && $value < 0) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('item_access_alow_review');
            }
            if($value && $value < $this->global['user']->reviews) {
                if(!$jump) return FALSE;
                redirect('item_access_reviews');
            }
            if($this->modcfg['avatar_review'] && !$this->global['user']->check_avatar()) {
                if(!$jump) return FALSE;
                redirect('review_access_avatar');
            }
        } elseif ($key=='review_repeat') {
            $value = (bool) $value;
            if($value) { //是否限制重复点评
                if(!$jump) return FALSE;
                redirect('item_access_alow_repeat');
            }
        } elseif ($key=='review_viewdigest') {
            $value = (bool) $value;
            if(!$value) { //允许本组会员查看精华点评
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('item_access_alow_viewdigest');
            }
        }
        return TRUE;
    }

    //获取点评对象类名称
    function get_typeinfo($idtype) {
        if(!$typeinfo = $this->get_type($idtype)) {
            redirect('review_idtype_empty');
        }
        return $typeinfo;
    }

    //取得点评最大rid
    function get_max_rid() {
        $this->db->from($this->table);
        $this->db->select('rid', 'rid', 'MAX( ? )');
        $r = $this->db->get_one();
        return $r['rid'];
    }

    function rand($select, $where, $rand_num, $maxrid) {
        $this->db->join($this->table, 'r.id', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        $this->db->where($where);
        $this->db->select($select);
        $num = $rand_num + 30;
        $minrid = $maxrid - $num;
        if($minrid > 0) {
            $this->db->where_more('rid',mt_rand(1,$minrid));
        }
        $this->db->limit(0, $rand_num);
        return $this->db->get();
    }

    //删除主题
    function _delete($where, $update_total = TRUE, $delete_point = FALSE) {
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$update_total && !$delete_point) {
            $this->db->delete();
            return;
        }
        if(!$q=$this->db->get()) return;

        $uids = $delids = $subjects = array();
        while($value=$q->fetch_array()) {
            if(!$this->in_admin && $this->global['user']->uid != $value['uid']) {
                redirect('global_op_access');
            }
            $delids[] = $value['rid'];
            if($value['status']) {
                //点评对象统计
                if(!is_array($subjects[$val['idtype']])) $subjects[$val['idtype']] = array();
                if($update_total && !in_array($val['id'], $subjects[$val['idtype']])) {
                    $subjects[$val['idtype']][$val['id']] = array(
                        'pid' => $val['pcatid'],
                        'idtype' => $val['idtype'],
                        'id' => $val['id'],
                    );
                }
                //会员积分更新
                if($value['uid'] && $delete_point) {
                    if(isset($uids[$value['uid']])) {
                        $uids[$value['uid']]++;
                    } else {
                        $uids[$value['uid']] = 1;
                    }
                }
                //删除标签
                if($update_total && $value['taggroup'] && $value['idtype']=='item_subject') {
                    if($taggroup = @unserialize($value['taggroup'])) {
                        $TAG =& $this->loader->model('item:tag');
                        $TAG->delete_batch($value['city_id'], $value['id'], $taggroup);
                    }
                }
            }
        }

        //删除记录
        if($delids) {
            $this->db->from($this->table);
            $this->db->where_in('rid', $delids);
            $this->db->delete();
        }

        //删除用户的对应积分
        if($delete_point && $uids) {
            $P =& $this->loader->model('member:point');
            foreach($uids as $uid => $num) {
                $P->update_point($uid, 'add_review', TRUE, $num);
            }
        }

        //更新主题分数和统计
        if($subjects) foreach($subjects as $idtype => $subject) {
            if(!$typeinfo = $this->get_type($idtype)) continue;
            $OBJ = &$this->loader->model($typeinfo['model_name']);
            foreach($subject as $val) {
                $config = $OBJ->get_review_config($val['pid']);
                $this->update_review_point($idtype,$val['id'],$config);
            }
        }
    }

    //发送提醒
    function _notice_post($rid) {
        if(!$rid) return;

        if(!$detail = $this->read($rid)) return;
        if(!$detail['id']||$detail['idtype']!='item_subject') return;

        $uids = array();
        $sid = $detail['id'];
        $S = $this->loader->model('item:subject');
        $subject = $S->read($sid,'sid,owner,cuid,creator',false);
        if($subject['cuid']>0) $uids[] = $subject['cuid'];
        if($subject['owner']) {
            $members = $S->owners($sid);
            if($members) foreach($members as $val) {
                $uids[] = $val['uid'];
            }
        }

        if(!$uids) return;
        $i = array_search($detail['uid'], $uids);
        if($i!== false) {
            unset($uids[$i]);
            if(!$uids) return;
        }

        $c_username = '<a href="'.url("space/index/uid/$detail[uid]").'" target="_blank">'.$detail['username'].'</a>';
        $c_subject = '<a href="'.url("item/detail/id/$detail[id]").'" target="_blank">'.$detail['subject'].'</a>';
        $c_review = url("review/detail/id/$detail[rid]");
        $note = lang('review_notice_new_review',array($c_username, $c_subject, $c_review));

        $N = $this->loader->model('member:notice');
        $N->save($uids,'review','post',$note);
    }

    //feed
    function _feed($rid) {
        if(!$rid) return;

        $this->_notice_post($rid); //提醒

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($rid)) return;
        if($detail['uid'] > 0) {
            $typeinfo = $this->get_type($detail['idtype']);
            $feed = array();
            $feed['icon'] = lang('review_feed_icon');
            $feed['title_template'] = lang('review_feed_title_template');
            $feed['title_data'] = array (
                'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
                'subject' => $detail['subject'],
            );
            $feed['body_template'] = lang('review_feed_body_template');
            $url = $detail['title'] ? url("review/detail/id/$rid") : url(str_replace('_ID_',$detail['id'],$typeinfo['detail_url']));
            $feed['body_data'] = array (
                'title' => '<a href="'.$url.'">'.($detail['title']?$detail['title']:$detail['subject']).'</a>',
                'respond' => '<a href="'.url("review/detail/id/$detail[rid]",'respond').'">'.lang('review_respond').'</a>',
            );
            $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);
            $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
        }
        if($detail['idtype']=='item_subject') {
            $this->_feed_subject($FEED, $detail);
        }
    }

    function _feed_subject(&$FEED, &$detail) {
        if(!$subject = $this->loader->model('item:subject')->read($detail['id'])) return;
        $this->global['fullalways'] = TRUE;
        $param = array();
        $param['flag'] = 'item';
        $param['uid'] = 0;
        $param['username'] = '';
        $param['icon'] = lang('party_feed_add_icon');
        $param['sid'] = $detail['id'];
        
        $feed = array();
        $feed['title_template'] = lang('review_feed_subject_title_template');
        $feed['title_data'] = array (
            'item_name' => '<a href="'.url("item/list/catid/$subject[pid]").'">' . display('item:model', "catid/$subject[pid]") . '</a>',
            'subject' => '<a href="'.url("item/detail/id/$subject[sid]").'">' . $subject['name'] . '</a>',
        );
        $feed['body_template'] = lang('review_feed_subject_body_template');
        $feed['body_data'] = array (
            'subject' => '<a href="'.url("review/detail/id/$detail[rid]").'">'.($detail['title']?$detail['title']:$detail['subject']).'</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);
        $FEED->save_ex($param, $feed);
    }

    //同步点评到第三方平台
    function _share($passports, $rid) {
        if(!$rid||!$passports) return;
        if(!$detail = $this->read($rid)) return;
        if(!$detail['id']||$detail['idtype']!='item_subject') return;
        foreach ($passports as $psname => $enable) {
            if(!$enable) continue;
            $_content = trimmed_title($detail['content'], 60, '...');
            $_subject = $detail['subject'] . ' ' . url('item/detail/id/'.$detail['id'], '', 1);
            $text = lang('review_share_new_review', array($_content,$_subject));
            $succeed = $this->loader->lib($psname)->post_text($text);
        }
    }
}
?>