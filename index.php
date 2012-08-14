<?php
/**
* ===========================================
* Project: Modoer(Mudder)
* Version: 2.9
* Time: 2007-7-17 @ Create
* Copyright (c) 2007 - 2012 Moufer Studio
* Website: http://www.modoer.com
* Developer: Moufer
* E-mail: moufer@163.com
* ===========================================
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/

if(!is_file(dirname(__FILE__).'/data/install.lock')) {
    exit('<a href="install.php">Unsure whether the system of Modoer has been installed or not.</a><br /><br />If it has already been installed,under the folder of ./data , please create a new empty file named as "install.lock".');
}

//application init
require dirname(__FILE__).'/core/init.php';
if($_G['cfg']['index_module'] && $_GET['m']=='index' && $_GET['act']=='index') {
    unset($_GET['m'], $_GET['act']);
}
if($_G['cfg']['index_module'] && !isset($_GET['m']) && !isset($_GET['act'])) {
    $m = _get('m', null, '_T');
    if(!$m || !preg_match("/^[a-z]+$/", $m)) {
        $m = $_G['cfg']['index_module'];
        if(strposex($m, '/')) list($m,$_GET['act']) = explode('/', $m);
    } else {
        $m = 'index';
    }
} else {
    $m = _get('m', 'index', '_T');
}

if($m && $m != 'index') {

    if($_GET['unkown_city_domain'] && !$_G['in_ajax'] && $_GET['name'] != $_GET['unkown_city_domain']) {
        $url = $_GET['Rewrite'] ? ($_CFG['siteurl'] . $_GET['Rewrite']) : ($_GET['m']?url("city:0/$_GET[m]",'',true):$_GET['siteurl']);
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $url");
    }

    if(check_module($m)) {
        $f = $m . DS . 'common.php';
        if(!file_exists(MUDDER_MODULE . $f)) show_error(lang('global_file_not_exist', ('./core/modules/' . $f)));
        include MUDDER_MODULE . $f;
    } else {
        http_404();
    }

} else {

    if($_GET['unkown_city_domain'] && !$_G['in_ajax']) http_404();
    if(!$_CITY && (!$_GET['act']||$_GET['act']=='index')) {
        //First visit
        if(!$_S_CITY = get_single_city()) {
            include MUDDER_CORE  . 'modules' . DS . 'modoer' . DS . 'city.php';
            exit;
            //location('index.php?act=city');
        }
        init_city($_S_CITY['aid']);
        $_CITY = $_S_CITY;
        unset($_S_CITY);
    }
    if(empty($_GET['city_domain']) && !$_GET['act'] && $_CFG['model_city_sldomain']) {
        location(get_city_domain($_CITY['aid']));
    }

    $_G['m'] = $m = 'index';
    $acts = array('ajax','map','seccode','js','search','announcement','city','upload');
    
    if(isset($_GET['act']) && in_array($_GET['act'], $acts)) {
        include MUDDER_CORE  . 'modules' . DS . 'modoer' . DS . $_GET['act'] . '.php';
        exit;
    } elseif(!$_GET['act'] || $_GET['act'] == 'index') {
        //page name
        define('SCRIPTNAV', 'index');
        //load template
        include template('modoer_index');
    } else {
        http_404();
    }

}
?>