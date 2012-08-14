<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$A =& $_G['loader']->model(':article');
$op = _input('op',null,MF_TEXT);
$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('misc',MOD_FLAG);
$forward = get_forward(cpurl($module,$act));
(strposex($forward,'cpmenu') || strposex($forward,'cpheader')) && $forward = cpurl($module,$act);
$forward = str_replace('&amp;', '&', $forward);

switch($op) {
case 'down_image':
    $admin->tplname = cptpl('article_down_image', MOD_FLAG);
    break;
case 'down_image_ing':
    $page = _input('page', null, MF_INT);
    $day = _input('day', null, MF_INT);
    $catid = _input('catid', null, MF_INT);
    if(!$catid) $catid = $catid_2>0 ? $catid_2 : $catid_1;
    if(!$page||$page<1) $page = 1;
    if($result = $A->batch_down_images($catid, $day, $page)) {
        list($total,$subject,$img_total,$img_succeed,$img_lost) = $result;
        redirect(lang("article_down_image_next",array($subject,$img_total,$img_succeed,$img_lost,$page,$total)),cpurl($module, $act, 'down_image_ing',
            array('day'=>$day, 'catid'=>$catid, 'total'=>$total, 'page'=>$page+1)));
    } else {
        redirect('article_down_image_succeed', cpurl($module,$act,'down_image'));
    }
    break;
case 'add':
    $admin->tplname = cptpl('article_save', MOD_FLAG);
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->item = 'admin';
    $editor->pagebreak = true;
    $edit_html = $editor->create_html();
    break;
case 'edit':
    if(!$detail = $A->read($_GET['articleid'])) redirect('article_empty');
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->item = 'admin';
    $editor->pagebreak = true;
    $editor->width = '99%';
    $editor->content = $detail['content'];
    $edit_html = $editor->create_html();
    $admin->tplname = cptpl('article_save', MOD_FLAG);
    break;
case 'save':
    if($_POST['do'] == 'edit') {
        if(!$articleid = (int) $_POST['articleid']) redirect(lang('global_sql_keyid_invalid','articleid'));
    } else {
        $articleid = null;
    }
    $post = $A->get_post($_POST);
    $A->save($post, $articleid);
    $forward = $_POST['do'] == 'edit' ? get_forward(cpurl($module,$act,'list'),1) :cpurl($module,$act,'list');
	$navs = array(
		array('name'=>'global_redirect_return', 'url'=>$forward),
		array('name'=>'article_redirect_articlelist', 'url'=>cpurl($module,$act,'list')),
		array('name'=>'article_redirect_addarticle', 'url'=>cpurl($module,$act,'add')),
	);
    redirect('global_op_succeed', $navs);
    break;
case 'delete':
    $A->delete($_POST['articleids']);
    redirect('global_op_succeed', cpurl($module,$act,'list'));
    break;
case 'listorder':
    $A->listorder($_POST['articles']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'upatt':
    $A->upatt($_POST['articleids'],(int)$_POST['att_select']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'upcity':
    $A->upcity($_POST['articleids'],(int)$_POST['city_id_select']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'checkup':
    $A->checkup($_POST['articleids']);
    redirect('global_op_succeed', cpurl($module,$act,'checklist'));
case 'checklist':
    $where = array();
    if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
    $where['status'] = 0;
    $offset = 20;
    $start = get_start($_GET['page'],$offset);
    list($total,$list) = $A->find('articleid,city_id,subject,catid,att,dateline,uid,author,status',$where,'dateline',$start,$offset,true);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'checklist'));
    }
    $admin->tplname = cptpl('article_check', MOD_FLAG);
    break;
default:
    $op = 'list';
    $sid = _get('sid',null,MF_INT_KEY);
    $pid = _get('pid',null,MF_INT_KEY);
    $catid = _get('catid',null,MF_INT_KEY);
    $city_id = _get('city_id',null,MF_INT_KEY);

    if($sid > 0) {
        $A->db->join('dbpre_subjectlink', 'sl.flagid', $A->table, 'a.articleid','LEFT JOIN');
    } else {
        $A->db->from($A->table,'a');
    }

    if(!$admin->is_founder) {
        $A->db->where('city_id', $_CITY['aid']);
    } elseif(is_numeric($_GET['city_id'])) {
        $A->db->where('city_id', $city_id);
    }
    if($catid) {
        $A->db->where('catid', $catid);
    } elseif($pid) {
        $C =& $_G['loader']->model('article:category');
        $cats = $C->get_sub_cats($pid);
        $A->db->where_in('catid', array_keys($cats));
    }
    if($sid > 0) {
        $A->db->where('sl.sid', $sid);
        $A->db->where('sl.flag', 'article');
    }
    if($_GET['subject']) $A->db->where_like('subject', '%'._T($_GET['subject']).'%');
    if($_GET['author']) $A->db->where('author', _T($_GET['author']));
    if(is_numeric($_GET['att'])) $A->db->where('att', (int)$_GET['att']);
    if($_GET['starttime']) $A->db->where_more('a.dateline', strtotime($_GET['starttime']));
    if($_GET['endtime']) $A->db->where_less('a.dateline', strtotime($_GET['endtime']));

    if($total = $A->db->count()) {
        $A->db->sql_roll_back('from,where');
        !$_GET['orderby'] && $_GET['orderby'] = 'articleid';
        !$_GET['ordersc'] && $_GET['ordersc'] = 'DESC';
        $A->db->order_by($_GET['orderby'], $_GET['ordersc']);
        $A->db->limit(get_start($_GET['page'], $_GET['offset']), $_GET['offset']);
        $A->db->select('articleid,subject,city_id,catid,att,a.dateline,uid,author,thumb,comments,pageview,digg,status,listorder');
        $list = $A->db->get();
        $multipage = multi($total, $_GET['offset'], $_GET['page'], cpurl($module,$act,'list',$_GET));
    }

    if($sid>0) $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
    $admin->tplname = cptpl('article_list', MOD_FLAG);
}
?>