<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$AL =& $_G['loader']->model('adv:list');

switch($op) {
    case 'delete':
        $AL->delete(_post('adids'));
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'add':
        $admin->tplname = cptpl('save', MOD_FLAG);
        break;
	case 'edit':
		$adid = _get('adid',null,'intval');
		if(!$detail = $AL->read($adid)) redirect('adv_empty');
        $admin->tplname = cptpl('save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$adid = _post('adid',null,'intval')) redirect(lang('global_sql_keyid_invalid','adid'));
        } else {
            $adid = null;
        }
        $post = $AL->get_post($_POST['ad']);
        $AL->save($post, $adid);
        redirect('global_op_succeed', cpurl($module,$act));
        break;
	case 'update':
		$AL->update(_post('adv'));
		redirect('global_op_succeed',get_forward(cpurl($module, $act)));
		break;
    default:
		$sorts = lang('adv_sort');
        $op = 'list';
		$_GET['enabled'] = _get('enabled','Y',MF_TEXT);
        $where = array();
        if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        $where['al.enabled'] = $_GET['enabled'];
        $offset = 20;
        $start = get_start($_GET['page'],$offset);
		$select = 'adid,city_id,al.apid,al.adname,al.begintime,al.endtime,al.sort,al.listorder,al.enabled,ap.name';
        list($total, $list) = $AL->get_list($select, $where, 'listorder', $start, $offset);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', $_GET));
        }
        $admin->tplname = cptpl('list', MOD_FLAG);
}
?>