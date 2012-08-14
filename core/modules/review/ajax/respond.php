<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'get', 'post', 'delete' );
$login_ops = array( 'post', 'delete' );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}

switch($op) {
default:
    if(!$rid = (int)$_POST['rid']) redirect(lang('global_sql_keyid_invalid', 'rid'));
    $RP =& $_G['loader']->model(MOD_FLAG.':respond');

    $select = 'respondid,rid,uid,username,content,posttime';
    $where = array ( 'rid' => $rid, 'status' => 1 );
    $offset = $MOD['respond_num'] > 0 ? $MOD['respond_num'] : 10;
    $start = get_start($_GET['page'], $offset);
    $orderby = array('posttime' => 'DESC');
    list($total, $list) = $RP->find($select, $where, $orderby, $start, $offset);

    if($total) {
        $onclick = "get_respond('$rid',{PAGE})";
        $multipage = multi($total, $offset, $_GET['page'], 'javascript:'.$onclick, '', '');
    }

    include template('review_ajax_get_respond');
    output();

    break;
}
?>