<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model('item:model');
$op = _input('op', 'list', MF_TEXT);

switch($op) {
    case 'export':
        $modelid = _get('modelid',null,MF_INT_KEY);
        $M->export($modelid);
        break;
    case 'delete':
        $_GET['modelid'] = _get('modelid',null,MF_INT_KEY);
        $M->delete($_GET['modelid']);
        redirect('global_op_succeed', cpurl($module,$act,'list'));
        break;
    default:
        $result = $M->model_list();
        $admin->tplname = cptpl('model_list', MOD_FLAG);
}
//redirect('global_op_unkown');
?>