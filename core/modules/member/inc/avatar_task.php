<?php
/**
 * 头像类任务（任务类开发样本）
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_member_avatar {

    var $flag = 'member:avatar';
    var $title = 'member_task_avatar_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    // 生成后台任务完成条件的表单
    function form($cfg) { return; }

    // 任务完成条件表单的提交检测
    function form_post($cfg) { return true; }

    // 检测任务进度 0-100
    function progress(& $task_detail) {
        global $_G;
        //检测头像是否存在
        return $_G['user']->check_avatar() ? 100 : 0;
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