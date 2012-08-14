<?php
define('MOD_FLAG', 'item');
define('MOD_ROOT', MUDDER_MODULE . MOD_FLAG . DS);

if(!$_CITY) $_CITY = get_default_city();
if(!$_G['mod'] = read_cache(MUDDER_CACHE . MOD_FLAG.'_config.php')) {
    if(isset($_G['modules'][MOD_FLAG])) {
        $C =& $_G['loader']->model('config');
        $C->write_cache(MOD_FLAG);
        include MOD_ROOT . 'inc' . DS . 'cache.php';
        show_error('global_cache_module_succeed');
    }
    if(empty($_G['mod'])) {
        redirect('global_module_not_install');
    }
}
if(!$_G['modules'][MOD_FLAG]) redirect('global_module_disable');
$_G['mod'] = array_merge($_G['mod'], $_G['modules'][MOD_FLAG]);
$MOD =& $_G['mod'];
$_GET['act'] = _T($_GET['act']);

if(!$MOD['list_filter_li_collapse_num']) $MOD['list_filter_li_collapse_num'] = 1000000;

if(!defined('IN_ADMIN')) {
    $acts = array('manage','ajax','category','member','index','list','detail','pic','allpic','reviews',
		'top','tag','rss','search','album','mtool','related','tops','taobaoke','map','batch_post','discussion');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'category';

	if($_GET['top_appkey']&&$_GET['top_session']&&$_GET['top_parameters']) {
		echo '<h3>sessionkey:</h3><p>'._T($_GET['top_session']).'</p>';exit;
	}

    include MOD_ROOT . $_GET['act'] . '.php';
}
?>