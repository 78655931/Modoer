<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$R =& $_G['loader']->model(':review');

switch($op) {

	case 'delete':

		$rid = (int) $_GET['rid'];
		$modelid = (int) $_GET['modelid'];
        $R->delete($rid);
		redirect('global_op_succeed', get_forward(url('review/member/list/ac/'._T($_GET['fw_ac']).'/pid/'._T($_GET['fw_pid']))));
		break;

	default:

		if($_POST['dosubmit']) {

			if(!$_POST['rid'] = (int) $_POST['rid']) {
				redirect(lang('global_sql_keyid_invalid', 'rid'));
			}
			$post = $R->get_post($_POST['review']);
			$rid = $R->save($post, $_POST['rid']);

			redirect(global_op_succeed, get_forward(url('review/member/ac/list'),1));

		} else {

			$rid = (int) _get('rid');
			if(!$detail = $R->read($rid, '*', False)) redirect('global_op_empty');

            if($user->uid != $detail['uid']) {
                redirect('global_op_access');
            }

            $idtype = $detail['idtype'];
            $id = $detail['id'];
            $typeinfo = $R->get_type($detail['idtype']);
            $OBJ =& $_G['loader']->model($typeinfo['model_name']);

            $object = $OBJ->read($detail['id']);
            $subject = $OBJ->get_subject($object);

			$pid = $OBJ->get_obj_pid($object);
			$config = $OBJ->get_review_config($object);
			$rogid = $config['review_opt_gid'];

            //判断是否允许游客点评
            if(!$user->isLogin && !$config['guest_review']) {
                if(defined('IN_AJAX')) {
                    $forward = base64_encode($_G['web']['referer']);
                    dialog(lang('global_op_title'), '', 'login');
                } else {
                    $forward = $_G['web']['reuri'] ? ($_G['web']['url'] . $_G['web']['reuri']) : get_url('modoer','','','',1);
                    location(url('memner/login/forward/'.base64_encode($forward)));
                    exit;
                }
            }

			$review_opts = $R->variable('opt_' . $rogid);
            $taggroups = $OBJ->variable('taggroup');

			$_G['loader']->helper('form');
			$tplname = 'review_save';

		}
}
?>