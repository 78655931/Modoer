<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
if($_POST['dosubmit']) {
    $C->save($_POST['modcfg'], MOD_FLAG);
    $LK =& $_G['loader']->model('link:mylink');
    $LK->write_cache();
    redirect('global_op_succeed', cpurl($module, 'config'));
} else {
    $modcfg = $C->read_all(MOD_FLAG);
    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>