<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$AP =& $_G['loader']->model('adv:place');

switch($op) {
    case 'code':
		$apid = _get('apid',null,'intval');
		if(!$detail = $AP->read($apid)) redirect('adv_place_empty');
        $admin->tplname = cptpl('place_code', MOD_FLAG);
        break;
    case 'delete':
        $AP->delete(_post('apids'));
        redirect('global_op_succeed_delete', get_forward(cpurl($module, $act)));
        break;
    case 'add':
        $admin->tplname = cptpl('place_save', MOD_FLAG);
        break;
    case 'edit':
		$apid = _get('apid',null,'intval');
		if(!$detail = $AP->read($apid)) redirect('adv_place_empty');
        $admin->tplname = cptpl('place_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$apid = _input('apid',null,'intval')) redirect(lang('global_sql_keyid_invalid','apid'));
        } else {
            $apid = null;
        }
        $post = $AP->get_post($_POST);
        $AP->save($post, $apid);
        redirect('global_op_succeed', get_forward(cpurl($module, $act),1));
        break;
    default:
        $op = 'list';
		$_GET['enabled'] = _get('enabled','Y','_T');
        $where = array('enabled'=>$_GET['enabled']);
        $offset = 20;
        $start = get_start($_GET['page'], $offset);
        list($total, $list) = $AP->find('apid,name,des', $where, 'apid', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
        }
        $admin->tplname = cptpl('place_list', MOD_FLAG);
}
?>