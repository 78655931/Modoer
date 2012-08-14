<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model(MOD_FLAG.':model');
$op = $_GET['op'] ? $_GET['op'] : 'list';

switch($op) {
    case 'delete':
        $_GET['modelid'] = (int) $_GET['modelid'];
        $M->delete($_GET['modelid']);
        redirect('global_op_succeed', cpurl($module,$act,'list'));
        break;
    default:
        $result = $M->model_list();
        $admin->tplname = cptpl('model_list', MOD_FLAG);
}
//redirect('global_op_unkown');
?>