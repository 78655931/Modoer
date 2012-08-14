<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->lib('uri', NULL, 0);

class ms_router {
    
    /**
     * @var MS_URI
     */
    var $uri = '';
    var $action = '';
    var $op = '';

    function ms_router() {
        $this->__construct();
    }
    
    function __construct() {
        $this->uri = new ms_uri();
        $this->_set_router();
    }

    function _set_router() {
        $this->uri->parse_uri();
        if($this->uri->uri_string == '') {
            $this->action = 'index';
            $this->op = 'index';
            return;
        }
        $this->_parse_router();
    }

    function _parse_router() {
        $this->uri->explode_uri();
        $this->action = $this->uri->segment[0];
        $this->op = $this->uri->segment[1];
    }
}
?>