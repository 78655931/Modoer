<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$A =& $_G['loader']->model(MOD_FLAG.':album');
switch($op) {
	case 'add':
		$tplname = 'album_save';
		break;
	case 'edit':
		$albumid = _get('albumid',null,'intval');
		if(!$detail = $A->read($albumid)) redirect('global_album_empty');
		$tplname = 'album_save';
		break;
	case 'save':
		if(_post('do')=='edit') {
			if(!$albumid = abs(_post('albumid',0,'intval'))) redirect(lang('global_sql_keyid_invalid','albumid'));
		} else {
			$albumid = null;
		}
		$post = $A->get_post($_POST);
		$A->save($post, $albumid);
		redirect('global_op_succeed',get_forward(url("item/member/ac/$ac"),1));
		break;
	case 'delete':
		$A->delete(_input('albumid'));
		redirect('global_op_succeed_delete',url("item/member/ac/$ac"));
		break;
	default:
		$where = array();
		$where['sid'] = $_G['manage_subject']['sid'];
		$offset = 20;
		$start = get_start($_GET['page'],$offset);
		list($total, $list)=$A->find('*',$where,'lastupdate DESC',$start,$offset);
		if($total) $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/page/_PAGE_"));
		$tplname = 'album_list';
}
?>