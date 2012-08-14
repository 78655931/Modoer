<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('ITEM_SUBJECT_EDIT', TRUE);
$op = _input('op',null,MF_TEXT);
$I =& $_G['loader']->model(MOD_FLAG.':subject');
$sid = $_GET['sid'] = (int) $_GET['sid'];
$mymenu = 'menu';
switch($op) {

	case 'delete':

		$pid = (int) $_GET['fw_pid'];
		$sid = (int) $_GET['sid'];
		if(!$detail = $I->read($sid, 'sid', FALSE)) redirect('global_op_empty');
		$I->delete($sid, TRUE);
		redirect('global_op_succeed', get_forward('home'));
		break;

	default:

		if($_POST['dosubmit']) {
			if(!$_POST['sid'] = (int) $_POST['sid']) {
				redirect(lang('global_sql_keyid_invalid', 'sid'));
			}
			$I->save($_POST['t_item'], $_POST['sid']);
            redirect('global_op_succeed', get_forward(url('item/detail/sid/'.$_POST['sid']), 1));
		} else {
			if(!$detail = $I->read($sid, '*', TRUE)) redirect('global_op_empty');
			$pid = $detail['pid'];
            $cfg = $I->get_category($pid);

            $access_edit = $cfg['config']['allow_edit_subject'] && $user->check_access('item_allow_edit_subject', $I, false);
            if($access_edit && $detail['owner']) $access_edit = false;
            if(!$access_edit && !$I->is_mysubject($sid, $user->uid)) {
                redirect('global_op_access');
            }
            $I->is_mysubject($sid, $user->uid) && $mymenu = 'mmenu';
			define("ITEM_PID", $pid);
			define("EDIT_SID", $sid);
			$field_form = $I->create_form($pid, $detail, null);
			$tplname = 'subject_save';
		}
}
?>