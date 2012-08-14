<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$F =& $_G['loader']->model(MOD_FLAG.':friend');

switch($op) {
case 'add':
    if(check_submit('dosubmit')) {
        $friend_uid = (int) $_POST['friend_uid'];
        $F->save($user->uid, $friend_uid);

        $MSG =& $_G['loader']->model(MOD_FLAG.':message');
        $subject = lang('member_friend_msg_subject');
        $message = $_POST['message'] ? _TA($_POST['message']) : lang('member_friend_msg_message');
        $MSG->send($user->uid, $friend_uid, $subject, $message);

        redirect('global_op_succeed', url('member/index/ac/friend'));
    } else {
        $post = array();
        $friend_uid = (int) $_POST['friend_uid'];
        if($user->uid == $friend_uid) {
            redirect('member_friend_add_self');
        } elseif(!$friend = $user->read($friend_uid, FALSE, 'uid,username,groupid')) {
            redirect('member_friend_not_found');
        } elseif($F->check_exists($user->uid, $friend_uid)) {
            redirect('member_friend_exists');
        }
        $tplname = 'friend_add';
    }
    break;
case 'delete':
    $F->delete($user->uid, $_POST['friendids']);
    redirect('global_op_succeed_delete', get_forward(url('member/index/ac/'.$ac)));
    break;
default:
    $offset = 10;
    list($total, $list) = $F->friend_ls($user->uid, $_GET['page'], $offset);
    $total && $multipage = multi($total, $offset, $_GET['page'], url('member/index/ac/'.$ac.'/pid/'.$pid.'/page/_PAGE_'));
    $tplname = 'friend_list';
    break;
}
?>