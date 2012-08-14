<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$S =& $_G['loader']->model(MOD_FLAG.':subject');

if(!$_POST['dosubmit']) {

    $_G['loader']->helper('form', MOD_FLAG);
    if($pid = (int) $_GET['pid']) {
        $IB =& $_G['loader']->model('item:itembase');
        $model = $IB->get_model($pid, TRUE);
        define("ITEM_PID", $pid);
        $field_form = $S->create_form($pid);
    }
    $admin->tplname = cptpl('subject_save', MOD_FLAG);

} else {

    $post = $_POST['t_item'];
    $S->save($post);

	if($_POST['t_item']['status'] != '1') {
		$url = cpurl($module,'subject_check','', array('pid' => $_POST['pid']));
	} else {
		$url = cpurl($module,'subject_list','', array('pid' => $_POST['pid']));
	}
    redirect('global_op_succeed', $url);
}

?>