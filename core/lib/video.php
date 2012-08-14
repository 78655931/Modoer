<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_video {

    var $sites = array();
    var $charset = 'gb2312';
    var $video = null;
    var $url = '';
    var $html = '';

    function __construct() {
        $this->sites = include MUDDER_CORE . 'lib' . DS . 'video' . DS . 'video_inc.php';
    }

    function get_url($url) {
        $this->url = $url;
        $this->_get_video_object();
        if(!$this->video) return false;
        return $this->video->get_url();
    }

    function _get_video_object() {
        include_once MUDDER_CORE . 'lib' . DS . 'video' . DS . 'base_class.php';
        foreach($this->sites as $site => $domain) {
            if(strposex($this->url, $domain)) {
                $classname = 'ms_video_' . $site;
                if(!class_exists($classname)) include MUDDER_CORE . 'lib' . DS . 'video' . DS . $site . '_class.php';
                $this->video = new $classname($this->url);
                $this->video->system_charset = $this->charset;
                break;
            }
        }
    }
}
?>