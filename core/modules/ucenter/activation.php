<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$membercfg = $_G['loader']->variable('config','member');
if($membercfg['closereg']) redirect('member_reg_closed');

$auth = trim($_G['cookie']['activationauth']);
list($uid,$username) = explode("\t",base64_decode($auth));

if(!$uid || !$username || ($username != $_G['cookie']['username'])) redirect('ucenter_activation_invalid');

//$result = uc_user_login($username , $password);
if(!$member = uc_get_user($username)) redirect('ucenter_activation_error');
if($member[0] != $uid) redirect('ucenter_activation_uid_invalid');

$post = array();
list($post['uid'],$post['username'],$post['email']) = $member;
$post['password'] = mt_rand(100000,999999);
$user->register($post, TRUE);

unset($auth, $post);
del_cookie(array('username','activationauth'));

redirect('ucenter_activation_succeed', url('member/index'));
?>