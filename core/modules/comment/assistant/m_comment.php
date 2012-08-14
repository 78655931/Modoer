<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$CM =& $_G['loader']->model(':comment');

if($_POST['dosubmit']) {

    $CM->delete($_POST['cids'],TRUE);
    redirect('global_op_succeed_delete', url('comment/member/ac/m_comment'));

} else {

    $where = array();
    $where['uid'] = $user->uid;
    $offset = 10;
    $start = get_start($_GET['page'],$offet);
    list($total, $list) = $CM->find('cid,idtype,id,title,content,dateline,status',$where,array('dateline'=>'DESC'),$start,$offset,TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], url('comment/member/ac/m_comment/page/_PAGE_'));
    }

    $tplname = 'comment_list';
}
?>