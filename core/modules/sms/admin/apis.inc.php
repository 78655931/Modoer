<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$_G['loader']->model('sms:factory',false);

switch($op) {
    case 'config':
        $API = msm_sms_factory::create(_input('api',null,MF_TEXT));
        $admin->tplname = cptpl('api_config', MOD_FLAG);
        break;
    case 'progress_config':
        msm_sms_factory::create(_input('api',null,MF_TEXT))->save_config();
        redirect('global_op_succeed',cpurl($module, $act));
        break;
    case 'use':
        $API = msm_sms_factory::create(_input('api',null,MF_TEXT))->set_use();
        redirect('global_op_succeed',cpurl($module, $act));
    case 'send':
        $API = msm_sms_factory::create(_input('api',null,MF_TEXT));
        $admin->tplname = cptpl('api_send', MOD_FLAG);
        break;
    case 'progress_send':
        $mobile = _input('mobile',null,MF_TEXT);
        $message = _input('message',null,MF_TEXT);
        $API = msm_sms_factory::create(_input('api',null,MF_TEXT));
        $return = $API->send($mobile,$message);
        if($return) {
            redirect('global_op_succeed',cpurl($module, $act));
        } else {
            redirect($API->get_error_msg());
        }
        break;
    default:
        $APIS =& $_G['loader']->model('sms:collection');
        $admin->tplname = cptpl('api_list', MOD_FLAG);
}
?>