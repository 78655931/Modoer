<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_hook extends ms_model {

	var $table = 'dbpre_hook';
    var $key = 'hookid';

    function __construct() {
        parent::__construct();
    }

    function msm_hook() {
		$this->__construct();
    }

    function check_post(& $post, $edit = FALSE) {
    }

    function write_cache() { 
    }
}
?>