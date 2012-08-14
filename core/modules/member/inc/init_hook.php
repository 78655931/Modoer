<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$__membercfg = $_G['loader']->variable('config','member');
if($__membercfg['passport_login'] && $__membercfg['passport_list']) {
    foreach(explode(',',$__membercfg['passport_list']) as $__passport_name) {
        $_G['passport_apis'][$__membercfg['passport_'.$__passport_name.'_name']] = $__membercfg['passport_'.$__passport_name.'_title'];
    }
    unset($__passport_name);
}
unset($__membercfg);
?>