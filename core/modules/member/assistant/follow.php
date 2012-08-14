<?php
/**
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);
$F =& $_G['loader']->model('member:favorite');

switch ($op) {
    case 'add':
        if(!$uid = (int)$_POST['uid']) redirect(lang('global_sql_keyid_invalid', 'uid'));
        $post = array('id' => $uid);
        $F->save($post);
        echo 'OK';
        output();
        break;
    case 'delete':
        if(!$uid = (int)$_POST['uid']) redirect(lang('global_sql_keyid_invalid', 'uid'));
        $F->unfollow($uid);
        echo 'OK';
        output();
        break;
    case 'fans':
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $F->get_fans($user->uid, $start, $offset);
        $subtitle = '我的粉丝';
        $active = array(
            'fans' => ' class="active"',
        );
        break;
    default:
        $offset = 40;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $F->get_follower($user->uid, $start, $offset);
        $subtitle = '我的关注';
        $active = array(
            'follow' => ' class="active"',
        );
}

/* end */