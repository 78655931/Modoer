<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'space_reviews');

$uid = _get('uid',0,MF_INT_KEY);
if(!$uid) redirect(lang('global_sql_keyid_invalid', 'uid'));

$SA =& $_G['loader']->model(':space');
$space = $SA->read($uid);
$member = $user->read($uid);
if(!$member) redirect('index');
//载入标签
$taggroups = $_G['loader']->variable('taggroup','item');

//发表的点评
$R =& $_G['loader']->model(':review');
$where = array();
$where['uid'] = $uid;
$where['status'] = 1;
$offset = $MOD['reviews'] > 0 ? $MOD['reviews'] : 20;
$start = get_start($_GET['page'], $offset);
$select = '*';
list($total, $reviews) = $R->find($select, $where, array('posttime' => 'DESC'), $start, $offset, TRUE);
if($total) {
    $multipage = multi($total, $offset, $_GET['page'], url("space/reviews/uid/$uid/page/_PAGE_"));
}

$_HEAD['description'] = $space['spacename'] . ',' . $space['spacedescribe'];

if($space['space_styleid']) {
    include template('space_reviews', 'space', $space['space_styleid']);
} else {
    //载入模型的内容页模板
    include template('space_reviews');
}
?>