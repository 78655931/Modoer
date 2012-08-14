<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldvalidator', FALSE);
class msm_product_fieldvalidator extends msm_fieldvalidator {

    function __construct() {
        parent::__construct();
		$this->model_flag = 'product';
    }

    function msm_product_fieldvalidator() {
        $this->__construct();
    }

    function _att() {
		if(!$this->data) {
            $this->data = '';
        } elseif(!$catid = $this->config['catid']) {
            $this->data = '';
        } else {
            $AD =& $this->loader->model('item:att_data');
            //检测标签并返回符合的标签组
            if($data = $AD->check_atts($catid, $this->data)) {
                $max = $this->config['len'] > 0 ? $this->config['len'] : 1;
                if(count(explode(',',$data)) > $max) redirect(lang('item_fieldvalidator_tag_len', array($this->field['title'], $max)));
                $this->data = $data;
            } else {
                $this->data = '';
            }
        }
    }

}

?>