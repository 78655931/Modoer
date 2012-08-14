<?php
/**
* System init
* @author moufer<moufer@163.com>
* @copyright (C)2001-2012 Moufersoft
*/
define('IN_MUDDER', TRUE);
define('DEBUG', FALSE);
define('DS', DIRECTORY_SEPARATOR);
define('MUDDER_DOMAIN', $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$_SERVER['SERVER_NAME']);
define('MUDDER_CORE', dirname(__FILE__) .  DS);
define('MUDDER_ROOT', dirname(MUDDER_CORE) . DS);
define('MUDDER_DATA', MUDDER_ROOT . 'data' . DS);
define('MUDDER_CACHE', MUDDER_DATA . 'cachefiles' . DS);
define('MUDDER_MODULE', MUDDER_CORE . 'modules' . DS);
define('MUDDER_PLUGIN', MUDDER_CORE . 'plugins' . DS);

if(DEBUG) {
    error_reporting(E_ALL ^ E_NOTICE);
    @ini_set('display_errors', 'On');
} else {
    error_reporting(0);
    @ini_set('display_errors', 'Off');
}

if(function_exists('set_magic_quotes_runtime')) {
    @set_magic_quotes_runtime(0);
}
$_G = $_C = $_CFG = $_HEAD = $_QUERY = $MOD = array();

$_G['mtime'] = explode(' ', microtime());
$_G['starttime'] = $_G['mtime'][1] + $_G['mtime'][0];

require MUDDER_DATA . 'config.php';
require MUDDER_CORE . 'version.php';

//timezone
if(function_exists('date_default_timezone_set')) {
    if($_G['timezone'] == 8) $_G['timezone'] = 'Asia/Shanghai';
    @date_default_timezone_set($_G['timezone']);
}
$_G['timestamp'] = time();

header('Content-type: text/html; charset=' . $_G['charset']);
if($_G['attackevasive'] && (!defined('IN_ADMIN') || SCRIPTNAV != 'seccode')) {
    include MUDDER_CORE . 'fense.php';
}

require MUDDER_CORE . 'function.php'; // global function
require MUDDER_CORE . 'loader.php';
$_G['loader'] = new ms_loader();

// web info
$_G['web'] = get_webinfo();
$_G['ip'] = get_ip();
$_G['ips'] = '127.0.0.1';
define('SELF', $_G['web']['self']);
define('URLROOT', get_urlroot());

$_G['loader']->lib('base', NULL, FALSE);
$_G['loader']->lib('model', NULL, FALSE);

$_G['cookie'] = $_G['loader']->cookie();
$_C =& $_G['cookie'];
$_G['loader']->helper('cache');
$_G['cfg'] = read_cache(MUDDER_CACHE . 'modoer_config.php');
$_G['modules'] = read_cache(MUDDER_CACHE . 'modoer_modules.php');
if(!$_G['cfg'] || !$_G['modules']) {
    include MUDDER_MODULE . 'modoer' . DS . 'inc' . DS . 'cache.php';
    $_G['modules'] = read_cache(MUDDER_CACHE . 'modoer_modules.php');
    foreach(array_keys($_G['modules']) as $flag) {
        $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'cache.php';
        if(is_file($file)) include $file;
    }
    show_error('global_cache_succeed');
}
$_MODULES =& $_G['modules'];
$_CFG =& $_G['cfg'];
$_G['cfg']['siteurl'] = $_G['cfg']['siteurl'] . (substr($_G['cfg']['siteurl'] , -1) != '/' ? '/' : '');

if($_G['cfg']['siteclose'] && !defined('IN_ADMIN') && $_GET['act'] != 'seccode') {
    show_error($_G['cfg']['closenote']);
}
if($_G['cfg']['useripaccess'] && !check_ipaccess($_G['cfg']['useripaccess'])) {
    show_error(lang('global_ip_without_list'));
}
if($_G['cfg']['ban_ip'] && check_ipaccess($_G['cfg']['ban_ip'])) {
    show_error(lang('global_ip_not_have_access'));
}
//hook
$_G['hook'] = $_G['loader']->lib('hook');
$_G['hook']->register();
//ob
if($_G['cfg']['gzipcompress'] && function_exists('ob_gzhandler')) {
    @ob_start('ob_gzhandler');
} else {
    $_G['cfg']['gzipcompress'] = 0;
    ob_start();
}

//URL rewrite
if($_G['cfg']['rewrite']) {
    $_G['rewriter'] =& $_G['loader']->lib('urlrewriter');
    $_G['rewriter']->set_mod($_G['cfg']['rewrite_mod']);
    $_G['rewriter']->set_domain_mod($_G['cfg']['city_sldomain']);
    $_G['rewriter']->hide_index = $_G['cfg']['rewrite_hide_index'];
    if(isset($_GET['Rewrite'])) {
        $__url_tag = $_G['rewriter']->html_recover($_GET['Rewrite']);
        if($_G['cfg']['rewrite_mod']=='pathinfo'&&$_G['cfg']['rewrite_location']) $__url_tag_location = true;
    } elseif(isset($_GET['Pathinfo'])) {
        $__url_tag = $_G['rewriter']->pathinfo_recover($_GET['Pathinfo']);
        if($_G['cfg']['rewrite_mod']=='html'&&$_G['cfg']['rewrite_location'])  $__url_tag_location = true;
    } elseif($_G['index_url_rewrite']) {
        $_G['rewriter']->index_recover();
    }
}

define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
if(MAGIC_QUOTES_GPC) {
    $_POST = strip_slashes($_POST);
    $_GET = strip_slashes($_GET);
    $_COOKIE = strip_slashes($_COOKIE);
    $_REQUEST = strip_slashes($_REQUEST);
}

if(!defined('IN_ADMIN')) {
    $_POST = strip_sql($_POST);
    $_GET = strip_sql($_GET);
    $_COOKIE = strip_sql($_COOKIE);
    $_REQUEST = strip_sql($_REQUEST);
}

//ajax
if($_G['in_ajax'] = isset($_POST['in_ajax']) ? $_POST['in_ajax'] : (isset($_GET['in_ajax']) ? $_GET['in_ajax'] :  0)) {
    define('IN_AJAX', TRUE);
}
//iframe
if($_G['in_iframe'] = isset($_POST['in_iframe']) ? $_POST['in_iframe'] : (isset($_GET['in_iframe']) ? $_GET['in_iframe'] :  0)) {
    define('IN_IFRAME', TRUE);
}
/*
if(!$in_ajax && function_exists('get_headers')) {
    if($headers = @get_headers($php_self, 1)) {
        $_G['in_ajax'] = $headers['X-Requested-With'] == 'XMLHttpRequest';
    }
}
*/

//init hook
$_G['hook']->hook('init');
// user
if(!defined('IN_ADMIN')) {
    $_G['user'] =& $_G['loader']->model('member:user');
    $_G['user']->auto_login();
    $user =& $_G['user'];
    //login access
    if($user->get_access('member_forbidden') && $_GET['act'] != 'login') {
        show_error('member_access_forbidden');
    }
}

//multi-city
$_G['city'] = array();
$_CITY =& $_G['city'];
if($_CITY = get_city()) init_city($_CITY['aid']);
if($_G['cfg']['city_sldomain']) {
    $_G['cfg']['citypath_without'] && $_G['cfg']['citypath_without'] = explode("\r\n",$_G['cfg']['citypath_without']);
    $_G['cfg']['citypath_without'][] = 'member/*';
    $_G['cfg']['citypath_without'][] = 'index/*';
    foreach($_G['modules'] as $__module_flag) {
        $_G['cfg']['citypath_without'][] = "$__module_flag[flag]/member";
    }
    unset($__module_flag);
}
//url rewrite
if($__url_tag_location&&$__url_tag) {
    location(url($__url_tag),true);
}
//item 
if($_GET['unkown_city_domain']!='') {
    //Subdomains
    if('http://'.$_G['web']['domain'].'/' != $_G['cfg']['siteurl'] && !$_G['in_ajax']) {
        include MUDDER_DATA . 'config_domain.php';
        if($_G['sldomain_level']) foreach($_G['sldomain_level'] as $_sldomain_hook_file) {
            if(is_file(MUDDER_CORE . $_sldomain_hook_file)) {
                $_sldomain_fun = include(MUDDER_CORE . $_sldomain_hook_file);
                if($_sldomain_fun()) {
                    $_G['sldomain'] = TRUE;
                    break;
                }
            }
        }
    }
}

//city Url
if(check_use_global_url("$_GET[m]/$_GET[act]") && $_G['cfg']['city_sldomain']=='2') {
    location(url($_GET['real_rewrite_url']));
}

// mutipage init
$_GET['page'] = (int) _get('page');
$_GET['page'] = $_GET['page'] < 1 ? 1 : $_GET['page'];
$_GET['offset'] = (int) _get('offset');
$_GET['offset'] = $_GET['offset'] < 1 ? 20 : $_GET['offset'];

// header init
$_G['show_sitename'] = TRUE;
$_HEAD['title'] = $_G['cfg']['sitename'] . $_G['cfg']['titlesplit'] . $_G['cfg']['subname'];
$_HEAD['keywords'] = $_G['cfg']['meta_keywords'];
$_HEAD['description'] = $_G['cfg']['meta_description'];
$_HEAD['css'] = '';
$_HEAD['js'] = '';

// rand
$_G['random'] = random(5);

// datacall
$_G['datacall'] =& $_G['loader']->model('datacall');
//$_G['datacall']->plan_delete();

