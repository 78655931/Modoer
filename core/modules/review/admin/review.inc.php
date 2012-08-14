<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(':review');
$op = _input('op');

$_G['loader']->helper('form', MOD_FLAG);
$forward = get_forward(cpurl($module,$act));

switch ($op) {
    case 'checkup':
        $R->checkup($_POST['rids']);
        redirect('global_op_succeed', $forward);
        break;
    case 'delete':
        $R->delete($_POST['rids'], TRUE, $_POST['delete_point']);
        redirect('global_op_succeed', $forward );
        break;
    case 'checklist':
        $where = array();
        if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
        $where['r.status'] = 0;
        $select = 'r.*';
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset, TRUE);
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, $op, $_GET));
        $admin->tplname = cptpl('review_check', MOD_FLAG);
        break;
    case 'report':
    case 'edit':
        if($op == 'report') {
            $reportid = _get('reportid',null,MF_INT_KEY);
            $RT =& $_G['loader']->model('review:report');
            $report = $RT->read($reportid);
            $_GET['rid'] = $report['rid'];
        }
        $rid = (int) $_GET['rid'];
        $select = 'r.*';
        if(!$detail = $R->read($rid, $select, TRUE)) {
            if($op != 'report') redirect('global_op_empty');
        }
        if($detail) {
            $typeinfo = $R->get_type($detail['idtype']);
            $OBJ =& $_G['loader']->model($typeinfo['model_name']);
            if(!$object = $OBJ->read($detail['id'])) redirect('review_object_empty');
            $catcfg =& $OBJ->get_review_config($object);
            $rogid = $catcfg['review_opt_gid'];
            $review_opts = $R->variable('opt_' . $rogid);
            if($detail['idtype']=='item_subject') $taggroups = $OBJ->variable('taggroup');
        }
        $admin->tplname = cptpl('review_edit', MOD_FLAG);
        break;
    case 'save':
        if($_POST['report']) {
            $reportid = (int) $_POST['reportid'];
            $RT =& $_G['loader']->model('review:report');
            $RT->disposal($_POST['report'], $reportid);
            if($_POST['report']['delete'] || $_POST['empty_review']) {
                redirect(global_op_succeed, $forward);
            }
        }

        if(!$_POST['rid'] = (int) $_POST['rid']) {
            redirect(lang('global_sql_keyid_invalid', 'rid'));
        }
        $post = $R->get_post($_POST['review']);
        $rid = $R->save($_POST['review'], $_POST['rid']);

        redirect(global_op_succeed, get_forward(cpurl($module,$act),1));
        break;
    case 'update':
        $R->update($_POST['review']);
        redirect('global_op_succeed', $forward );
        break;
    default:
        //if($_GET['dosubmit']) {
            $R->db->from($R->table);
            if(!$admin->is_founder) $R->db->where('city_id', $_CITY['aid']);
            if(is_numeric($_GET['city_id']) && $_GET['city_id'] >=0) $R->db->where('city_id', $_GET['city_id']);
            if($_GET['title']) $R->db->where_like('title', '%'.$_GET['title'].'%');
            if($_GET['idtype']) $R->db->where('idtype', $_GET['idtype']);
            if($_GET['id']) $R->db->where('id', $_GET['id']);
            if($_GET['username']) $R->db->where('username', $_GET['username']);
            if($_GET['ip']) $R->db->where('ip', $_GET['ip']);
            if($_GET['starttime']) $R->db->where_more('posttime', strtotime($_GET['starttime']));
            if($_GET['endtime']) $R->db->where_less('posttime', strtotime($_GET['endtime']));
            if($total = $R->db->count()) {
                $R->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'rid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $R->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $R->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $R->db->select('*');
                $list = $R->db->get();
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
            }
        //}
        if($_GET['id']) {
            $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $_GET['id'], true);
        }
        $admin->tplname = cptpl('review_list', MOD_FLAG);
}
?>