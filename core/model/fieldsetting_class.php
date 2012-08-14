<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fieldsetting extends ms_model {

    // �ֶ�����
    var $cfg = array();
    // δ����ǰ���ֶ�
    var $old_cfg = array();
    // ���ݿ��ֶνṹ
    var $datatype = '';
    // �Ƿ���²���
    var $isedit = FALSE;

    function __construct() {
        parent::__construct();
    }

    function msm_fieldsetting() {
        $this->__construct();
    }

    function setting($type, $cfg) {
        if(!$type) return;
        $result = '';
        //�༭����ʱ���ϲ��¾����飬��䲻���޸ĵĲ���
        if($this->isedit) {
            $cfg = $this->old_cfg ? array_merge($this->old_cfg, $cfg) : $cfg;
        }
        if(empty($cfg)) {
            $cfg = array();
        } else {
            $this->cfg = $cfg;
            $fun = '_'.$type;
            $this->$fun();
            $result = $this->cfg;
        }
        return $result;
    }

    // ��̨��ʾ�������ý���
    function display($type, $cfg) {
    }

    function clear() {
        $this->datatype = '';
    }

    // �����ı�
    function _text() {
        $this->cfg['len'] = intval($this->cfg['len']);
        $this->cfg['size'] = intval($this->cfg['size']);
        empty($this->cfg['len']) && $this->cfg['len'] = 255;
        empty($this->cfg['size']) && $this->cfg['size'] = 20;
        if(strlen($this->cfg['default']) > 255) {
            redirect('admincp_field_text_default_charlen');
        } elseif($this->cfg['len'] > 255) {
            redirect('admincp_field_text_chatlen');
        }
        $this->datatype = 'VARCHAR(255)';
        $this->default = '';
    }

    // �����ı�
    function _textarea() {
        $this->cfg['height'] = trim($this->cfg['height']);
        $this->cfg['width'] = trim($this->cfg['width']);
        $this->cfg['charnum_sup'] = intval($this->cfg['charnum_sup']);
        $this->cfg['charnum_sub'] = intval($this->cfg['charnum_sub']);
        if(is_numeric($this->cfg['height'])) $this->cfg['height'] .= 'px';
        if(is_numeric($this->cfg['width'])) $this->cfg['width'] .= 'px';
        empty($this->cfg['height']) && $this->cfg['height'] = '80px';
        empty($this->cfg['width']) && $this->cfg['width'] = '98%';
        $this->datatype = 'MEDIUMTEXT';
        $this->default = null;
    }

    // ѡ��
    function _option() {
        if(!$this->cfg['value']) redirect('admincp_field_option_empty');
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $this->cfg['value']));
        if(!$list) redirect('admincp_field_option_empty');
        foreach($list as $val) {
            $v = explode("=", $val);
            if(count($v) > 2) redirect(lang('admincp_field_option_format', $val));
            if(!is_numeric($v[0])) redirect(lang('admincp_field_option_format2', $v[0]));
            if($v[0] < 1) redirect(lang('admincp_field_option_format3', $v[0]));
        }

        if($this->cfg['type'] == 'check') {
            $this->datatype = 'TEXT';
        } else {
            $this->datatype = 'TINYINT(3) UNSIGNED';
            $this->default = '0';
        }
    }

    // ����
    function _numeric() {
        $this->cfg['min'] = intval($this->cfg['min']);
        $this->cfg['max'] = intval($this->cfg['max']);
        //if($this->cfg['min']=='') redirect('admincp_field_num_empty_min');
        if(!$this->cfg['max']) redirect('admincp_field_num_empty_max');
        extract($this->cfg);
        if(!$point) {
            $this->datatype = 'INT(10)' . ($min > 0 ? ' UNSIGNED' : '');
        } else {
            $this->datatype = "DECIMAL(9,$point)";
        }
        $this->default = '0';
    }

    // ����
    function _date() {
		$this->datatype = 'INT(10) UNSIGNED';
        $this->default = '0';
    }

	// ����
	function _area() {
		$this->datatype = 'MEDIUMINT(8) UNSIGNED';
        $this->default = '0';
	}

}

?>