<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model(MOD_FLAG.':category');

if(!$_POST['dosubmit']) {

    $catlist = $C->getlist(0);
    $admin->tplname = cptpl('category_list', MOD_FLAG);

} else {

    $C->update($_POST['category']);
    redirect('global_op_succeed', cpurl($module,$act));

}
?>