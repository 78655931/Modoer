<?php
/**
* RSS¾ÛºÏ
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');
class ms_rss {

    var $item = '';

    var $use_head = true;
    var $title = 'RSS';
    var $description = 'Modoer RSS Creator';
    var $link = '';
    var $copyright  = '';

    function __construct() {
        $this->link = _G('cfg','site_url');
        $this->coryright = lang('global_rss_coryright');
    }
    
    function ms_rss() {
        $this->__construct();
    }

    function add_item($title,$link,$author,$description,$pubDate) {
        $this->item[] =array(
            'title' => $title,
            'link' => $link,
            'author' => $author,
            'description' => $description,
            'pubDate' => $pubDate,
        );
    }

    function create_xml() {
        $charset = _G('charset');
        $xml = $this->use_head ? ('<?xml version="1.0" encoding="'.$charset.'"?>'."\r\n") : '';
        $xml.="<rss version=\"2.0\">\r\n";
        $xml.="<channel>\r\n";
        $xml.="<title>$this->title</title>\r\n";
        $xml.="<description>$this->description</description>\r\n";
        $xml.="<link>$this->link</link>\r\n";
        $xml.="<copyright>$this->copyright</copyright>\r\n";
        $xml.="<docs>http://backend.userland.com/rss</docs>\r\n";
        if($this->item) foreach($this->item as $data){
            $xml.="<item>\r\n";
            $xml.="<title>$data[title]</title>\r\n";
            $xml.="<link>$data[link]</link>\r\n";
            $xml.="<author>$data[author]</author>\r\n";
            $xml.="<description>\r\n";
            $xml.="<![CDATA[ \r\n";
            $xml.="$data[description]";
            $xml.="]]> \r\n";
            $xml.="</description>\r\n";
            $xml.="<pubDate>".date('Y-m-d H:i:s',$data['pubDate'])."</pubDate>\r\n";
            $xml.="</item>\r\n";
        }
        $xml.="</channel>\r\n";
        $xml.="</rss>\r\n";
        return $xml;
    }
}
?>