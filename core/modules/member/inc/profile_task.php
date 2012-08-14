<?php
/**
 * 会员信息完善
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_member_profile {

    var $flag = 'member:profile';
    var $title = 'member_task_profile_title';
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
        $result = array();
        $result[] = array(
            'title' => '需要完善的字段',
            'des' => '勾选需要会员完善的字段',
            'content' => form_check('config[fields][]',
                array(
                    'realname'=>'真实姓名',
                    'mobile'=>'手机号码',
                    'gender'=>'性别',
                    'birthday'=>'生日',
                    'alipay'=>'支付宝',
                    'qq'=>'QQ',
                    'msn'=>'MSN',
                    'address'=>'地址',
                    'zipcode'=>'邮编',
                ),
                $cfg['fields'],
                '',
                '&nbsp;'
            ),
        );
        return $result;
    }

    // 任务完成条件表单的提交检测
    function form_post($cfg) {
        if(!$cfg['fields']) redirect('您未选用户择需要完善的字段。');
        return true; 
    }

    // 检测任务进度 0-100
    function progress(& $task_detail) {
        global $_G;

        $uid = $_G['user']->uid;
        $MP = $_G['loader']->model('member:profile');
        $profile = $MP->read($uid);

        $config = unserialize( $task_detail['config'] );
        if($config) $fields = $config['fields'];
        if(!$config) return 0;

        $s = 0;
        foreach($fields as $k) {
            if(!empty($_G['user']->$k)) $s++;
        }
        if(!$s) return 0;

        $p = round($s / count($fields) * 100);
        return $p;
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