<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fieldvalidator extends ms_model {

    var $field = array();
    var $config = array();
    // ����֤����
    var $data = null;
	// ȫ������
	var $all_data = null;

    // ǰ̨Or��̨
    var $in_admin = false;
    // ����Or�༭
    var $is_edit = false;
    // ����¼�ύ������
    var $leave = false;

    function __construct() {
        parent::__construct();
          $this->in_admin = defined('IN_ADMIN');
          $this->is_edit = defined('EDIT_ID');
    }

    function msm_fieldvalidator() {
        $this->__construct();
    }
	
	function all_data($data) {
		$this->all_data = $data;
	}

    function validator($field, $data) {
        if(empty($field)) return false;
        //�༭״̬��
        if(!$this->_check_access($field) && $this->is_edit) {
            $this->leave = true;
            return false;
        } else {
            $this->leave = false;
        }

        $this->data = $data;
        $this->field = $field;
        $this->config = $field['config'];
        !is_array($this->config) && (array)$this->config;

        //����״̬��û��Ȩ�����ʱ������Ĭ��ֵ
        if(!$this->_check_access($field) && !$this->is_edit) {
            if($field['default']) {
                $this->data = $field['default'];
                $this->leave = false;
            } else {
                $this->leave = true;
                return false;
            }
        }

        // ������
		if($this->data && $this->field['regular'] && !preg_match($this->field['regular'], $this->data)) {
            redirect($this->field['errmsg']);
        }
        // ˽�м��
		$fun = '_' . $field['type'];
        $this->$fun();
		//�հ׼��
        if(!$this->field['allownull'] && $this->data === '' && !$field['isadminfield']) {
            redirect(lang('item_fieldvalidator_empty_field', $this->field['title']));
		}
        return $this->data;
    }

    function _text() {
        if($this->data=='') return;
        if($this->config['html']) {
            $this->data = _HTML($this->data);
        } else {
            $this->data = _T($this->data);
        }
        if($this->config['min'] && strlen($this->data) < $this->config['min']) {
            redirect(lang('item_fieldvalidator_text_len_limit', array($this->field['title'], lang('global_compare_min'), $this->config['min'])));
        } elseif($this->config['len'] && strlen($this->data) > $this->config['len']) {
            redirect(lang('item_fieldvalidator_text_len_limit', array($this->field['title'], lang('global_compare_max'), $this->config['len'])));
        }
    }

    function _textarea() {
        if(!$this->data) return;

        if($this->config['charnum_sup'] && strlen($this->data) < $this->config['charnum_sup']) {
            redirect(lang('item_fieldvalidator_text_len_limit',array($this->field['title'], lang('global_compare_min'), $this->config['charnum_sup'])));
        } elseif($this->config['charnum_sub'] && strlen($this->data) > $this->config['charnum_sub']) {
            redirect(lang('item_fieldvalidator_text_len_limit',array($this->field['title'], lang('global_compare_max'), $this->config['charnum_sub'])));
        }
        // ����HTML
        if($this->config['html']) {
            $this->data = _HTML($this->data);
            if($this->config['html'] == '1') $this->data = nl2br($this->data);
        } else {
            $this->data = _TA($this->data);
        }
    }

    function _option() {
        $value = array();
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $this->config['value']));
        foreach($list as $val) {
            $v = explode("=",$val);
            $value[] = $v[0];
        }
        $fun = "__" . $this->config['type'];
        $this->$fun($value);
    }

    function _numeric() {
        $this->data = trim($this->data);
        if(!is_numeric($this->data)) $this->data = 0;
        if(!is_numeric($this->data)) {
            redirect(lang('item_fieldvalidator_invalid_number',$this->field['title']));
        }
    }

    function _date() {
        if(!$this->data) {
            $this->data = 0;
        } else {
          if(!$this->data = strtotime($this->data)) {
               redirect(lang('global_op_value_invalid', $this->field['title']));
          }
        }
    }

    function _area() {
        $A =& $this->loader->model('area');
        if(!$city_id = $A->get_parent_aid($this->data, 1)) redirect(lang('global_op_value_invalid', $this->field['title']));
        $area = $this->loader->variable('area_' . $city_id);
        $this->data = (int) $this->data;
        if(!isset($area[$this->data])) {
            redirect(lang('global_op_value_invalid', $this->field['title']));
        }
    }

    function __select(&$list) {
        if($this->data) {
            if(!is_numeric($this->data)) {
                redirect(lang('item_fieldvalidator_invalid_item', $this->field['title']));
            }
            if(!in_array($this->data, $list)) {
                redirect(lang('item_fieldvalidator_option_exists', $this->field['title']));
            }
        } else {
            $this->data = 0;
        }
    }

    function __check(&$list) {
        if($this->data) {
            if(!is_array($this->data)) {
                redirect(lang('item_fieldvalidator_invalid_item', $this->field['title']));
            }
            foreach ($this->data as $key => $val) {
                if(!in_array($val, $list)) {
                    unset($this->data[$key]);
                }
            }
            if(!$this->data && !$this->field['allownull']) {
                redirect(lang('item_fieldvalidator_empty_field', $this->field['title']));
            }
            $this->data = implode(",", $this->data);
        } else {
            $this->data = '';
        }
    }

    function __radio(&$list) {
        $this->__select($list);
    }

    function _check_access(&$field) {
        // ����ǰ̨�༭ʱ���Ժ�̨�ֶεĲ���
        if($this->in_admin) return true;
        if(!$field['isadminfield']) return true;
        return false;
    }

}

?>