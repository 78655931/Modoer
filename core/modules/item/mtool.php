<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');

$_G['fullalways'] = TRUE;

if($sid = (int) $_GET['sid']) {
    unset($_GET['id'],$_GET['name']);
} elseif($sid = (int) $_GET['id']) {
    unset($_GET['id'],$_GET['name']);
    $_GET['sid'] = $sid;
}

if(!$sid && !$domain) jswrite(lang('global_sql_keyid_invalid', 'id'));

//取得主题信息
if($sid) {
    $detail = $I->read($sid);
} else {
    $_G['fullalways'] = TRUE;
    //域名合法性检测
    if(!$I->domain_check($domain)) jswrite(lang('global_sql_keyid_invalid', 'id'));
    if($detail = $I->read($domain,'*',TRUE,TRUE)) {
        $_GET['sid'] = $sid = (int)$detail['sid'];
    } else {
        location($_G['cfg']['siteurl']);
    }
}
if(!$detail||!$detail['status']) jswrite('item_empty');

//主题管理员标记
$is_owner = $detail['owner'] == $user->username;
$category = $I->get_category($detail['catid']);
if(!$pid = $category['catid']) {
    jswrite('item_cat_empty');
}
$modelid = $I->category['modelid'];
$rogid = $I->category['review_opt_gid'];

//取得分类、模型、点评项以及地区信息
$catcfg =& $category['config'];
if(!$model = $I->get_model($modelid)) jswrite('item_model_empty');
if($model['usearea']) $area = $I->loader->variable('area');

//生成表格内容
$detail_custom_field = $I->display_detailfield($detail);

//载入模板
include template('item_subject_mtool');

$output = ob_get_contents();
ob_end_clean();
$output = "document.write(\""._JStr($output)."\");";
$_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
echo $output;

exit;

function jswrite($string) {
	echo "document.write(\""._JStr(lang($string))."\");";
	exit;
}