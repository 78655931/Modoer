<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_tudou extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'gb2312';
        parent::__construct($url); 
    }

    function get_url() {
        if(preg_match("/\/([a|l][0-9]+)\.html$/i", $this->url, $match)) {
            return $this->_url_playlist($match[1]);
        } elseif(preg_match("/\/([a|l][0-9]+)i([0-9]+)\.html$/i", $this->url, $match)) {
            return $this->_url_playlist2($match[1],$match[2]);
        } else {
            return $this->_url_alone();
        }

    }

    function _url_alone() {
        if(preg_match("/,iid_code\s?=\s?icode\s?=\s?'(.*?)'/i", $this->html, $match)) {
             return "http://www.tudou.com/v/$match[1]/v.swf";
        } else {
            return false;
        }
    }

    function _url_playlist($lid) {
        if(preg_match("/,lid_code\s?=\s?lcode\s?=\s?'(.*?)'/i", $this->html, $match)) {
             $lcode = $match[1];
        } else {
            return false;
        }
        if(preg_match("/,defaultIid\s?=\s?([0-9]+)/i", $this->html, $match)) {
             $iid = $match[1];
        } else {
            return false;
        }
        return "http://www.tudou.com/l/$lcode/&iid=$iid/v.swf";
    }

    function _url_playlist2($lid,$iid) {
        if(preg_match("/,lid_code\s?=\s?lcode\s?=\s?'(.*?)'/i", $this->html, $match)) {
             return "http://www.tudou.com/l/$match[1]/&iid=$iid/v.swf";
        } else {
            return false;
        }
    }
}
?>