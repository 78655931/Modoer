<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
$_G['loader']->model(':sms', false);

class msm_sms_factory {

    private static $modcfg = null;
    public static function create($api=null) {
        if(!self::$modcfg) self::$modcfg = _G('loader')->variable('config','sms');
        $apiflag = $api ? $api : self::getApi();
        if($apiflag) {
            $classname = 'msm_sms_' . $apiflag;
            if(!class_exists($classname)) {
                $file = 'sms' . DS . 'api' . DS . $apiflag . '.php';
                if(!is_file(MUDDER_MODULE.$file)) show_error(lang('global_file_not_exist',$file));
                require_once MUDDER_MODULE.$file;
            }
            return new $classname(self::$modcfg);
        } else {
            return NULL;
        }
    }

    protected static function getApi() {
        $use_api = self::$modcfg['use_api'];
        if(empty($use_api)) $use_api = 'powereasy';
        return $use_api;
    }

}