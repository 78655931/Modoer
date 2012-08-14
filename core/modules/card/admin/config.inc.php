<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
if($_POST['dosubmit']) {
    if(is_array($_POST['modcfg']['modelids'])) {
        $DC =& $_G['loader']->model(MOD_FLAG.':discount');
        $DC->relevance($_POST['modcfg']['modelids']);
        $_POST['modcfg']['modelids'] = serialize($_POST['modcfg']['modelids']);
    }
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));
} else {
    $_G['loader']->helper('form','member');
    $modcfg = $C->read_all(MOD_FLAG);
    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>