<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class validate {

    function is_email($str) {
        return strlen($str) > 5 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $str);
    }

    function is_date($str) {
        return preg_match("/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}$/", $str);
    }

    function is_datetime($str) {
        return preg_match("/^[0-9]{4}\-[0-9]{1,2}\-[0-9]{1,2}\s[0-9]{1,2}:[0-9]{1,2}(:[0-9]{1,2}|)$/", $str);
    }

    function is_numeric($str, $alow_zero = 1, $alow_minus = 1) {
        if(!is_numeric($str)) return false;
        if(!$alow_zero && !$str) return false;
        if(!$alow_minus && $str < 0) return false;
        return true;
    }

    function is_mobile($str) {
        $cfg = _G('loader')->variable('config','sms');
        $match = $cfg['preg_match'] ? $cfg['preg_match'] : '/^1[3|5|8]{1}[0-9]{9}$/';
        return preg_match($match, $str);
    }
}

?>