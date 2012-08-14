<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$P =& $_G['loader']->model(':product');

$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('form','item');

switch($op) {
    case 'add':
        if($sid = (int) $_GET['sid']) {
            $S =& $_G['loader']->model('item:subject');
            if(!$subject = $S->read($sid,'sid,pid,catid,name,subname,status')) redirect('item_empty');
            if(!$modelid = $P->get_modelid($subject['pid'])) redirect('product_model_empty');
            $custom_form = $P->create_from($modelid);
        }
        $tplname = 'product_save';
        break;
    case 'edit':
        $pid = (int) $_GET['pid'];
        if(!$detail = $P->read($pid)) redirect('product_empty');
        $sid = $detail['sid'];
        $catid = $detail['catid'];
        $custom_form = $P->create_from($detail['modelid'], $detail);
        $tplname = 'product_save';
        break;
    case 'delete':
        $P->delete($_POST['pids']);
        $forward = get_forward();
        redirect('global_op_succeed_delete', $forward);
    case 'post':
        $pid = $_POST['do'] == 'edit' ? (int)$_POST['pid'] : null;
        $post = $P->get_post($_POST);
        $post['field_data'] = $_POST['t_item'];
        $pid = $P->save($post, $pid);
        redirect(RETURN_EVENT_ID, url('product/member/ac/g_product/sid/'.$post['sid']));
        break;
    default:
        $op = 'list';
        $sid = (int) $_GET['sid'];
        $S =& $_G['loader']->model('item:subject');
        if(!$subjects = $S->mysubject($user->uid)) redirect('product_mysubject_empty');
        if($sid && !in_array($sid, $subjects)) redirect('product_mysubject_nonentity');
        $catid = (int) $_GET['catid'];

        $where = array();
        $where['p.sid'] = $sid ? $sid : $subjects;
        $catid && $where['p.catid'] = $catid;
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $P->find('p.*', $where, array('p.dateline'=>'DESC'), $start, $offset, TRUE, 's.name,s.subname');
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], url("product/member/ac/g_product/sid/$sid/catid/$catid/page/_PAGE_"));
        }
        $tplname = 'product_list';
}
?>