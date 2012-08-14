<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'space_friends');

$uid = _get('uid',0,MF_INT_KEY);
if(!$uid) redirect(lang('global_sql_keyid_invalid', 'uid'));

$SA =& $_G['loader']->model(':space');
$space = $SA->read($uid);
$member = $user->read($uid);
if(!$member) redirect('index');
//好友列表
$F =& $_G['loader']->model('member:friend');
$offset = 10;
list($total, $friends) = $F->friend_ls($uid,$_GET['page'],$offset);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("space/friends/uid/$uid/page/_PAGE_"));
}

$_HEAD['description'] = $space['spacename'] . ',' . $space['spacedescribe'];

if($space['space_styleid']) {
    include template('space_index', 'space', $space['space_styleid']);
} else {
    //载入模型的内容页模板
    include template('space_friends');
}
?>