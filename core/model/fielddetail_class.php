<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_fielddetail extends ms_model {

    // 字段属性
    var $field = array();
    var $config = array();
	// 全部数据
	var $all_data = null;
    var $pagemod = 'detail';
    var $format = "";
    var $style = "";
    var $width = "*";
    var $class = "";
    var $align = "right";
    var $td_num = 2;

    var $note_width = "150";
	
	var $value = "";
	var $empty_exception_types = array();

    function __construct() {
        parent::__construct();
    }

    function msm_fielddetail() {
        $this->__construct();
    }

	function all_data($data) {
		$this->all_data = $data;
	}

    function style() {
        $style = '';
        if($this->width) $style .= " width='$this->width'";
        if($this->class) $style .= " class='$this->class'";
        if($this->align) $style .= " align='$this->align'";
        $this->style = $style;

        $this->format = "<tr>\r\n";
        $this->format .= "\t<td $this->style>%s：" . ($this->td_num > 1 ? "</td>\r\n" : "");
        $this->format .= ($this->td_num > 1 ? "\t<td width=\"*\">" : "")."%s</td>\r\n";
        $this->format .= "</tr>\r\n";
    }
	
	function exception_add($type) {
		if(is_array($type)) {
			$this->empty_exception_types = array_merge($this->empty_exception_types,$type);
		} else {
			$this->empty_exception_types[] = $type;
		}
		array_unique($this->empty_exception_types);
	}

    function detail($field, $val=null) {
        $content = '';
        if(empty($field)) return $content;
        $this->style();
        $this->field = $field;
        $this->config = $field['config'];
        !is_array($this->config) && (array)$this->config;
        $fun = '_'.$field['type'];
        if(empty($val) && !in_array($field['type'], $this->empty_exception_types)) {
            return;
        }
		$this->value = $val;
        if(!$fun && !$val) return;
        return $this->$fun($val);
    }

    function __template_parse(& $val) {
        $re = array('{value}','{urlcode:value}','{city_name}');
        $va = array($val, rawurlencode($val), $this->global['city']['name']);
        $str =  str_replace($re,$va,$this->field['template']);
		$str = preg_replace_callback("/\{display:([a-z]+):([a-z0-9A-Z\_]+)\}/i",
			array(&$this, '__template_parse_func'),
			$str);
		/*
		if(preg_match_all("/\{display:([a-z]+):([a-z0-9A-Z\_]+)\}/i", $str, $match)) {
			$params = array('value'=>$val,'config'=>$this->config,'field'=>$this->field);
			$module = $match[1][0];
			$func = $match[2][0];
			foreach($match as $k => $v) {
				if(!$k) continue;
				vp($match[$k][0]);
			}
			
			$module = $match[0][1];
			$func = $match[0][2];vp($match);
			$this->loader->helper('display',$module);
			$class = 'display_' . $module;
			$result = call_user_func(array($class, $func), $params);
			preg_replace("/display:$module:$func/i", $result, $str);
		}
		*/
		return $str;
    }
	
	function __template_parse_func($match) {
		$params = array('value'=>$this->value,'config'=>$this->config,'field'=>$this->field);
		$class = 'display_' . $match[1];
		$func = $match[2];
		$this->loader->helper('display', $match[1]);
		$result = call_user_func(array($class, $func), $params);
		return $result;
	}

    function _text($val) {
        $text = $val . ($this->field['unit'] ? "&nbsp;{$this->field['unit']}" : '');
        if($this->field['template']) {
            $text = $this->__template_parse($text);
        }
        return sprintf($this->format, $this->field['title'], $text);
    }

    function _numeric($val) {
        return $this->_text($val);
    }

    function _textarea($val) {
        return $this->_text($val);
    }

    function _option($val) {
        $fun = '__' . $this->config['type'];
        if(!method_exists($this, $fun)) return;
        $val = $this->$fun($val);
        return $this->_text($val);
    }

    function _date($val) {
        return $this->_text(newdate($val,$this->config['format']));
    }

    function _area($val) {
        $A =& $this->loader->model('area');
        if(!$city_id = $A->get_parent_aid($val,1)) return;
        if(!$area = $this->loader->variable('area_' . $city_id)) return;
        // 载入地区
        if($area[$val]['level'] == 2) {
            $paid = 0;
        } else {
            $paid = $area[$val]['pid'];
        }
        $urlpath = array();
        if($paid) {
            $urlpath[] = url_path($area[$paid]['name'], url('item/list/catid/' . SUBJECT_CATID . '/aid/'.$paid));
        }
        if($paid != $val) {
            $urlpath[] = url_path($area[$val]['name'], url('item/list/catid/' . SUBJECT_CATID . '/aid/'.$val));
        }
        return sprintf($this->format, $this->field['title'], implode('&nbsp;&gt;&nbsp;', $urlpath));
    }

    function __select($val) {
        $option = "";
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $this->config['value']));
        foreach($list as $sval) {
            $v = explode("=",$sval);
            if($v[0] == $val) {
                $option = $v[1];
            }
        }
        if(!$option) $option = "N/A";
        return $option;
    }

    function __check($val) {
        if($val) $val = explode(",", $val);
        $option = "";
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $this->config['value']));
        !$this->config['display_split'] && $this->config['display_split'] = '&nbsp;';
        if($this->config['display_split'])
        foreach($list as $sval) {
            $v = explode("=",$sval);
            if($val && in_array($v[0], $val)) {
                $option .= $split . $v[1];
                $split = $this->config['display_split'];
            }
        }
        if(!$option) $option = "N/A";
        return $option;
    }

    function __radio($val) {
        return $this->__select($val);
    }
}

?>