<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$AL =& $_G['loader']->model(MOD_FLAG.':att_list');
$op = _input('op');

switch($op) {
case 'update':
    $AL->update($_POST['att_list']);
    $AL->write_cache(_post('catid'));
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
case 'save':
    $catid = _post('catid',null,'intval');
    $names = _post('names',null,'_T');
    $AL->save($catid, $names);
    redirect('global_op_succeed', cpurl($module,$act,'',array('catid'=>$catid)));
    break;
case 'delete':
    $AL->delete($_POST['attids']);
    $AL->write_cache(_post('catid'));
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    if(!$catid = abs((int) $_GET['catid'])) redirect(lang('global_sql_keyid_invalid','catid'));
    $AT = & $_G['loader']->model('item:att_cat');
    if(!$cat = $AT->read($catid)) redirect('item_att_cat_empty');
    list(,$list) = $AL->find('*',array('catid'=>$catid,'type'=>'att'),'listorder',0,0,false);
    $admin->tplname = cptpl('att_list', MOD_FLAG);
    break;
}
?>