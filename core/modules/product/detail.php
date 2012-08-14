<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'product');

if(!$pid = abs(_get('id','0','intval'))) {
	$pid = abs(_get('pid','0','intval'));
}
if(!$pid) redirect(lang('global_sql_keyid_invalid', 'pid'));

$P =& $_G['loader']->model(':product');
$detail = $P->read($pid);
if(!$detail || !$detail['status']) redirect('product_empty');

//生成表格内容
$FD =& $_G['loader']->model('product:fielddetail');
//样式设计
$FD->class = 'key';
$FD->width = '';
$detail_custom_field = '';
$fields = $P->variable('field_' . $detail['modelid']);
foreach($fields as $val) {
    if(in_array($val['fieldname'], array('content'))) continue;
    if($val['show_detail']) {
        $detail_field .= $FD->detail($val, $detail[$val['fieldname']]) . "\r\n";
    }
}

$S =& $_G['loader']->model('item:subject');
if(!$subject = $S->read($detail['sid'])) redirect('item_empty');

//判断是否当前内容所属当前城市，不是则跳转
if(check_jump_city($subject['city_id'])) location(url("city:$subject[city_id]/product/detail/id/$pid"));


$modelid = $S->get_modelid($subject['pid']);

//获取主题侧边栏显示字段
$subject_field_table_tr = $S->display_sidefield($subject);

$urlpath = array();
$urlpath[] = url_path($subject['name'].$subject['subname'], url("item/detail/id/$sid"));
$urlpath[] = url_path(lang('product_title'), url("product/list/sid/$sid"));
$urlpath[] = url_path($detail['subject'], url("product/detail/pid/$pid"));

//更新浏览量
$P->pageview($pid);

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
    include template('product_detail', 'item', $subject['templateid']);
} else {
    include template('product_detail');
}
?>