<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$P =& $_G['loader']->model(':product');
$op = _input('op',null,'_T');
$_G['loader']->helper('form','product');

switch($op) {
case 'create_category':
	$C =& $_G['loader']->model('product:category');
    $catid = $C->create((int)$_POST['sid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo $catid;
        output();
    }
    break;
case 'rename_category':
    $C =& $_G['loader']->model('product:category');
    $catid = $C->rename((int)$_POST['catid'], trim($_POST['catname']));
    if(defined('IN_AJAX')) {
        echo 'OK';
        output();
    }
    break;
case 'delete_category':
    $C =& $_G['loader']->model('product:category');
    $catid = _input('catid', null, MF_INT_KEY);
    $C->delete($catid);
    if(defined('IN_AJAX')) {
        echo 'OK';
        output();
    }
    break;
case 'add':
	if($sid=_get('sid',null,'intval')) {
		$S=&$_G['loader']->model('item:subject');
		if(!$subject=$S->read($sid)) redirect('item_empty');
		if(!$modelid = $P->get_modelid($subject['pid'])) redirect('product_model_empty');
		$custom_form = $P->create_from($modelid);
	}
	$admin->tplname = cptpl('product_save', MOD_FLAG);
	break;
case 'edit':
    if(!$detail = $P->read($_GET['pid'])) redirect('product_empty');
    $sid = $detail['sid'];
    $catid = $detail['catid'];
    $custom_form = $P->create_from($detail['modelid'], $detail);
	$S=&$_G['loader']->model('item:subject');
	if(!$subject=$S->read($sid)) redirect('item_empty');
    $admin->tplname = cptpl('product_save', MOD_FLAG);
    break;
case 'save':
	if(_post('do')=='edit') {
		if(!$pid = $_POST['pid']) redirect(lang('global_sql_keyid_invalid','pid'));
	} else {
		$pid = null;
	}
    $post = $P->get_post($_POST);
    $post['field_data'] = $_POST['t_item'];
    $pid = $P->save($post, $pid);
	$navs = array(
		array('name'=>'global_redirect_return','url'=>get_forward(cpurl($module,$act,'list'),1)),
		array('name'=>'product_redirect_productlist','url'=>cpurl($module,$act,'list')),
		array('name'=>'product_redirect_newproduct','url'=>cpurl($module,$act,'add',array('sid'=>$post['sid']))),
		array('name'=>'product_redirect_subjectproduct','url'=>cpurl($module,$act,'add')),
	);
	redirect('global_op_succeed',$navs);
    break;
case 'update':break;
case 'delete':
    $P->delete($_POST['pids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checkup':
    $P->checkup($_POST['pids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checklist':
    $where = array();
    if(!$admin->is_founder) $where['s.city_id'] = $_CITY['aid'];
    $where['p.status'] = 0;
    list($total,$list) = $P->find('p.pid,p.sid,p.subject,p.description,p.thumb,p.status,p.dateline',$where,array('p.dateline'=>'DESC'),$start,$offset,TRUE,'s.name,s.subname');
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
    }
    $admin->tplname = cptpl('product_check', MOD_FLAG);
    break;
default:
    $op = 'list';
    //if($_GET['dosubmit']) {
        if(!$admin->is_founder) $P->db->where('s.city_id',$_CITY['aid']);
        $_GET['modelid'] > 0 && $P->db->where('p.modelid',$_GET['modelid']);
        $_GET['sid'] && $P->db->where('p.sid',$_GET['sid']);
        $_GET['subject'] && $P->db->where('p.subject',trim($_GET['subject']));
        $_GET['username'] && $P->db->where('p.username',trim($_GET['username']));
        $_GET['starttime'] && $P->db->where_more('dateline', strtotime($_GET['starttime']));
        $_GET['endtime'] && $P->db->where_less('dateline', strtotime($_GET['endtime']));
        $P->db->where('p.status',1);
        $P->db->join($P->table, 'p.sid', 'dbpre_subject', 's.sid');
        if($total = $P->db->count()) {
            $P->db->select('p.pid,p.sid,p.catid,p.username,p.uid,p.subject,p.pageview,p.comments,p.dateline,p.status,p.thumb,p.description,s.name,s.subname');
            $P->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'pid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $P->db->order_by('p.'.$_GET['orderby'], $_GET['ordersc']);
            $P->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $list = $P->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
        if($_GET['sid']) {
            $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $_GET['sid'], true);
        }
    //}
    $_G['loader']->helper('form',MOD_FLAG);
    $admin->tplname = cptpl('product_list', MOD_FLAG);
}
?>