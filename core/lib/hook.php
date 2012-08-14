<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_hook extends ms_base {

    var $modules = array();

    function __construct() {
        parent::__construct();
    }

    function register() {
        foreach($this->global['modules'] as $m) {
            $hkfile =  MUDDER_MODULE . $m['flag'] . DS . 'inc' . DS . 'hook.php';
            if(is_file($hkfile)) {
                include $hkfile;
                $this->modules[] = $m['flag'];
            }
        }
    }

    function hook($fname, $params=null, $return=FALSE) {
        if($return) $result = array();
        foreach($this->modules as $m) {
            $classname = 'hook_' . $m;
            $hk = new $classname();
            if(method_exists($hk, $fname)) {
                if($return) {
                    $list = $params ? $hk->$fname($params) : $hk->$fname();
                    if($list[0]) {
                        foreach($list as $k => $val) array_push($result, $val);
                    } elseif($list) {
                        array_push($result, $list);
                    }
                } else {
                    if($params) $hk->$fname($params); 
                    else $hk->$fname();
                }
            }
            unset($hk);
        }
        if($return) return $result;
    }

}
?>