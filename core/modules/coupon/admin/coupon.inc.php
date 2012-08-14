<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$CU =& $_G['loader']->model(':coupon');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form', MOD_FLAG);

switch($op) {
    case 'delete':
        $CU->delete($_POST['couponids']);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'checkup':
        $CU->checkup($_POST['couponids']);
        redirect('global_op_succeed_checkup', cpurl($module,$act,'checklist'));
        break;
    case 'add':
        $admin->tplname = cptpl('coupon_save', MOD_FLAG);
        break;
    case 'edit':
        $couponid = (int) $_GET['couponid'];
        if(!$detail = $CU->read($couponid)) redirect('coupon_empty');
        if($detail['sid']) {
            $S =& $_G['loader']->model('item:subject');
            if(!$subject = $S->read($detail['sid'],'sid,pid,name,subname')) redirect('item_empty');
        }
        $admin->tplname = cptpl('coupon_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$couponid = (int)$_POST['couponid']) redirect(lang('global_sql_keyid_invalid','couponid'));
            $forward = get_forward(cpurl($module,$act,'list'),1);
            if(empty($forward )||strpos($forward,'cpmenu')) $def_url = cpurl($module,$act,'list');
        } else {
            $couponid = null;
            $forward = cpurl($module,$act,'list');
        }
        $post = $CU->get_post($_POST);
        $CU->save($post, $couponid);
        redirect('global_op_succeed', $forward);
        break;
    case 'checklist':
        $where = array('c.status'=>0);
		if(!$admin->is_founder) $where['c.city_id'] = $_CITY['aid'];
        list($total, $list) = $CU->find('c.*', $where, 'couponid', $start, $offset, TRUE, 's.name,s.subname,s.pid');
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
        }
        $admin->tplname = cptpl('coupon_check', MOD_FLAG);
        break;
    default:
        $op = 'list';
        if($_GET['dosubmit']) {
            $CU->db->join($CU->table,'c.sid',$CU->subject_table,'s.sid','LEFT JOIN');
			if(!$admin->is_founder) $where['c.city_id'] = $_CITY['aid'];
            if($_GET['city_id']) $CU->db->where('c.city_id', $_GET['city_id']);
            if($_GET['catid']) $CU->db->where('c.catid', $_GET['catid']);
            if($_GET['sid']) $CU->db->where('c.sid', $_GET['sid']);
            if($_GET['subject']) $CU->db->where_like('c.subject', '%'.$_GET['subject'].'%');
            if($_GET['username']) $CU->db->where_like('c.username', $_GET['username']);
            if($_GET['starttime']) $CU->db->where_more('c.starttime', strtotime($_GET['starttime']));
            if($_GET['endtime']) $CU->db->where_less('c.endtime', strtotime($_GET['endtime']));
            $CU->db->where('c.status', array(1,2));
            if($total = $CU->db->count()) {
                $CU->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'sid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $CU->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $CU->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $CU->db->select('c.*,s.name,s.subname,s.pid');
                $list = $CU->db->get();
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,$op,$_GET));
            }
        }
        $admin->tplname = cptpl('coupon_list', MOD_FLAG);
}
?>