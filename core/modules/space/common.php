<?php
define('MOD_FLAG', 'space');
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

$space_menus = $_G['loader']->variable('menu_' . $MOD['space_menuid']);

if(!defined('IN_ADMIN')) {
    $acts = array('index','reviews','subjects','friends','member');
    if(!in_array($_GET['act'], $acts)) $_GET['act'] = 'index';

    if(!$_GET['uid'] && $_GET['username']) {
        $member = $user->read(_T($_GET['username']), TRUE);
        $uid = $member['uid'];
    }

    include MOD_ROOT . $_GET['act'] . '.php';
}

?>