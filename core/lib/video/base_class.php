<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/

class ms_video_base {

    var $system_charset = 'gb2312';

    var $url = '';
    var $charset = 'gb2312';
    var $preg = '';

    function __construct($url) {
        $this->url = $url;
        $this->_get_html();
    }

    function _get_html() {
        if($this->html = get_url_content($this->url)) {
            if($this->system_charset != $this->charset) {
                if(!class_exists('ms_chinese')) {
                    include MUDDER_CORE . 'lib' . DS . 'chinese.php';
                }
                $CHS = new ms_chinese($this->charset, $this->system_charset);
                $this->html = $CHS->Convert($this->html);
            }
        }
    }

    function get_url() {
        if(!$this->preg) return;
        if(preg_match('%'.$this->preg.'%i', $this->html, $match)) { 
            return $match[1];
        } else {
            return false;
        }
    }

}
?>