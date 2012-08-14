<?php
/**
* @author moufer<moufer@163.com>
* @pageage space
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'space_index');

$uid = _get('uid', 0, MF_INT_KEY);
if(!$uid) {
    if($username = _T(_get('username'))) {
        if($member = $user->read($username,TRUE)) {
            $uid = $member['uid'];
        }
    }
    !$uid && $uid = $user->uid;
    if(!$uid) location('index');
}
//跳转
if(defined('IN_UC')) {
    $ucfg = $_G['loader']->variable('config','ucenter');
    if($ucfg['uc_uch'] &&$ucfg['uc_uch_url']) {
        header("Location:".$ucfg['uc_uch_url']."/?".$uid);
    }
}

if(!isset($member) || !is_array($member)) {
    $member = $user->read($uid);
    if(empty($member)) redirect('space_not_exists');
}

$SA =& $_G['loader']->model(':space');
if(!$space = $SA->read($uid)) {
    $SA->create($uid, $member['username']);
    $space = $SA->read($uid);
}

// 添加的主题
$S =& $_G['loader']->model('item:subject');
$where = array();
$where['cuid'] = $uid;
$where['status'] = 1;
$offset = $MOD['index_subjects'] > 0 ? $MOD['index_subjects'] : 5;
list(,$subjects) = $S->find('sid,name,subname,pid,catid,avgsort,reviews,pictures,thumb,addtime', 
    $where, array('addtime' => 'DESC'), 0, $offset, FALSE);

//发表的点评
$R =& $_G['loader']->model(':review');
//载入标签
$taggroups = $_G['loader']->variable('taggroup','item');
$where = array();
$where['uid'] = $uid;
$where['status'] = 1;
$select='*';
$offset = $MOD['index_subjects'] > 0 ? $MOD['index_subjects'] : 5;
list(,$reviews) = $R->find($select, $where, array('posttime' => 'DESC'), 0, $offset, FALSE);

//好友
$F =& $_G['loader']->model('member:friend');
list(,$friends) = $F->friend_ls($uid);

//更新浏览量
$SA->pageview($uid);

$_HEAD['description'] = $space['spacename'] . ',' . $space['spacedescribe'];

if($templateid = _get('templateid',null,MF_INT_KEY)) {
    $space['space_styleid'] = $templateid;
}
if($space['space_styleid']) {
    include template('space_index', 'space', $space['space_styleid']);
} else {
    //载入模型的内容页模板
    include template('space_index');
}
?>