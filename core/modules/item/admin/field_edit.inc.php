<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$F =& $_G['loader']->model(MOD_FLAG.':field');
$op = empty($_GET['op']) ? 'edit' : $_GET['op'];

switch($op) {
case 'disable':
case 'enable':
    $F->disable((int)$_GET['fieldid'], $op == 'disable' ? 1 : 0);
    redirect('global_op_succeed', cpurl($module,'field_list', '', array('modelid' => (int)$_GET['modelid'])));
    break;
case 'delete':
    $F->delete((int)$_GET['fieldid']);
    redirect('global_op_succeed', cpurl($module,'field_list', '', array('modelid' => (int)$_GET['modelid'])));
    break;
case 'add':
default:
    $isedit = $op == 'edit';
    $op = $isedit ? 'edit' : 'add';
    if(!$_POST['dosubmit']) {
        if($isedit) {
            $_GET['fieldid'] = (int) $_GET['fieldid'];
            $t_field = $F->read($_GET['fieldid']);
            $disabled = ' disabled="disabled"';
            $modelid = $t_field['modelid'];
        } else {
            $modelid = $_GET['modelid'];
            $M =& $_G['loader']->model('item:model');
            $t_model = $M->read($modelid);
        }
        $admin->tplname = cptpl('fielde_save', MOD_FLAG);
    } else {
        $_POST['t_field']['config'] = $_POST['t_cfg'];
        unset($_POST['t_cfg']);
        if(empty($_POST['t_field']['title'])) {
            redirect('admincp_field_post_title_empty');
        }
        if($_POST['isedit']) {
            if(empty($_POST['fieldid'])) redirect('admincp_field_post_type_empty');
            $F->edit($_POST['fieldid'],$_POST['t_field']);
        } else {
            $_POST['t_field']['modelid'] = (int) $_POST['modelid'];
            if(empty($_POST['t_field']['fieldname'])) redirect('admincp_field_post_name_empty');
            //в╥╪свж╤нг╟в╨
            $_POST['t_field']['fieldname'] = 'c_' . $_POST['t_field']['fieldname'];
            $F->add($_POST['t_field']);
        }
        redirect('global_op_succeed', cpurl($module, 'field_list', '', array('modelid' => $_POST['modelid'])));
    }
}

?>