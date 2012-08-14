<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_input {

    var $_get = null;
    var $_post = null;
    var $_cookie = null;
    var $_files = null;
    var $_server = null;
    var $_env = null;

    function __construct() {
        $this->set_name();
    }

    function ms_input() {
        $this->__construct();
        if(PHP_VERSION < '4.1.0') {
            $this->_compatible();
        }
    }

    function _compatible() {
        if(PHP_VERSION < '4.1.0') {
            $_GET = $HTTP_GET_VARS;
            $_POST = $HTTP_POST_VARS;
            $_COOKIE = $HTTP_COOKIE_VARS;
            $_SERVER = $HTTP_SERVER_VARS;
            $_ENV = $HTTP_ENV_VARS;
            $_FILES = $HTTP_POST_FILES;
        }
        unset($HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_COOKIE_VARS, $HTTP_SERVER_VARS, $HTTP_ENV_VARS, $HTTP_POST_FILES);
    }

    function _clear_xss() {
    }

    function _clear_sql() {
        if(!defined('IN_ADMIN')) {
            $_POST = strip_sql($_POST);
            $_GET = strip_sql($_GET);
            $_COOKIE = strip_sql($_COOKIE);
        }
    }

    function _slashes() {
    }

}
?>