<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'forums', 'threads' );
$login_ops = array( );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}

$_G['loader']->helper('modcenter');

switch($op) {

case 'threads':
	$sid = _input('sid',NULL,MF_INT_KEY);
    $S =& $_G['loader']->model(MOD_FLAG.':subject');
    if(!$detail = $S->read($sid)) redirect('item_empty');
    $forumid = _input('forumid',NULL,MF_INT_KEY);
    $offset = $MOD['forum_num'] > 0 ? $MOD['forum_num'] : 10;
    $start = get_start($_GET['page'], $offset);
	$forums = modcenter::get_threads($forumid);

    $category = $S->get_category($detail['catid']);
    if(!$detail['templateid'] && $category['config']['templateid']>0) $detail['templateid'] = $category['config']['templateid'];

    if($detail['templateid']>0) {
        include template('part_forum', 'item', $detail['templateid']);
    } else {
        include template('item_subject_detail_forum');
    }
    output();
    break;
default:
    redirect('global_op_unkown');
}
?>