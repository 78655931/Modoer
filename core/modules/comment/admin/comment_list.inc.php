<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$CM =& $_G['loader']->model(':comment');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$_G['loader']->helper('form', MOD_FLAG);

switch($op) {
    case 'delete':
        $CM->delete($_POST['cids'],$_POST['uppoint']);
        redirect('global_op_succeed_delete', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $CM->checkup($_POST['cids']);
        redirect('global_op_succeed_checkup', get_forward(cpurl($module,$act,'checklist')));
        break;
    case 'edit':
        if(!$detail = $CM->read((int)$_GET['cid'])) redirect('comment_empty');
        $admin->tplname = cptpl('comment_save', MOD_FLAG);
        break;
    case 'save':
        if(!$cid = (int)$_POST['cid']) redirect(lang('global_sql_keyid_invalid','cid'));
        $post = $CM->get_post($_POST);
        $CM->save($post, $cid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'checklist':
        $op = $_GET['op'] = 'list';
        $where = array('status'=>0);
        list($total, $list) = $CM->find('*', $where, 'dateline', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list'));
        }
        $admin->tplname = cptpl('comment_check', MOD_FLAG);
        break;
    default:
        $op = 'list';
        if($_GET['dosubmit']) {
            $CM->db->from($CM->table);
            if($_GET['idtype']) $CM->db->where('idtype', $_GET['idtype']);
            if($_GET['id']) $CM->db->where('id', $_GET['id']);
            if($_GET['title']) $CM->db->where('title', $_GET['title']);
            if($_GET['username']) $CM->db->where('username', $_GET['username']);
            if($_GET['ip']) $CM->db->where('ip', $_GET['ip']);
            if($_GET['starttime']) $CM->db->where_more('dateline', strtotime($_GET['starttime']));
            if($_GET['endtime']) $CM->db->where_less('dateline', strtotime($_GET['endtime']));
            if($total = $CM->db->count()) {
                $CM->db->sql_roll_back('from,where');
                !$_GET['orderby'] && $_GET['orderby'] = 'cid';
                !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
                $CM->db->order_by($_GET['orderby'], $_GET['ordersc']);
                $CM->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
                $list = $CM->db->get();
                /*
                $url = SELF;
                $split = '?';
                foreach($_GET as $k => $v) {
                    if($k=='page') continue;
                    $url .= $split . rawurlencode($k) .'=' . rawurlencode($v);
                    $split  = '&';
                }
                */
                $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
            }
        }
        $admin->tplname = cptpl('comment_list', MOD_FLAG);
}
?>