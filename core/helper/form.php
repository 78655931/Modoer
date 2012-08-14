<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_convert_extra(&$extra) {
    $content = $split = '';
    if(!is_array($extra)) return $extra;
    foreach($extra as $key => $val) {
        $content .= $split . $key . '="' . $val .'"';
    }
    $content .= ' ';
    return $content;
}

function form_get_id($name) {
	return str_replace(array('[',']'),array('_',''),$name);
}

function form_class($class) {
    if($class) {
        return ' class="'.$class.'" ';
    }
}

function form_begin($action='', $method='post', $name='myform', $enctype='', $onsubmit='', $class="", $extra='') {
    $content = "<form name=\"$name\" action=\"$action\" method=\"$method\"";
    if($enctype) $content .= " enctype=\"$enctype\"";
    if($onsubmit) $content .= " onsubmit=\"$onsubmit\"";
    $content .= form_class($class);
    if($extra) $content .= " " . form_convert_extra($extra);
    $content .= ">";
    return $content;
}

function form_end() {
    return "</form>";
}

function form_hidden($name, $value, $extra=array()) {
    return "<input type=\"hidden\" name=\"$name\" id=\"hidden_".form_get_id($name)."\" value=\"$value\"".form_convert_extra($extra)." />";
}

function form_input($name, $value, $class='', $extra='') {
    return "<input type=\"text\"" . form_class($class) . " name=\"$name\" id=\"input_".form_get_id($name)."\" value=\"$value\"".form_convert_extra($extra)." />";
}

function form_password($name, $value, $class='', $extra='') {
    return "<input type=\"password\"" . form_class($class) . " name=\"$name\" id=\"input_".form_get_id($name)."\" value=\"$value\"".form_convert_extra($extra)." />";
}

function form_datetime($name, $value, $format='Y-m-d', $class='', $extra='') {
    $value = $value ? date($format,$value) : '';
    return "<input type=\"text\"" . form_class($class) . " name=\"$name\" id=\"input_".form_get_id($name)."\" value=\"$value\"".form_convert_extra($extra)." />";
}

function form_textarea($name, $value, $rows=5, $cols=50, $class='', $extra='') {
    return "<textarea name=\"$name\" rows=\"$rows\" id=\"textarea_".form_get_id($name)."\" cols=\"$cols\"" . form_class($class) . form_convert_extra($extra).">$value</textarea>";
}

function form_submit($name, $caption, $value, $class='', $extra='') {
    return "<button type=\"submit\" name=\"$name\" value=\"$value\" id=\"submit_".form_get_id($name)."\"" . form_class($class) . form_convert_extra($extra).">$caption</button>";
}

function form_reset($name, $caption, $value, $class, $extra='') {
    return "<button type=\"reset\" name=\"$name\" value=\"$value\" id=\"reset_$name\"" . form_class($class) . form_convert_extra($extra).">$caption</button>";
}

function form_button($name, $caption, $value, $onclick, $class, $extra='') {
    return "<button type=\"button\" name=\"$name\" value=\"$value\" id=\"reset_$name\" onclick=\"$onclick\"" . form_class($class) . form_convert_extra($extra).">$caption</button>";
}

function form_image($src, $class='', $extra='') {
    return "<input type=\"image\" src=\"$src\"" . form_class($class) . form_convert_extra($extra) . " />";
}

function form_radio($name, $values, $checked='', $extra='', $split='&nbsp;') {
    $content = $splitx = '';
    foreach($values as $key => $val) {
        $content .= $splitx . sprintf("<input type=\"radio\" name=\"%s\" value=\"%s\" id=\"%s\" %s %s /><label for=\"%s\">%s</label>", 
            $name, $key, ('radio_'.$name.'_'.$key),
            ($key == $checked ? ' checked="checked"' : ''), 
            form_convert_extra($extra),
            ('radio_'.$name.'_'.$key),
            $val);
        $splitx = $split;
    }
    return $content;
}

function form_bool($name, $checked, $extra='', $split='&nbsp;') {
    $values = array('1'=>lang('global_yes'), '0'=>lang('global_no'));
    return form_radio($name, $values, $checked, $extra, $split);
}

function form_check($name, $values, $checkeds=array(), $extra='', $split='<br />') {
    $content = $splitx = '';
    foreach($values as $key => $val) {
        $content .= $splitx . sprintf("<input type=\"checkbox\" name=\"%s\" value=\"%s\" id=\"%s\" %s %s /><label for=\"%s\">%s</label>", 
            $name, $key, ('checkbox_'.$name.'_'.$key),
            (in_array($key, $checkeds) ? ' checked="checked"' : ''), 
            form_convert_extra($extra),
            ('checkbox_'.$name.'_'.$key),
            $val);
        $splitx = $split;
    }
    return $content;
}

function form_bool_check($name, $checked, $extra='') {
    $selected = $checked ? ' checked="checked"' : '';
    $extra = form_convert_extra($extra);
    $content = "<input type=\"checkbox\" name=\"$name\" value=\"1\" id=\"".('checkbox_'.form_get_id($name))."\"$selected $extra />"; 
    return $content;
}

function form_select($name, $values, $selecteds=null, $size=0, $class='', $extra='') {
    $size = $size ? "size=\"$size\"" : '';
    $content = "<select name=\"$name\" id=\"$name\" $size" . form_class($class) . form_convert_extra($extra).">";
    foreach($values as $key => $val) {
        $selected = $selecteds ? ((is_array($selecteds) ? isset($selecteds[$key]) : $selecteds == $key) ? 'selected="selected"' : '') : '';
        $content .= "\t<option value=\"$key\"$selected>$val</option>\r\n";
    }
    $content .= "</select>";
    return $content;
}

function form_select2($name, $values, $vkey, $selecteds=null, $size=0, $extra='') {
    $size = $size ? "size=\"$size\"" : '';
    $content = "<select name=\"$name\" id=\"$name\" $size" . form_class($class) . form_convert_extra($extra).">";
    foreach($values as $val) {
        $k = $val[$vkey[0]];
        $v = $val[$vkey[1]];
        $selected = $selecteds ? ((is_array($selecteds) ? isset($selecteds[$key]) : $selecteds == $key) ? 'selected="selected"' : '') : '';
        $content .= "\t<option value=\"$k\"$selected>$v</option>\r\n";
    }
    $content .= "</select>";
    return $content;
}

function form_option($list, $select) {
    $content = '';
    if($list) foreach($list as $k => $v) {
        $selected = (isset($select) && $select !='' && $k == $select) ? ' selected="selected"' : '';
        $content .= "\t<option value=\"$k\"$selected>$v</option>\r\n";
    }
    return $content;
}

function form_button_return($caption, $class='btn', $extra='') {
    return "<button type=\"button\" onclick=\"location='javascript:history.go(-1);'\"" . form_class($class) . form_convert_extra($extra).">$caption</button>";
}

function form_area($city_id='', $select='', $extra='') {
	global $_G;
	$city_id = $city_id ? $city_id : $_G['city']['aid'];
	$loader =& _G('loader');
	$area = $loader->variable('area_'.$city_id,'',false);
	$level2 = $level3 = '';
	if($area) foreach($area as $key => $val) {
		if($val['level']==2) $level2[] = $key;
		if($val['level']==3) $level3[$val['pid']][] = $key;
	}
	if($level2) foreach($level2 as $key) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>{$area[$key][name]}</option>\r\n";
		if($level3[$key]) foreach($level3[$key] as $_key) {
			$selected = $_key == $select ? ' selected' : '';
			$content .= "\t<option value=\"$_key\"$selected>&nbsp;©»&nbsp;{$area[$_key][name]}</option>\r\n";
		}
	}
	return $content;
}

function form_template($type='main', $select='') {
	$loader =& _G('loader');
	$templates = $loader->variable('templates');
	if($templates[$type]) foreach($templates[$type] as $val) {
		$selected = $val['templateid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"$val[templateid]\"$selected>{$val[name]}</option>\r\n";
	}
	return $content;
}

function form_menu_main($select='') {
	$loader =& _G('loader');
	$menus = $loader->variable('menus');
	if($menus) foreach($menus as $val) {
		if($val['parentid'] > 0) continue;
		$selected = $val['menuid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"$val[menuid]\"$selected>{$val[title]}</option>\r\n";
	}
	return $content;
}

function form_datacall_template_files($templateid,$select='') {
    $loader =& _G('loader');
    $templates = $loader->variable('templates');
    if(!isset($templates['main'][$templateid])) return '';
    $root_dir = MUDDER_ROOT . 'templates' . DS . 'main' . DS . $templates['main'][$templateid]['directory'] . DS . 'datacall';
    $ext = _G('cfg','tplext');
    !$ext && $ext = '.htm';
    $content = '';
    $des = read_cache($root_dir . DS . 'template.php');
    if(is_dir($root_dir)) {
        if($dh = opendir($root_dir)) {
            while(($file = readdir($dh)) !== false) {
                if($file == '.'||$file=='..') continue;
                if('.'.strtolower(pathinfo($file,PATHINFO_EXTENSION)) != strtolower($ext)) continue;
                $name = basename($file,$ext);
                $selected = $name == $select ? ' selected' : '';
                $content .= "\t<option value=\"$name\"$selected>$name".($des[$file]?" ($des[$file])":'')."</option>\r\n";
            }
            closedir($dh);
        }
    }
    return $content;
}

function form_module($select='',$use_flag=TRUE) {
	$loader =& _G('loader');
	$modules = $loader->variable('modules');
	foreach($modules as $val) {
        $key = $use_flag ? $val['flag'] : $val['moduleid'];
        $selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val[name]</option>\r\n";
	}
	return $content;
}

function form_module_index($select='') {
    $loader =& _G('loader');
    $modules = $loader->variable('modules');
    $select_arr = array();
    foreach($modules as $val) {
        if(in_array($val['flag'], array('sms','ucenter','adv','pay'))) continue;
        $pages = array();
        $key = $val['flag'];
        $select_arr[$key] = $val['name'];
        $file = MUDDER_ROOT . 'core/modules/' . $val['flag']. '/inc/index_hook.php';
        if(is_file($file)) $pages = read_cache($file);
        if($pages) {
            foreach ($pages as $_key => $_name) {
                $select_arr[$key.'/'.$_key] = "   ¨N " . $_name;
            }
        }
    }
    $loader->helper('form');
    return form_option($select_arr,$select);
}

function form_bcastr_group($select='') {
    $loader =& _G('loader');
    $B =& $loader->model('bcastr');
    if(!$groups = $B->group_list()) return;
    foreach(array_keys($groups) as $val) {
        $select_arr[$val] = $val;
    }
    $loader->helper('form');
    return form_option($select_arr,$select);
}

function form_city($select = null, $use_global = FALSE, $filter_citys = FALSE) {
	$loader =& _G('loader');
	$citys = $loader->variable('area');
	$select_arr = array();
    if($use_global && !$filter_citys) {
        $select_arr[0] = lang('global_global');
    }
	foreach($citys as $val) {
		if($filter_citys) {
			if (_G('city','aid') != $val['aid']) continue;
		}
		$select_arr[$val['aid']] = $val['name'];
	}
	return form_option($select_arr, $select);
}
?>