<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$T =& $_G['loader']->model(MOD_FLAG.':taggroup');

switch($_GET['op']) {
case 'add':
    $admin->tplname = cptpl('taggroup', MOD_FLAG);
    break;
case 'edit':
    $tgid = (int) $_GET['tgid'];
    if(!$detail = $T->read($tgid)) redirect('global_op_empty');
    $admin->tplname = cptpl('taggroup', MOD_FLAG);
    break;
case 'save':
    $T->save($_POST['taggroup'], $_POST['tgid']);
    redirect('global_op_succeed', cpurl($module,$act));
    break;
case 'delete':
    $tgid = (int) $_GET['tgid'];
    $T->delete($tgid);
    redirect('global_op_succeed', cpurl($module,$act));
    break;
default:
    list(,$list) = $T->read_all();
    $admin->tplname = cptpl('taggroup', MOD_FLAG);
    break;
}
?>