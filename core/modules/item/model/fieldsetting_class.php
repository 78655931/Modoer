<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldsetting', FALSE);
class msm_item_fieldsetting extends msm_fieldsetting {

	function __construct() {
		parent::__construct();
	}

    function msm_item_fieldsetting() {
		$this->__construct();
	}

	// ����
	function _category() {
		$this->datatype = 'smallint(5)';
        $this->default = '0';
	}

	// ��ǩ
	function _tag() {
		$this->datatype = 'varchar(255)';
        $this->default = '';
	}

	// ��Ա
	function _member() {
		$this->datatype = 'varchar(25)';
        $this->default = '';
	}

	// ״̬
	function _status() {
		$this->datatype = 'tinyint(1)';
        $this->default = '0';
	}

	// �ȼ�
	function _level() {
		$this->datatype = 'tinyint(3)';
        $this->default = '0';
	}

	// ��ͼ
	function _mappoint() {
		$this->datatype = 'varchar(255)';
        $this->default = '';
	}

	// ģ��
	function _template() {
		$this->datatype = 'smallint(5)';
        $this->default = '0';
	}

    // ��Ƶ
    function _video() {
        $this->datatype = 'varchar(255)';
        $this->default = '';
    }

	// ����
	function _att() {
		$this->datatype = 'varchar(255)';
        $this->default = '';
	}

	// ����
	function _subject() {
        if(!$this->cfg['categorys']) redirect('item_field_category_empty');
        $this->cfg['categorys'] = is_array($this->cfg['categorys']) ? implode(',',$this->cfg['categorys']) : $this->cfg['categorys'];
		$this->datatype = 'text';
        $this->default = '';
	}

	//�Ա��Ͳ�Ʒ
	function _taoke_product() {
		if(!$this->cfg['nick_field']&&empty($this->cfg['q_type'])) redirect('item_field_keyword_empty');
		if($this->cfg['q_type']=='q_field'&&empty($this->cfg['q_field'])) redirect('item_field_q_field_empty');
		$this->datatype = 'varchar(60)';
        $this->default = '';
	}
}

?>