<?php
/**
* Modoer框架基类
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_base {

    var $errmsg = '';
    var $timestamp = 0;
    var $loader = null;
    var $global = null;
    var $in_admin = false;
    var $in_ajax = false;
    var $in_js = false;

    function __construct() {
        global $_G;
        $this->global =& $_G; //应用全局变量
        $this->timestamp = $_G['timestamp']; //当前时间
        $this->in_admin = defined('IN_ADMIN');
        $this->in_ajax = isset($_G['in_ajax']) && $_G['in_ajax'];
        $this->in_js = isset($_G['in_js']) && $_G['in_js'];
        $this->loader =& $_G['loader']; //loader载入类
    }

    function ms_base() {
        $this->__construct();
    }

}

