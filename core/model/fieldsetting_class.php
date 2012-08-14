<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fieldsetting extends ms_model {

    // 字段属性
    var $cfg = array();
    // 未更新前的字段
    var $old_cfg = array();
    // 数据库字段结构
    var $datatype = '';
    // 是否更新操作
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
        //编辑操作时，合并新旧数组，填充不能修改的参数
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

    // 后台显示类型配置界面
    function display($type, $cfg) {
    }

    function clear() {
        $this->datatype = '';
    }

    // 单行文本
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

    // 多行文本
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

    // 选项
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

    // 数字
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

    // 日期
    function _date() {
		$this->datatype = 'INT(10) UNSIGNED';
        $this->default = '0';
    }

	// 地区
	function _area() {
		$this->datatype = 'MEDIUMINT(8) UNSIGNED';
        $this->default = '0';
	}

}

?>