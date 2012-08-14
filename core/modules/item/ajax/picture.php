<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'get', 'page' );
$login_ops = array( );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}

switch($op) {

case 'get':

    if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
    $P =& $_G['loader']->model(MOD_FLAG.':picture');

    $select = 'picid,albumid,uid,username,title,thumb,filename';
    $where = array('sid' => $sid,'status'=>1);
    $orderby = array('addtime' => 'DESC');
    $start = get_start($_GET['page'], $offset = 6);
    list($total,$list) = $P->find($select, $where, $orderby, $start, $offset, TRUE);
    $root = url('modoer','',1);
    if($list) {
        echo '<table id="roll" class="roll">';
        echo "\t<tbody>\t";
        echo "\t<tr>\t";
        if($_GET['page'] > 1) {
            echo "\t<td class=\"arrow\"><img src=\"{$root}templates/main/default/img/pic_left.gif\" onclick=\"get_pictures($sid,".($_GET['page']-1).");\" /></td>";
        }
        $i = 0;
        while($val = $list->fetch_array()) {
            $i++;
            $url = url('modoer','',1) . $val['filename'];
            $thumb = url('modoer','',1) . $val['thumb'];
            echo "\t<td class=\"pic\"><a href=\"".url("item/album/id/$val[albumid]/picid/$val[picid]")."\" target=\"_blank\" title=\"$val[title]\"><img onmouseover=\"tip_start(this);\" src=\"$thumb\" /></a></td>";
        }
        if($i == $offset && $total > $start + $offset) {
            echo "\t<td class=\"arrow\"><img src=\"{$root}templates/main/default/img/pic_right.gif\" onclick=\"get_pictures($sid,".($_GET['page']+1).");\" /></td>";
        }
        echo "\t</tr>\t";
        echo "\t</tbody>\t";
        echo '</table>';
    }
    output();

case 'page':

    if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
    if(!$modelid = (int)$_POST['modelid']) redirect(lang('global_sql_keyid_invalid', 'modelid'));

    $P =& $_G['loader']->model(MOD_FLAG.':picture');
    $select = 'pid,uid,username,title,thumb,filename,addtime,comments';
    $where = array('sid'=>(int)$sid, 'modelid'=>(int)$modelid);
    $orderby = array('addtime' => 'DESC');
    $start = get_start($_GET['page'], $offset = 1);
    list($total, $list) = $P->find($select, $where, $orderby, $start, $offset, 1);
    $pic = $list->fetch_array();

    $multipage = multi($total, $offset, $_GET['page'], "javascript:picture_page($sid,$modelid,{PAGE});");

    include template('item_pic');
    output();
    break;

default:

    redirect('global_op_unkown');

}
?>