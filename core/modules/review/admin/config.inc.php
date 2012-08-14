<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');

if($_POST['dosubmit']) {

    if($_POST['modcfg']['index_review_pids']) {
        $_POST['modcfg']['index_review_pids'] = implode(',',$_POST['modcfg']['index_review_pids']);
    }
    if($_POST['modcfg']['index_top_pids']) {
        $_POST['modcfg']['index_top_pids'] = implode(',',$_POST['modcfg']['index_top_pids']);
    }
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {
    
    $_G['loader']->helper('form','item');
    $_G['loader']->helper('form','member');
    $modcfg = $C->read_all(MOD_FLAG);
    $modcfg['index_review_pids'] = $modcfg['index_review_pids'] ? explode(',',$modcfg['index_review_pids']): array();
    $modcfg['index_top_pids'] = $modcfg['index_top_pids'] ? explode(',',$modcfg['index_top_pids']): array();
    $admin->tplname = cptpl('config', MOD_FLAG);
}
?>