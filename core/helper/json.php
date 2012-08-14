<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!function_exists('json_encode')) {
    function json_encode($value) {
        static $instance = array();
        if (!isset($instance[0])) {
            require_once(MUDDER_CORE . 'json.php');
            $instance[0] =& new Services_JSON();
        }
        return $instance[0]->encode($value);
    }
}

if(!function_exists('json_decode')) {
    function json_decode($jsonString) {
        static $instance = array();
        if (!isset($instance[0])) {
            require_once(MUDDER_CORE . 'json.php');
            $instance[0] =& new Services_JSON();
        }
        return $instance[0]->decode($jsonString);
    }
}
?>