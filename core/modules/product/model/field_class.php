<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('field', FALSE);
class msm_product_field extends msm_field {

    function __construct() {
        $this->fielddir = MOD_ROOT . 'model' . DS . 'fields';
        parent::__construct();
        $this->model_flag = 'product';
    }

    function msm_product_field() {
        $this->__construct();
    }

    function & field_list($modelid, $p_cfg = FALSE) {
        return parent::field_list('product', $modelid, $p_cfg);
    }

    function write_cache($modelid = null) {
        if($modelid > 0) {
            $this->_write_cache('product', $modelid);
            return;
        }
        $this->db->from('dbpre_product_model');
        if(!$row = $this->db->get()) return;
        while($value = $row->fetch_array()) {
            $this->_write_cache('product', $value['modelid'], $this->model_flag);
        }
    }
}
?>