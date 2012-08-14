<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

if(!$sid = (int)$_GET['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
$_GET['catid'] && $catid = (int)$_GET['catid'];

$S =& $_G['loader']->model('item:subject');
if(!$subject = $S->read($sid)) redirect('item_empty');
//if($subject['products'] < 1) redirect('product_empty');
$modelid = $S->get_modelid($subject['pid']);
$model = $S->variable('model_' . $modelid);

$total = $subject['products'];
if($total > 0) {
    $P =& $_G['loader']->model(':product');
    $where = array('sid'=>$sid);
    $catid && $where['catid'] = $catid;
    $where['status'] = 1;

    $offset = 20;
    $start = get_start($_GET['page'], $offset);

    //获取产品列表字段
    $product_modelid = $P->get_modelid($subject['pid']);
    $product_field = array();
    $select = 'p.pid,catid,sid,subject,dateline,grade,pageview,comments,picture,thumb,description';
    $select_arr = explode(',', $select);
    $fields = $P->variable('field_' . $product_modelid);
    foreach($fields as $val) {
        if(!$val['show_list']) continue;
        if(!in_array($val['fieldname'], $select_arr)) {
            $select .= ',' . $val['fieldname'];
        }
        $product_field[] = $val;
    }
    list($total, $list) = $P->find_list($product_modelid, $select, $where, array('dateline'=>'DESC'), $start, $offset, TRUE);
    $multipage = multi($total, $offset, $_GET['page'], url("product/list/sid/$sid/catid/$catid/page/_PAGE_"));
    unset($select, $select_arr, $val, $fields);
    //主图内容
    $PFD =& $_G['loader']->model('product:fielddetail');
    //样式设计
    $PFD->td_num = 1; //表只有1个td
    $PFD->class = "";
    $PFD->width = "";
    $PFD->align = "left";
}

$active = array();
$active['category'][(int)$catid] = ' class="selected"';

//产品分类
$_G['loader']->helper('query','product');
$category = query_product::category(array('sid'=>$sid));

$urlpath = array();
$urlpath[] = url_path($subject['name'].$subject['subname'], url("item/detail/id/$sid"));
$urlpath[] = url_path(lang('product_title'), url("product/list/sid/$sid"));
$catid > 0 && $urlpath[] = url_path($category[$catid]['name'], url("product/list/sid/$sid/catid/$catid"));

//获取主题侧边显示字段
$subject_field_table_tr = $S->display_sidefield($subject);

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','product');

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $S->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('product_list', 'item', $subject['templateid']);
} else {
    include template('product_list');
}
?>