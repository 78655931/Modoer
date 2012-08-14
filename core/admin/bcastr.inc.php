<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$B =& $_G['loader']->model('bcastr');
$op = _input('op',null,MF_TEXT);

switch($op) {
case 'add':
    $admin->tplname = cptpl('bcastr_save');
    break;
case 'edit':
    $bcastr_id = (int)$_GET['bcastr_id'];
    if(!$detail = $B->read($bcastr_id)) return lang('admincp_bcastr_empty');
    $admin->tplname = cptpl('bcastr_save');
    break;
case 'save':
    if($_POST['do']=='edit') {
        if(!$bcastr_id = (int)$_POST['bcastr_id']) redirect(lang('global_sql_keyid_invalid','bcastr_id'));
    } else {
        $bcastr_id = null;
    }
    $post = $B->get_post($_POST);
    $B->save($post,$bcastr_id);
    redirect('global_op_succeed', cpurl($module,$act,'list',array('gn'=>$post['groupname'])));
    break;
case 'update':
    $B->update($_POST['bcastr']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
case 'delete':
    $B->delete($_POST['bcastr_ids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    if(!$gn = _T($_GET['gn'])) {
        $groups = $B->group_list();
        $tplname = 'bcastr_group';
    } else {
		$where = array();
		if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
		$where['groupname'] = $gn;
        list(,$list) = $B->find('*',$where,'orders',0,0,false);
        $tplname = 'bcastr_list';
    }
    $admin->tplname = cptpl($tplname);
}
?>