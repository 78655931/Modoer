<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldsetting', FALSE);
class msm_product_fieldsetting extends msm_fieldsetting {

	function __construct() {
		parent::__construct();
        $this->model_flag = 'product';
	}

    function msm_product_fieldsetting() {
		$this->__construct();
	}

	// Ъєад
	function _att() {
		$this->datatype = 'varchar(255)';
        $this->default = '';
	}

}

?>