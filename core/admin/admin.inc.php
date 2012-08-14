<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$A =& $_G['loader']->model('admin');

switch($op) {
case 'add':
    if(!$admin->is_founder) redirect('global_op_access');
    $admin->tplname = cptpl('admin_save');
    break;
case 'edit':
    $_GET['adminid'] = (int) $_GET['adminid'];
    if(!$_GET['adminid']) redirect(lang('global_sql_keyid_invalid', 'adminid'));
    if(!$admin->is_founder && $admin->id != $_GET['adminid']) redirect('global_op_access');
    if(!$detail = $A->read($_GET['adminid'])) redirect('global_op_empty');
    $mymodules = explode(',',$detail['mymodules']);
    if(!is_array($mymodules)) $mymodules = array(); 
    $mycitys = explode(',',$detail['mycitys']);
    if(!is_array($mycitys)) $mycitys = array(); 
    $admin->tplname = cptpl('admin_save');
    break;
case 'post':
    if($_POST['do'] == 'edit') {
        if(!$adminid = (int) $_POST['adminid']) redirect(lang('global_sql_keyid_invalid', 'adminid'));
        if(!$admin->is_founder && $admin->id != $adminid) redirect('global_op_access');
        if($_POST['admin']['password'] != $_POST['password2']) redirect('admincp_admin_unequal_pw');
        if($_POST['admin']['password']) {
            $_POST['admin']['password'] = md5($_POST['admin']['password']);
        } else {
            unset($_POST['admin']['password']);
        }
    } else {
        if(!$admin->is_founder) redirect('global_op_access');
        $adminid = null;
        $_POST['admin']['password'] = md5($_POST['admin']['password']);
    }
    $A->save($_POST['admin'], $adminid);
    redirect('global_op_succeed', cpurl($module,$act));
    break;
case 'delete':
    if(!$admin->is_founder) redirect('global_op_access');
    if(empty($_POST['adminids']) || !is_array($_POST['adminids'])) redirect('global_op_unselect');
    $A->delete($_POST['adminids']);
    redirect('global_op_succeed_delete',cpurl($module,$act));
    break;
default:
    if(!$admin->is_founder) {
        header("Location:"  . cpurl($module,$act,'edit',array('adminid'=>$admin->id)));
        exit;
    }
    $op = 'list';
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $A->find(0, $start, $offset);
    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, $op));
    $admin->tplname = cptpl('admin_list');
}
?>