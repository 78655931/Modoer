<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$R =& $_G['loader']->model(MOD_FLAG.':opt');

if(!$_POST['dosubmit']) {
    
    $gid = $_GET['gid'] = (int) $_GET['gid'];
    $result = $R->getlist($gid);
    empty($result) && $result = array();
    $admin->tplname = cptpl('opt', MOD_FLAG);

} else {

    $gid = $_POST['gid'] = (int) $_POST['gid'];
    $R->save($_POST['t_pot'], $gid);

    redirect('global_op_succeed', cpurl($module,$act,'',array('gid'=>$gid)));
}
?>