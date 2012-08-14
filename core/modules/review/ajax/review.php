<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'get', 'add_flower', 'get_flower', 'post_report' );
$login_ops = array( 'add_flower' );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}

$R =& $_G['loader']->model(':review');

switch($op) {

case 'get':

    if(!$idtype = _post('idtype',null,MF_TEXT)) redirect(lang('global_sql_keyid_invalid', 'idtype'));
    if(!$id = _post('id',0,MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid', 'id'));
    
    //取得点评对象信息
    $typeinfo = $R->get_type($idtype);
    $OBJ =& $_G['loader']->model($typeinfo['model_name']);
    if(!$object = $OBJ->read($id)) redirect('review_object_empty');
    $catcfg =& $OBJ->get_review_config($object);
    $rogid = $catcfg['review_opt_gid'];

    //筛选
    $filter_list = array('all','best','bad','pic','digest');
    $review_filter = in_array($_POST['filter'],$filter_list) ? $_POST['filter'] : 'all';

    $order_list = array(
        'posttime' => array('posttime'=>'DESC'), 
        'flower' => array('flowers'=>'DESC'), 
        'respond' => array('responds'=>'DESC'), 
    );

    $review_orderby = $order_list[$_POST['order']] ? $_POST['order'] : 'posttime';

    //取得点评数据
    $where = array();
    $where['idtype'] = $idtype;
    $where['id'] = $id;
    if($review_filter=='best') $where['best'] = 1;
    if($review_filter=='bad') $where['best'] = 0;
    if($review_filter=='pic') $where['havepic'] = 1;
    if($review_filter=='digest') $where['digest'] = 1;
    $where['status'] = 1;

    $orderby = $order_list[$review_orderby];
    $itemcfg = $_G['loader']->variable('config','item');
    $offset = $itemcfg['review_num'] > 0 ? $itemcfg['review_num'] : 5;
    $start = get_start($_GET['page'], $offset);
    $select = 'r.*,m.point,m.point1,m.groupid';

    list($total, $reviews) = $R->find($select, $where, $orderby, $start, $offset, TRUE, FALSE, TRUE);
    if($total) {
        $onclick = "get_review('$idtype',$id,'$review_filter','".$_POST['order']."',{PAGE})";
        $multipage = multi($total, $offset, $_GET['page'], url("item/detail/id/$id/view/review/page/_PAGE_"), '#review', $onclick);
    }

    //点评项
    $reviewpot = $R->variable('opt_' . $rogid);
    //标签组
    $taggroups = $OBJ->variable('taggroup');
    //预览模式
    if($vtid = _cookie('item_style_preview_'.$id,null,MF_INT_KEY)) {
        if(is_template($vtid,'item')) {
            $object['templateid'] = $vtid;
            $is_preview = true;
        }
    }

    $category = $OBJ->get_category($object['catid']);
    if(!$object['templateid'] && $category['config']['templateid']>0) $object['templateid'] = $category['config']['templateid'];

    if($object['templateid']>0) {
        include template('part_review','item',$object['templateid']);
    } else {
        include template('item_subject_detail_review');
    }
    break;

case 'post':

    if($_POST['dosubmit']) {

    } else {
        $_G['loader']->helper('form');

        $sid = (int)$_GET['sid'];

        $goto = 'review/member/ac/edit/rid/{rid}';
        $subject = $R->check_review_before($sid, FALSE, $goto);
        unset($goto);

        $pid = $subject['pid'];
        $category = $R->variable('category');
        $modelid = $category[$pid]['modelid'];
        $rogid = $category[$pid]['review_opt_gid'];
        $catcfg = $category[$pid]['config'];
        $model = $R->variable('model_' . $modelid);
        $review_opts = $R->variable('review_opt_' . $rogid);
        $taggroups = $R->variable('taggroup');
    }
    break;

case 'add_flower':

    if(!$rid = (int)$_POST['rid']) redirect(lang('global_sql_keyid_invalid', 'rid'));

    $R =& $_G['loader']->model(':review');
    if(!$review = $R->read($rid)) {
        redirect(lang('review_empty'));
    } elseif($review['uid'] == $user->uid) {
        redirect(lang('review_flower_myself'));
    }

    $F =& $_G['loader']->model(MOD_FLAG.':flower');
    $post = array('rid'=>$rid);
    $F->save($post);

    break;

case 'get_flower':

    if(!$rid = (int)$_POST['rid']) redirect(lang('global_sql_keyid_invalid', 'rid'));

    $R =& $_G['loader']->model(':review');
    if(!$review = $R->read($rid)) {
        redirect(lang('review_empty'));
    }

    $F =& $_G['loader']->model(MOD_FLAG.':flower');
    $where = array ( 'rid' => $rid );
    $offset = 100;
    $start = get_start($_GET['page'], $offset);
    list($total, $list) = $F->find($where, $start, $offset);
    if(!$total) redirect('global_empty_info');
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], 'javascript:get_flower('.$rid.', {PAGE});');
    }
    include template('review_ajax_get_flower');
    output();

    break;

case 'post_report':

    if(!$rid = (int)$_POST['rid']) redirect(lang('global_sql_keyid_invalid', 'rid'));
    if($_POST['dosubmit']) {

        $R =& $_G['loader']->model(MOD_FLAG.':report');
        //$R->get_category($pid);
        $reportid = $R->save();
        redirect('review_report_succeed');

    } else {

        $R =& $_G['loader']->model(':review');

        if(!$review = $R->read($rid)) {
            redirect(lang('review_empty'));
        }

        include template('review_ajax_post_report');
        output();
    }

    break;

default:

    redirect('global_op_unkown');

}
?>