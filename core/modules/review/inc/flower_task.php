<?php
/**
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_review_flower {

    var $flag = 'review:flower';
    var $title = 'review_task_flower_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    // 生成后台任务完成条件的表单
    function form($cfg) {
        global $_G;
        $result = array();
        $_G['loader']->helper('form','item');
        $result[] = array(
            'title' => '赠送鲜花数量',
            'des' => '此任务要求会员给点评赠送鲜花数量，默认为 1 条',
            'content' => form_input('config[num]',$cfg['num']>0?$cfg['num']:1,'txtbox4'),
        );
        $result[] = array(
            'title' => '时间限制（小时）',
            'des' => '设置会员从申请任务到完成任务的时间限制，会员在此时间内未能完成任务则不能领取奖励并标记任务失败，0 或留空为不限制',
            'content' => form_input('config[time_limit]',$cfg['time_limit'],'txtbox4'),
        );
        return $result; 
    }

    // 任务完成条件表单的提交检测
    function form_post($cfg) { 
        if(!$cfg['num'] || !is_numeric($cfg['num']) || $cfg['num'] < 1) redirect('完成条件错误：未填写一个有效的赠送鲜花数量。');
        if($cfg['time_limit'] && (!is_numeric($cfg['time_limit'])||$cfg['time_limit']<0)) redirect('完成条件错误：未填写一个有效的时间限制。');
        return true; 
    }

    // 检测任务进度 0-100
    function progress(& $task_detail) {
        global $_G;
        $db =& $_G['db'];
        $taskid = $task_detail['taskid'];
        $db->from('dbpre_mytask');
        $db->where('uid',$_G['user']->uid);
        $db->where('taskid',$taskid);
        $mytask = $db->get_one();
        if(!$mytask) return 0;

        $cfg = @unserialize($task_detail['config']);
        if(!$cfg) return 0;

        if($cfg['time_limit']>0) {
            $timelimit = $cfg['time_limit'] * 3600;
            if($mytask['applytime'] + $timelimit < $_G['timestamp']) return -1;//任务失败
        }

        $db->from('dbpre_flowers');
        $db->where('uid', $_G['user']->uid);
        $db->where_more('dateline', $mytask['applytime']);
        if(!$total = $db->count()) return 0;
        $min = min($cfg['num'], $total);
        return round($min / $cfg['num'] * 100);
    }

    // 会员申请任务时触发
    function apply($taskid) {}

    // 后台删除任务时触发
    function delete($taskid) {}

    // 安装本任务类时触发
    function install() {}

    // 卸载本任务类时触发
    function unstall() {}
}
?>