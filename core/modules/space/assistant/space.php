<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$SPACE =& $_G['loader']->model(':space');

if(!check_submit('dosubmit')) {

    //自动判断和创建空间
    $SPACE->create($user->uid, $user->username);
    $detail = $SPACE->read($user->uid);
    
    $_G['loader']->helper('form');
    $tplname = 'space';

} else {

    $post = $SPACE->get_post($_POST);
    $SPACE->save($post, $user->uid);

    redirect('global_op_succeed', url('space/member/ac/space'));

}
?>