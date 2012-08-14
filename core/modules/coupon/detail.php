<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['id'])) $couponid = (int) $_GET['id'];
if(!$couponid) redirect(lang('global_sql_keyid_invalid','id'));

$CO = $_G['loader']->model(':coupon');
$detail = $CO->read($couponid);
if(!$detail || $detail['status']!=1) redirect('coupon_empty');

if($_GET['do'] == 'effect') {
    $CO->update_effect($couponid, $_POST['effect']);
    echo 'OK';
    exit;
}

if(!$CO->check_valid($couponid, $detail['catid'], $detail['sid'], $detail['endtime'], $detail['status'])) {
    redirect('coupon_status_invalid');
}

$category = $_G['loader']->variable('category',MOD_FLAG);

//更新浏览量
$CO->pageview($couponid);

//获取主题列表字段
if($detail['sid'] > 0) {
    $I =& $_G['loader']->model('item:subject');
    if(!$subject = $I->read($detail['sid'])) redirect('item_empty');
    //判断是否当前内容所属当前城市，不是则跳转
    if(check_jump_city($subject['city_id'])) location(url("city:$subject[city_id]/coupon/detail/id/$couponid"));
    //侧边栏显示主题信息
    $subject_field_table_tr = $I->display_sidefield($subject);
}

$urlpath = array();
$urlpath[] = url_path($MOD['name'], url("coupon/index"));
$urlpath[] = url_path($category[$detail['catid']]['name'], url("coupon/index/catid/$detail[catid]"));
$urlpath[] = url_path($detail['subject'], url("coupon/detail/id/$couponid"));

//其他模块和功能的链接
$links = $_G['hook']->hook('subject_detail_link',$subject,TRUE);
define('SUB_NAVSCRIPT','coupon');

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];

if($subject) {
    $scategory = $I->get_category($subject['catid']);
    if(!$subject['templateid'] && $scategory['config']['templateid']>0) {
        $subject['templateid'] = $scategory['config']['templateid'];
    }
}
if($subject && $subject['templateid']) {
    include template('coupon_detail', 'item', $subject['templateid']);
} else {
    include template('coupon_detail');
}
?>