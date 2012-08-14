<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_task extends ms_model {

	var $table = 'dbpre_task';
    var $mytable = 'dbpre_mytask';
    var $key = 'taskid';

    var $modcfg = null;
    var $cache_name = 'task';
    var $tasks = array();
    var $add_feed = true;

    var $tt = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
        $this->init_field();
        $this->tt =& $this->loader->model('member:tasktype');
    }

	function init_field() {
		$this->add_field('enable,taskflag,title,des,icon,starttime,endtime,period,period_unit,pointtype,point,listorder,access,access_groupids,reg_automatic,config');
		$this->add_field_fun('enable,period,point,listorder,access,reg_automatic', 'intval');
        $this->add_field_fun('taskflag,title,period_unit,pointtype,starttime,endtime', '_T');
        $this->add_field_fun('starttime,endtime', 'strtotime');
        $this->add_field_fun('access_groupids', '_ArrayToStr');
        $this->add_field_fun('config', 'serialize');
        $this->add_field_fun('des', '_HTML');
	}

    function save($post,$taskid=null) {
        $edit = $taskid > 0;
        if($edit) {
            $detail = $this->read($taskid);
            if(empty($detail)) redirect('member_task_empty');
            $post['taskflag'] = $detail['taskflag'];
        }
        if(!$post['starttime']) $post['starttime'] = date('Y-m-d H:i', $this->global['timestamp']);
        $taskid = parent::save($post, $taskid);
        return $taskid;
    }

    function update($post) {
        if(empty($post)) redirect('global_op_unselect');
        foreach($post as $taskid => $set) {
            $set['enable'] = (int) $set['enable'];
            $this->db->from($this->table);
            $this->db->set($set);
            $this->db->where('taskid', $taskid);
            $this->db->update();
        }
    }

    function check_post($post, $edit=false) {
        //$this->loader->helper('validate');
        if(!$post['taskflag']&&!$edit) redirect('member_task_post_flag_empty');
        if(!$post['title']) redirect('member_task_post_title_empty');
        if(!$post['des']) redirect('member_task_post_desc_empty');
        //if($post['starttime'] && !validate::is_datetime($post['starttime'])) redirect('member_task_post_starttime_invalid');
        //if($post['endtime'] && !validate::is_datetime($post['endtime'])) redirect('member_task_post_endtime_invalid');
        if($post['endtime'] && $post['starttime'] && $post['starttime'] >= $post['endtime']) redirect('member_task_post_time_invalid');
        if($post['period'] && !is_numeric($post['period'])) redirect('member_task_post_period_invalid');
        if($post['period_unit']>0 && $post['period']>0) {
            if($post['period_unit']=='2'&&($post['period']>7)) redirect('member_task_post_period_week_invalid');
            if($post['period_unit']=='3'&&($post['period']>31)) redirect('member_task_post_period_month_invalid');
        }
        if(!$post['pointtype']) redirect('member_task_post_pointtype_empty');
        if($post['point'] && (!is_numeric($post['point'])||$post['point']<0)) redirect('member_task_post_point_invalid');
        if($post['access']=='2'&&!$post['access_groupids']) redirect('member_task_post_access_groupids_empty');
        $tsk = $this->tt->instantiate($post['taskflag']);
        if(method_exists($tsk,'form_post')) {
            $post['config'] && $post['config'] = unserialize($post['config']);
            $tsk->form_post($post['config']);
        }
        unset($tsk);
    }

    function delete($taskid) {
        $detail = $this->read($taskid);
        if(empty($detail)) redirect('member_task_empty');
        $this->_delete($detail['taskflag']);
        parent::delete($taskid);
    }

    function delete_taskflag($taskflag) {
        $this->db->from($this->table);
        $this->db->where('taskflag', $taskflag);
        $this->db->select('taskid,taskflag');
        if(!$q = $this->db->get()) return;
        $taskids = array();
        while($v=$q->fetch_array()) {
            $this->_delete($v['taskflag']);
            $taskids[] = $v['taskid'];
        }
        if($taskids) {
            $this->delete_mytask($taskids);
            parent::delete($taskids);
        }
    }

    function delete_mytask($taskids) {
        $this->db->from($this->mytable);
        $this->db->where('taskid', $taskids);
        $this->db->delete();
    }

    function find($page, $offset=20) {
        $result = array(0,null);
        $this->db->from($this->table);
        if($result[0] = $this->db->count()) {
            $this->db->sql_roll_back('from,where');
            $this->db->order_by('listorder','ASC');
            $this->db->limit(get_start($page,$offset),$offset);
            $result[1] = $this->db->get();
        }
        return $result;
    }

    function read_mytask($taskid) {
        $this->db->from($this->mytable);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->where('taskid', $taskid);
        $detail = $this->db->get_one();
        if(!empty($detail)) {
            $detail['apply_again'] = $this->_ck_again_apply($detail['taskid']);
        }
        return $detail;
    }

    function newtask() {
        $where = array();
        $where['enable'] = 1;
        $where['{sql}'] = "(endtime=0 OR endtime >= {$this->global['timestamp']})";
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->order_by('listorder','ASC');
        if(!$q = $this->db->get()) return;
        $list = array();
        while($v=$q->fetch_array()) {
            $again = $this->_ck_again_apply($v);
            if($again < 0 || $again > 1) continue;
            $v['access'] = $this->_ck_access($v);
            $list[] = $v;
        }
        $q->free_result();
        return $list;
    }

    //自动申请
    function automatic_apply() {
        $where = array();
        $where['enable'] = 1;
        $where['reg_automatic'] = 1;
        $where['{sql}'] = "(endtime=0 OR endtime >= {$this->global['timestamp']})";
        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->order_by('listorder','ASC');
        if(!$q = $this->db->get()) return;
        $this->add_feed = false;
        while($v=$q->fetch_array()) {
            $this->apply($v, false);
        }
        $q->free_result();
        $this->add_feed = true;
    }

    //status -1:failed, 0:doing, 1:done
    function mytask($status) {
        $this->db->select('t.*,m.*');
        $this->db->join($this->mytable, 'm.taskid', $this->table, 't.taskid', 'LEFT JOIN');
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('status',$status);
        $this->db->order_by('listorder','ASC');
        if(!$q = $this->db->get()) return;
        $list = array();
        while($v=$q->fetch_array()) {
            if($status=='1') {
                if($v['period'] > 0) $v['apply_again'] = $this->_ck_again_apply($v);
            } elseif($status == '-1') {
            } elseif(!$status) {
                if($v['progress'] < 100) {
                    $progress = $this->check_progress($v);
                    if($progress == -1) {
                        //任务失败
                        $this->fail_task($v['taskid']);
                    } elseif($v['progress'] != $progress) {
                        //更新任务进度
                        $this->update_progress($v['taskid'],$progress);
                        $v['progress'] = $progress;
                    }
                }
            }
            $list[] = $v;
        }
        $q->free_result();
        return $list;
    }

    //获取进度已经100%，未领取奖励的任务
    function task_done_count() {
        $this->db->from($this->mytable);
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('status',0);
        $this->db->where('progress',100);
        return $this->db->count();
    }

    function apply($taskid, $show_msg = true) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        $taskid = $detail['taskid'];
        if(!$detail) {
            if(!$show_msg) return false;
            redirect('member_task_mepty');
        }
        $cktime = $this->_ck_time($detail); //判断活动时间范围
        if($cktime<0) {
            if(!$show_msg) return false;
            redirect('member_task_apply_cktime_'.$cktime);
        }
        if(!$this->_ck_access($detail)) {
            if(!$show_msg) return false;
            redirect('member_task_apply_access'); //判断权限
        }
        $again = $this->_ck_again_apply($taskid); //判断是否已经申请，和是否符合重复申请
        if($again < 0 || $again > 1) {
            if(!$show_msg) return false;
            redirect('member_task_apply_exists');
        }

        $this->db->from($this->mytable);
        $this->db->set(array(
            'progress' => '0',
            'dateline' => $this->global['timestamp'],
            'status' => '0',
            'applytime' => $this->global['timestamp'],
        ));
        if($again) {//更新
            $this->db->where('uid', $this->global['user']->uid);
            $this->db->where('taskid', $taskid);
            $this->db->update();
        } else { //新增
            $this->db->set('uid', $this->global['user']->uid);
            $this->db->set('taskid', $taskid);
            $this->db->set('username', $this->global['user']->username);
            $this->db->insert();
            //增加申请成功的人数
            $this->db->from($this->table);
            $this->db->set_add('applys', 1);
            $this->db->where('taskid', $taskid);
            $this->db->update();
        }
        if($this->add_feed) $this->_feed($detail, 'add');
    }

    //会员领取奖励
    function finish_task($taskid) {
        if(!$mytask = $this->read_mytask($taskid)) redirect('member_task_apply_not');
        if($mytask['status'] == '1') redirect('member_task_finished'); //已经完成了
        $detail = $this->read($taskid);
        if(empty($detail)) redirect('member_task_empty');
        if($mytask['progress'] < 100) {
            $progress = $this-check_progress($detail);
            if($progress < 100) redirect('member_task_not_finished');
        }

        //任务完成
        $this->db->from($this->mytable);
        $this->db->set('progress', 100);
        $this->db->set('status', 1);
        $this->db->set_add('total', 1);
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('taskid',$taskid);
        $this->db->set('dateline',$this->global['timestamp']);
        $this->db->update();

        //增加任务成功的人数
        $this->db->from($this->table);
        $this->db->where('taskid', $taskid);
        $this->db->set_add('completes', 1);
        $this->db->update();
        //领取积分奖励
        $this->_add_user_point($this->global['user']->uid, $detail['pointtype'], $detail['point'], $detail['title']);
        //添加事件
        if($this->add_feed) $this->_feed($detail, 'done');
    }

    //任务失败
    function fail_task($taskid) {
        //member_task_failed
        $this->db->from($this->mytable);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->where('taskid', $taskid);
        $this->db->set('status', -1);
        $this->db->set('dateline',$this->global['timestamp']);
        $this->db->update();
    }

    //会员放弃任务
    function cancel_mytask($taskid) {
        $mytask = $this->read_mytask($taskid);
        if(!$mytask) redirect('member_task_apply_not');
        if($mytask['uid'] != $this->global['user']->uid) redirect('member_task_apply_not_myself');
        if($mytask['status']=='1') redirect('member_task_apply_delete_done');
        $this->db->from($this->mytable);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->where('taskid', $taskid);
        $this->db->delete();
        //更新任务申请人数
        $this->db->from($this->table);
        $this->db->where('taskid',$taskid);
        $this->db->set_dec('applys',1);
        $this->db->update();
    }

    //判断进度
    function check_progress($taskid) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        if(!$detail) return false;
        $tsk =& $this->tt->instantiate($detail['taskflag']);
        $progress = $tsk->progress($detail);
        unset($tsk);
        return $progress;
    }

    //是否可以申请（综合判断）
    function check_access($taskid) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        if(!$detail) return false;
        if($this->_ck_time($detail)<0) return false;
        if(!$this->_ck_access($detail)) return false;
        $again = $this->_ck_again_apply($detail);
        if($again < 0 || $again > 1) return false;
        return true;
    }

    //0表示正常,-1表示尚未开始,-2:已结束
    function _ck_time($taskid) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        if($detail['starttime'] > $this->global['timestamp']) return -1;
        if($detail['endtime']>0 && $detail['endtime'] < $this->global['timestamp']) return -2;
        return 0;
    }

    //更新任务进度
    function update_progress($taskid,$progress) {
        $this->db->from($this->mytable);
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('taskid',$taskid);
        $this->db->set('progress',$progress);
        $this->db->set('dateline',$this->global['timestamp']);
        $this->db->update();
    }

    //true表示允许申请,flase表示没有权限
    function _ck_access($taskid) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        if(!$detail) return false;
        switch($detail['access']) {
            case 0: //全部注册用户
                return $this->global['user']->uid > 0;
            case 1: //普通用户组
                if(!$groups = $this->loader->variable('usergroup','member')) return false;
                $grouptype = $groups[$this->global['user']->groupid]['grouptype'];
                return in_array($grouptype,array('member','special'));
            case 2: //指定用户组
                if(!$groupids = $detail['access_groupids']) return false;
                $groupids = explode(',', $groupids);
                return $this->global['user']->uid > 0 && in_array($this->global['user']->groupid, $groupids);
            default:
                return false;
        }
    }

    //判断允许申请（包括已经申请）-1表示不允许，-2尚未完成，0表示允许申请，1表示可以再次申请，>1表示剩余多少秒后才能申请
    function _ck_again_apply($taskid) {
        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        $taskid = $detail['taskid'];
        $this->db->from($this->mytable);
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('taskid',$taskid);
        $r = $this->db->get_one();
        if(!$r) return 0;
        if($r['status']=='0') return -1;
        if(!$detail['period']) return -2;
        switch($detail['period_unit']) {
            case 0://hour
                $time = $detail['period'] * 3600;
                break;
            case 1://day
                $td = strtotime(date('Y-m-d',strtotime("+1 day", $r['dateline']))) - $r['dateline'];
                $time = $td + (($detail['period']-1) * 24 * 3600);
                break;
            case 2://week
                $w = date('w', $r['dateline']);
                if($w >= $detail['period']) {
                    $nweek = 7-$w+$detail['period'];
                } else {
                    $nweek = $detail['period'] - $w;
                }
                $nexttime = strtotime(date('Y-m-d', $r['dateline'] + ($nweek * 24 * 3600)));
                if($nexttime <= $this->global['timestamp']) return 1;
                return $nexttime - $this->global['timestamp'];
            case 3://mouth
                $j = date('j', $r['dateline']);
                $t = date('t', $r['dateline']);
                if($j >= $detail['period']) {
                    $day = $t-$j+$detail['period'];
                } else {
                    $day = $detail['period'] - $j;
                }
                $nexttime = strtotime(date('Y-m-d', $r['dateline'] + ($day * 24 * 3600)));
                if($nexttime <= $this->global['timestamp']) return 1;
                return $nexttime - $this->global['timestamp'];
        }
        if($r['dateline'] + $time < $this->global['timestamp']) return 1;
        return $r['dateline'] + $time - $this->global['timestamp'];
    }

    function _delete($taskflag) {
        $tsk = $this->tt->instantiate($taskflag);
        method_exists($tsk, 'delete') && $tsk->delete($taskid);
        unset($tsk);
        //$this->db->from($this->mytable);
        //$this->db->where('taskid',$taskid);
        //$this->db->delete();
    }

    //领取积分奖励
    function _add_user_point($uid, $pointtype, $point, $title='') {
        if(!$uid) return;
        $des = lang('member_task_point_log_des', $title);
        $P =& $this->loader->model('member:point');
        $P->update_point2($uid, $pointtype, $point, $des);
    }

    //$type:add,done
    function _feed($taskid,$type='add') {
        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        $detail = is_numeric($taskid) ? $this->read($taskid) : $taskid;
        if(!$detail) return;

        $feed = array();
        $feed['icon'] = lang('member_task_feed_'.$type.'_icon');
        $feed['title_template'] = lang('member_task_feed_'.$type.'_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url('space/index/uid/'.$this->global['user']->uid).'">'.$this->global['user']->username.'</a>',
        );
        $feed['body_template'] = lang('member_task_feed_'.$type.'_body_template');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("member/index/ac/task/op/view/taskid/$detail[taskid]").'">'.$detail['title'].'</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $this->global['user']->uid, $this->global['user']->username, $feed);
    }

}