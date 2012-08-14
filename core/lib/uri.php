<?php
class ms_uri {
    
    var $uri_string = '';
    var $segment = array();

    function __construct() {
    }
    
    function ms_uri() {
        $this->__construct();
    }
    
    /**
     * ½âÎöURI
     * @access private
     */
    function parse_uri() {
        $path = (isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : @getenv('PATH_INFO');
        if (trim($path, '/') != '' && $path != "/".SELF) {
            $this->uri_string = $path;
        }
    }
    
    function explode_uri() {
        if ($this->uri_string == '') {
            return ;
        }
        foreach(explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->uri_string)) as $val) {
            $this->segment[] = $val;
        }
    }

}
?>