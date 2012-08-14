<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$op = _input('op');
$forward = get_forward(cpurl($module, $act,'',array('pid' => $_GET['pid'])));

switch ($op) {
    case 'delete':
        $I->delete($_POST['sids'], $_POST['delete_point']);
        redirect('global_op_succeed', $forward);
        break;
    case 'rebuild':
        $I->rebuild($_POST['sids']);
        redirect('global_op_succeed', $forward);
        break;
    case 'update':
        $I->update($_POST['subjects']);
        redirect('global_op_succeed', $forward);
        break;
    case 'move':
        $I->move($_POST['sids'], abs((int)$_POST['moveto_catid']));
        redirect('global_op_succeed', $forward);
        break;
	case 'forums':
		$_G['loader']->helper('modcenter');
		if($forums = modcenter::get_forums()) {
			foreach($forums as $fid => $name) {
				echo '<option value="'.$fid.'">'.$name.'</option>';
			}
		}
		exit;
		break;
    default:
        $pid = (int) $_GET['pid'];
        $I->db->from($I->table);
        $pid && $I->db->where('pid', $pid);
        if($_GET['catid']) $I->db->where('catid', $_GET['catid']);
        if(!$admin->is_founder) {
			$_GET['city_id'] = '';
			$I->db->where('city_id',$_CITY['aid']);
		} elseif($_GET['city_id']) {
			$I->db->where('city_id',$_GET['city_id']);
		}
        if($_GET['aid']) {
			$AREA =& $_G['loader']->model('area');
			$aids = $AREA->get_sub_aids($_GET['aid']);
			if($aids) $I->db->where('aid', $aids);
		}
        if($_GET['keyword']) $I->db->where_like('name', '%'._T($_GET['keyword']).'%');
        if($_GET['creator']) $I->db->where('creator', $_GET['creator']);
        if($_GET['owner']) $I->db->where('owner', $_GET['owner']);
        if($_GET['starttime']) $R->db->where_more('addtime', strtotime($_GET['starttime']));
        if($_GET['endtime']) $R->db->where_less('addtime', strtotime($_GET['endtime']));
        $I->db->where('status', 1);
        $total = $I->db->count();
        if($total) {
            $I->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'sid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $I->db->order_by($_GET['orderby'], $_GET['ordersc']);
            $I->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $list = $I->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
		$_G['loader']->helper('form', $I->model_flag);
        $admin->tplname = cptpl('subject_list', MOD_FLAG);
}

function p_order($order) {
    if($_GET['order'] == $order) {
        if($_GET['by'] == 'ASC') return '¡ü';
        if($_GET['by'] == 'DESC') return '¡ý';
    }
}
?>