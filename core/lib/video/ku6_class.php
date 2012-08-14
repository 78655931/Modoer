<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_ku6 extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'gb2312';
        $this->preg = '<input\s?id="outSideSwfCode"\s?type="text".*value="(.*?)"';
        parent::__construct($url);
    }

    function get_url() {
        if($url = parent::get_url()) return $url;
        if(preg_match('%A\.href\s?=\s?"http://v\.ku6\.com/film/.*/(.*)\.html"%i', $this->html,$match)) {
            return "http://player.ku6.com/refer/$match[1]/v.swf";
        }
        return false;
    }
}
?>