<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$TP =& $_G['loader']->model('discussion:topic');
$RP =& $_G['loader']->model('discussion:reply');
$op = _input('op');

switch ($op) {
	case 'edit':
		$rpid = _get('rpid', null, MF_INT_KEY);
		$detail = $RP->read($rpid);
		if(empty($detail)) redirect('discussion_reply_empty');
		$admin->tplname = cptpl('reply_save', MOD_FLAG);
		break;
	case 'save':
        $rpid = _post('rpid',0,MF_INT_KEY);
        $post = $RP->get_post($_POST);
        $RP->save($post, $rpid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));	
		break;
	case 'delete':
		$RP->delete(_post('rpids',null,MF_INT_KEY));
		redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
		break;
	case 'checklist':
		$where = array();
		$where['rp.status'] = 0;
		$RP->db->join($RP->table,'rp.tpid','dbpre_discussion_topic','tp.tpid');
		$RP->db->where($where);
	    if($total = $RP->db->count()) {
	        $RP->db->sql_roll_back('from,where');
	        !$_GET['orderby'] && $_GET['orderby'] = 'rpid';
	        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
	        $RP->db->order_by($_GET['orderby'], $_GET['ordersc']);
	        $RP->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
	        $RP->db->select('rp.*,tp.subject');
	        $list = $RP->db->get();
	        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'checklist',$_GET));
	    }
	    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act));
		$admin->tplname = cptpl('reply_checklist', MOD_FLAG);
		break;
	case 'checkup':
		$RP->checkup(_post('rpids',null,MF_INT_KEY));
		redirect('global_op_succeed_checkup',cpurl($module,$act,'checklist'));
		break;
	default:
		$tpid = _get('tpid',null,MF_INT_KEY);
		$topic = $TP->read($tpid);
		if(empty($topic)) redirect('discussion_topic_empty');

		$sid = _get('sid', null, MF_INT_KEY);
		$tpid = _get('tpid', null, MF_INT_KEY);
		$uid = _get('uid', null, MF_INT_KEY);
		$username = _get('username', null, MF_TEXT);

		$where = array();
		if($sid) $where['tp.sid'] = $sid;
		if($tpid) $where['rp.tpid'] = $tpid;
		if($uid) $where['rp.uid'] = $uid;
		if($username) $where['rp.username'] = $username;
		$where['rp.status'] = 1;
		$RP->db->join($RP->table,'rp.tpid','dbpre_discussion_topic','tp.tpid');
		$RP->db->where($where);
	    if($total = $RP->db->count()) {
	        $RP->db->sql_roll_back('from,where');
	        !$_GET['orderby'] && $_GET['orderby'] = 'rpid';
	        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
	        $RP->db->order_by($_GET['orderby'], $_GET['ordersc']);
	        $RP->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
	        $RP->db->select('rp.*,tp.subject');
	        $list = $RP->db->get();
	        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
	    }
	    $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
	    $admin->tplname = cptpl('reply_list', MOD_FLAG);
		break;
}
?>