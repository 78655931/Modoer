<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$IM =& $_G['loader']->model(MOD_FLAG.':impress');
$op = _input('op');

switch ($op) {
case 'delete':
    $IM->delete($_POST['ids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    $S =& $_G['loader']->model('item:subject');
    $pid = $_GET['pid'] = isset($_GET['pid']) ? $_GET['pid'] : $MOD['pid'];
    (!$pid || !$S->get_category($pid)) and redirect('item_empty_default_pid');
    $category = $S->variable('category');
    $modelid = $category[$pid]['modelid'];
    $model = $S->variable('model_' . $modelid);

    $IM->db->join('dbpre_subjectimpress','si.sid','dbpre_subject','s.sid');
    $IM->db->where('pid', $pid);
    if($total = $IM->db->count()) {
        $IM->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'si.total';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $IM->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $IM->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $IM->db->select('si.*,s.name,s.subname,s.pid,s.catid,s.city_id,s.aid');
        $list = $IM->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'',$_GET));
    }

    $admin->tplname = cptpl('impress_list', MOD_FLAG);
 }