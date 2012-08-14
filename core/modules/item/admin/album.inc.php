<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(MOD_FLAG.':album');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'update':
	$A->update(_post('album'));
	redirect('global_op_succeed',get_forward(cpurl($module,$act,'list')));
	break;
case 'delete':
	$A->delete(_post('albumids'));
	redirect('global_op_succeed_delete',get_forward(cpurl($module,$act,'list')));
	break;
default:
    $op = 'list';
    //if($_GET['dosubmit']) {
        $A->db->join($A->table,'a.sid','dbpre_subject','s.sid', MF_DB_LJ);
        if(!$admin->is_founder) $A->db->where('a.city_id', $_CITY['aid']);
        if(is_numeric($_GET['city_id']) && $_GET['city_id'] >=0) $A->db->where('a.city_id', $_GET['city_id']);
        if($_GET['sid']) $A->db->where('a.sid', $_GET['sid']);
        if($_GET['name']) $A->db->where_like('a.name', '%'.$_GET['name'].'%');
        if($_GET['starttime']) $A->db->where_more('a.lastupdate', strtotime($_GET['starttime']));
        if($_GET['endtime']) $A->db->where_less('a.lastupdate', strtotime($_GET['endtime']));
        if($total = $A->db->count()) {
            $A->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'albumid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $A->db->order_by('a.'.$_GET['orderby'], $_GET['ordersc']);
            $A->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $A->db->select('a.*,s.name as subjectname,s.subname');
            $list = $A->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
    //}
    $admin->tplname = cptpl('album_list', MOD_FLAG);
 }