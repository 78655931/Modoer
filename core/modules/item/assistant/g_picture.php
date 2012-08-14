<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');


$A =& $_G['loader']->model(MOD_FLAG.':album');
$P =& $_G['loader']->model(MOD_FLAG.':picture');

$op = _input('op');
switch($op) {
	case 'setthumb':
		if(!$picid = abs ( _post( 'picid', null, 'intval' ) ) ) redirect( lang( 'global_sql_keyid_invalid', 'picid' ) );
		$A->set_thumb($picid);
		redirect('global_op_succeed', get_forward(url("item/member/ac/g_album")));
		break;
	case 'delete':
		$P->delete($_POST['picids']);
		redirect('global_op_succeed_delete', get_forward(url('item/member/ac/'.$ac.'/pid/'.$_GET['fw_pid'])));
		break;
	case 'update':
		$P->update($_POST['picture']);
		redirect('global_op_succeed', get_forward(url('item/member/ac/'.$ac.'/pid/'.$_GET['fw_pid'])));
		break;
	default:
		if(!$albumid = _get('albumid',0,'intval')) redirect(lang('global_sql_keyid_invalid','albumid'));
		if(!$album = $A->read($albumid)) redirect('item_album_empty');

		$pid = $_G['manage_subject']['pid'];
		$category = $P->variable('category');
		$modelid = $category[$pid]['modelid'];
		$model = $P->variable('model_' . $modelid);
        $fields = $P->variable('field_' . $modelid);

        $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
        foreach ($fields as $value) {
            if($var = $varname[$value['fieldname']]) {
                $$var = $value['title'];  
            }
        }

        $S =& $_G['loader']->model('item:subject');
        $mysubjects = $S->mysubject($user->uid);

        $where['sid'] = (int) $_G['manage_subject']['sid'];
        $where['albumid'] = $albumid;
        $select = 'p.*';

        $start = get_start($_GET['page'], $offset = 10);
        list($total, $list) = $P->find($select, $where, array('addtime'=>'DESC'), $start, $offset);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/g_picture/ac/$ac/pid/$pid/albumid/$albumid/page/_PAGE_"));

        $path_title = lang('item_title_g_picture');
        $tplname = 'pic_list_g';
}
?>