<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$type = _input('type',null,MF_TEXT);
$op = _input('op',null,MF_TEXT);
$new_msgs = 0;
$M =& $_G['loader']->model('ucenter:message');

if($type=='public') {
    $L_M =& $_G['loader']->model('member:message',TRUE,NULL,FALSE);
    switch($op) {
    case 'delete':
        $L_M->delete($user->uid, $_POST['folder'], $_POST['pmids']);
        redirect('global_op_succeed', url('ucenter/index/ac/'.$ac.'/type/public'));
        break;
    default:
        $folder = 'inbox';
        $offset = 20;
        list($total, $list) = $L_M->find($user->uid, $folder, array('posttime'=>'DESC'), get_start($_GET['page'], $offset), $offset);
        if(!$total && $folder == 'inbox' && $user->newmsgs > 0) {
            $L_M->clear_new_record($user->uid);
        }
        $sub_param = array('inbox' => lang('member_pm_inbox'), 'outbox' => lang('member_pm_outbox'));
        $multipage = multi($total, $offset, $_GET['page'], url("ucenter/member/ac/$ac/type/public/page/_PAGE_"));
    }
} else {
    if($op=='uc') {
        uc_pm_location($user->uid);
        exit;
    } else {
        $new_msgs = $M->get_new_pbulic_num($user->uid);
    }
}
?>