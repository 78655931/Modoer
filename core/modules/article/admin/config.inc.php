<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$_G['loader']->helper('form', MOD_FLAG);

if($_POST['dosubmit']) {

    $_POST['modcfg']['att_custom']  = preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $_POST['modcfg']['att_custom']);
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);

    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>