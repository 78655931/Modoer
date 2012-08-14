<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_video_6room extends ms_video_base  {

    function __construct($url) {
        $this->charset = 'utf-8';
        $this->preg = 'embed\ssrc=&quot;(.*?\.swf)';
        parent::__construct($url);
    }
}
?>