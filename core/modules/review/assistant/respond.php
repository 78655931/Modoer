<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$R =& $_G['loader']->model(MOD_FLAG.':respond');

switch($op) {
    case 'delete':
        $R->delete($_POST['respondids']);
        if(defined('IN_AJAX')) {
            echo 'OK';
            exit;
        } else {
            redirect('global_op_succeed', get_forward(url('item/member/ac/'.$ac)));
        }
        break;
    case 'add':
        if(!$rid = (int)$_GET['rid']) redirect(lang('global_sql_keyid_invalid', 'rid'));
        $RW =& $_G['loader']->model(':review');
        if(!$review = $RW->read($rid)) {
            redirect(lang('review_empty'));
        }
        $tplname = 'respond_save';
        break;
    case 'edit':
        if(!$respondid = (int)$_GET['respondid']) {
            redirect(lang('global_sql_keyid_invalid', 'respondid'));
        }
        if(!$detail = $R->read($respondid)) {
            redirect('review_respond_empty');
        }
        $RW =& $_G['loader']->model(':review');
        if(!$review = $RW->read($detail['rid'])) {
            redirect(lang('review_empty'));
        }
        if($user->uid != $detail['uid']) {
            redirect('global_op_access');
        }
        $tplname = 'respond_save';
        break;
	case 'save':
        if($_POST['do'] == 'edit') {
            if(!$respondid = (int)$_POST['respondid']) {
                redirect(lang('global_sql_keyid_invalid', 'respondid'));
            }
        } else  {
            $respondid = NULL;
        }
        $post = $R->get_post($_POST);
        $R->save($post, $respondid);
        redirect(RETURN_EVENT_ID, get_forward(url('item/member/ac/m_respond'),1)); //($_POST['forward']));
        break;
    default:
		$select = 'rp.*';
        $where = array();
        $where['rp.uid'] = $user->uid;
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $R->find($select, $where, array('rp.posttime'=>'DESC'), $start, $offset, 1, 1);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/page/_PAGE_"));

		$tplname = 'respond_list';
}
?>