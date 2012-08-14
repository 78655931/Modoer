<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$GT =& $_G['loader']->model(MOD_FLAG.':gift');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form','exchange');
$_G['loader']->helper('form','member');
$usergroup =& $_G['loader']->variable('usergroup','member');
switch($op) {
case 'add':
    $admin->tplname = cptpl('gift_save', MOD_FLAG);
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('description');
    $editor->item = 'admin';
    $edit_html = $editor->create_html();
	$sort = _input('sort',1,MF_INT);
    break;
case 'edit':
    $giftid = $_GET['giftid'] = (int) $_GET['giftid'];
    if(!$detail = $GT->read($_GET['giftid'])) redirect('exchange_gift_empty');
    if($detail['sid']>0) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($detail['sid'],'*',false);
    }
	$sort = $detail['sort'];
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('description');
    $editor->item = 'admin';
    $editor->content = $detail['description'];
    $edit_html = $editor->create_html();
    $admin->tplname = cptpl('gift_save', MOD_FLAG);
    break;
case 'save':
    if($_POST['do'] == 'edit') {
        if(!$giftid = (int) $_POST['giftid']) redirect(lang('global_sql_keyid_invalid','giftid'));
    } else {
        $giftid = null;
    }
    $post = $GT->get_post($_POST);
    is_array($post['usergroup'])?$post['usergroup'] = ','.implode(',',$post['usergroup']).',':$post['usergroup'] = '';
    $GT->save($post, $giftid);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
    break;
case 'delete':
    $GT->delete($_POST['giftids']);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
    break;
case 'update':
    $GT->update($_POST['gifts']);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
    break;
default:
    $op = 'list';
    $where = array();
    if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
    if($_GET['city_id']) $where['city_id'] = $_GET['city_id'];
    if($_GET['catid']) $where['catid'] = $_GET['catid'];
    if($_GET['sid']) $where['sid'] = $_GET['sid'];
    if($_GET['name']) $where['name'] = array('where_like',array("%$_GET[name]%"));
    if($_GET['pattern']) $where['pattern'] = $_GET['pattern'];
    $ordersc = $_GET['ordersc']?$_GET['ordersc']:'DESC';
    $orderby = $_GET['orderby']?$_GET['orderby']:'giftid';
    $offset = $_GET['offset']?$_GET['offset']:20;
    $start = get_start($_GET['page'], $offset);
	$select = 'giftid,sid,city_id,name,sort,pattern,available,displayorder,price,point,point3,point4,pointtype,pointtype2,pointtype,pointtype2,pointtype3,pointtype4,num,salevolume';
    list($total,$list) = $GT->find($select, $where, array($orderby=>$ordersc), $start, $offset, true);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'list'));
    }
    $admin->tplname = cptpl('gift_list', MOD_FLAG);
}
?>