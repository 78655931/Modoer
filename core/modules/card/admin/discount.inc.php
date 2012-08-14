<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$DC =& $_G['loader']->model(MOD_FLAG.':discount');
$op = _input('op',null,MF_TEXT);
$_G['loader']->helper('form','card');

switch($op) {
    case 'delete':
        $DC->delete($_POST['sids']);
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'update':
        $DC->update($_POST['cards']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'add':
        if($sid = _input('sid',null,MF_INT_KEY)) {
            if($detail = $DC->read($sid)) {
                //已存在，跳转到编辑
                location(cpurl($module,$act,'edit',array('sid'=>$sid)));
            }
            $S =& $_G['loader']->model('item:subject');
            if(!$subject = $S->read($sid)) redirect('item_subject_empty');
            $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
        }
        $admin->tplname = cptpl('discount_save', MOD_FLAG);
        break;
    case 'edit':
        if(!$sid = (int) $_GET['sid']) redirect(lang('global_sql_keyid_invalid','sid'));
        if(!$detail = $DC->read($sid)) redirect('card_empty');
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($detail['sid'],'sid,pid,name,subname')) redirect('item_empty');
        $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
        $admin->tplname = cptpl('discount_save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid','sid'));
        } else {
            $sid = null;
        }
        $post = $DC->get_post($_POST);
        $DC->save($post, $sid);

        $def_url = get_forward(cpurl($module,$act,'list'),1);
        if(empty($def_url )||strpos($def_url,'cpmenu')) $def_url = cpurl($module,$act,'list');

		$navs = array(
			array('name'=>'global_redirect_return', 'url'=>$def_url),
			array('name'=>'card_redirect_discountlist', 'url'=>cpurl($module,$act,'list')),
			array('name'=>'card_redirect_adddiscount', 'url'=>cpurl($module,$act,'add')),
		);
        redirect('global_op_succeed', $navs);
        break;
    default:
        $op = 'list';
        //if($_GET['dosubmit']) {
            $DC->db->join($DC->table,'c.sid',$DC->subject_table,'s.sid','LEFT JOIN');
			if(!$admin->is_founder) $DC->db->where('city_id', $_CITY['aid']);
            if($_GET['city_id']) $DC->db->where('s.city_id', _int_keyid($_GET['city_id']));
            if($_GET['pid']) {
                $DC->db->where('s.pid', _int_keyid($_GET['pid']));
            }
            if($_GET['sid']) $DC->db->where('c.sid', $_GET['sid']);
            if($_GET['cardsort']) $DC->db->where('c.cardsort', $_GET['cardsort']);
            if($_GET['starttime']) $DC->db->where_more('c.addtime', strtotime($_GET['starttime']));
            if($_GET['endtime']) $DC->db->where_less('c.addtime', strtotime($_GET['endtime']));
            if($total = $DC->db->count()) {
                $DC->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'sid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $DC->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $DC->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $DC->db->select('c.*,s.city_id,s.name,s.subname,s.pid');
                $list = $DC->db->get();
                /*
                $url = SELF;
                $split = '?';
                foreach($_GET as $k => $v) {
                    if($k=='page') continue;
                    $url .= $split . rawurlencode($k) .'=' . rawurlencode($v);
                    $split  = '&';
                }
                */
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,$op,$_GET));
            }
        //}
        $admin->tplname = cptpl('discount_list', MOD_FLAG);
}
?>