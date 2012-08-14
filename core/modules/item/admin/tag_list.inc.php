<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$TAG =& $_G['loader']->model(MOD_FLAG.':tag');

switch($op) {
case 'edit':
    if($_POST['dosubmit']) {
        $tagid = (int) $_POST['tagid'];
        $TAG->edit($_POST['tagname'], $tagid, $_POST['merge']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
    } else {
        $tagid = (int) $_GET['tagid'];
        if(!$detail = $TAG->read($tagid)) {
            redirect('item_tag_empty_tagid');
        }
        $admin->tplname = cptpl('tag_save', MOD_FLAG);
    }
    break;
case 'close':
    $TAG->close($_POST['tagids'], (int)$_POST['closed']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
case 'delete':
    $TAG->delete_tagids($_POST['tagids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    $where = null;
    if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
    $orderby = array('total'=>'DESC');
    $start = get_start($_GET['page'], $offset = 20);
    list($total, $list) = $TAG->find($where, $orderby, $start, $offset, TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('disposal' => $_GET['disposal'])));
    }
    $admin->tplname = cptpl('tag_list', MOD_FLAG);
}
?>