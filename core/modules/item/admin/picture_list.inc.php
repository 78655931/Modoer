<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$P =& $_G['loader']->model(MOD_FLAG.':picture');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];

switch ($op) {
case 'upload':
    $P->save($P->get_post($_POST));
    redirect('global_op_succeed', cpurl($module, $act));
    break;
case 'delete':
    $P->delete($_POST['picids']);
    redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
    break;
case 'update':
    $P->update($_POST['picture']);
    redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
    break;
case 'set_thumb':
    if(!$picid = _input('picid',null,MF_INT_KEY)) redirect( lang( 'global_sql_keyid_invalid', 'picid' ) );
    $A =& $_G['loader']->model('item:album');
    $A->set_thumb($picid);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
default:
    $op = 'list';
    //if($_GET['dosubmit']) {
        $P->db->join($P->table,'p.sid','dbpre_subject','s.sid', MF_DB_LJ);
		$P->db->join_together('p.albumid','dbpre_album','a.albumid', MF_DB_LJ);
        if(!$admin->is_founder) $P->db->where('p.city_id', $_CITY['aid']);
        if(is_numeric($_GET['city_id']) && $_GET['city_id'] >=0) $P->db->where('p.city_id', $_GET['city_id']);
        if($_GET['sid']) $P->db->where('p.sid', $_GET['sid']);
        if($_GET['albumid']) $P->db->where('p.albumid', $_GET['albumid']);
        if($_GET['title']) $P->db->where_like('p.title', '%'.$_GET['title'].'%');
        if($_GET['starttime']) $P->db->where_more('p.addtime', strtotime($_GET['starttime']));
        if($_GET['endtime']) $P->db->where_less('p.addtime', strtotime($_GET['endtime']));
        if($total = $P->db->count()) {
            $P->db->sql_roll_back('from,where');
            !$_GET['orderby'] && $_GET['orderby'] = 'picid';
            !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
            $P->db->order_by('p.'.$_GET['orderby'], $_GET['ordersc']);
            $P->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
            $P->db->select('p.*,s.name as subjectname,s.subname,a.name as albumname');
            $list = $P->db->get();
            $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
        }
    //}
    $admin->tplname = cptpl('picture_list', MOD_FLAG);
 }