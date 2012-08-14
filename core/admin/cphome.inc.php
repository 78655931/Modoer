<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$installfiles = MUDDER_ROOT . 'install' . DS . 'install.php';
$install_exists = is_file($installfiles);

$server = array();
$server['time'] = date('Y-m-d H:i:s', $_G['timestamp']);
$server['upfile'] = (ini_get('file_uploads')) ? 'On ' . ini_get('upload_max_filesize') : 'Off';
$server['register_globals'] = (ini_get('register_globals')) ? 'On' : 'Off';
$server['safe_mode'] = (ini_get('safe_mode')) ? 'On' : 'Off';
$server['software'] = $_SERVER['SERVER_SOFTWARE'];
$server['phpver'] = phpversion(); //KB
$server['mysqlver'] = $_G['db']->version();
$s = function_exists('gd_info') ? gd_info() : '<span class="font_1"><strong>Not Support</strong></span>';
$server['gd'] = is_array($s) ? ($s['GD Version']) : $s;
if(function_exists('memory_get_usage')) {
    $server['memory'] = round(memory_get_usage()/1024,2); //KB
}

$sessions = $admin->get_sessions();

if($_G['cfg']['console_total']) {
    $system = array();
    foreach($_G['modules'] as $key => $val) {
        if(!$admin->check_access($val['flag'])) continue;
        $hook_file_home = MUDDER_MODULE . $val['flag'] . DS . 'inc' . DS . 'cphome_hook.php';
        if(is_file($hook_file_home)) include $hook_file_home;
    }
    unset($hook_file_home);
}

$admin->tplname = cptpl('cphome');
?>