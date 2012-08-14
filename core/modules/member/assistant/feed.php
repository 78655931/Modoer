<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
* @version $Id 3 2008-08-25 18:35 $
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$offset = 10;

switch ($op) {
    case 'user':
        $start = get_start($_GET['page'], $offset);
        $_G['db']->from('dbpre_member_feed', 'feed');
        $_G['db']->where_exist("SELECT 1 FROM dbpre_favorites f WHERE feed.uid=f.id AND idtype='member' AND f.uid=$user->uid");
        $_G['db']->order_by('dateline','DESC');
        $_G['db']->limit($start, $offset);
        $list = $_G['db']->get();
        if($list) {
            $multipage = multi_w($list->num_rows(), $offset, $_GET['page'], url("member/index/ac/feed/op/user/page/_PAGE_"));
        }
        break;
    case 'item':
        $start = get_start($_GET['page'], $offset);
        $_G['db']->from('dbpre_member_feed', 'feed');
        $_G['db']->where_exist("SELECT 1 FROM dbpre_favorites f WHERE feed.sid=f.id AND f.idtype='subject' AND f.uid=$user->uid");
        $_G['db']->order_by('dateline','DESC');
        $_G['db']->limit($start, $offset);
        $list = $_G['db']->get();
        if($list) {
            $multipage = multi_w($list->num_rows(), $offset, $_GET['page'], url("member/index/ac/feed/op/item/page/_PAGE_"));
        }
        break;
    default:
        # code...
        break;
}
?>