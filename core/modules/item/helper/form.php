<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

function form_item_category($select = '') {
	$loader =& _G('loader');
	$category = $loader->variable('category', 'item');
	$level1 = $level2 = '';
	foreach($category as $key => $val) {
		if(!$val['pid']) {
			$level1[] = $key;
		} else {
			$level2[$val['pid']][] = $key;
		}
	}
	foreach($level1 as $key) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>{$category[$key][name]}</option>\r\n";
		if($level2[$key]) foreach($level2[$key] as $_key) {
            if(!$val['enabled']) continue;
			$selected = $_key == $select ? ' selected' : '';
			$content .= "\t<option value=\"$_key\"$selected>&nbsp;©¸-&nbsp;{$category[$_key][name]}</option>\r\n";
		}
	}
	return $content;
}

function form_item_category_main($select = '') {
	$loader =& _G('loader');
	if(!$category = $loader->variable('category', 'item', FALSE)) {
		return '';
	}
	foreach($category as $key => $val) {
		if($val['pid'] != 0||!$val['enabled']) continue;
        !isset($val['config']['enable_add']) && $val['config']['enable_add'] = 1;
        if(!$val['config']['enable_add'] && defined("ITEM_SUBJECT_ADD") && !defined("IN_ADMIN")) continue;
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>{$val[name]}</option>\r\n";
	}
	return $content;
}

function form_item_category_equal_model($pid, $select = '') {
	$loader =& _G('loader');
	if(!$category = $loader->variable('category', 'item', FALSE)) {
		return '';
	}
    if(!$my = $category[$pid]) return '';
	foreach($category as $key => $val) {
		if($val['pid'] != 0 ) continue;
        if($val['modelid'] != $my['modelid']) continue;
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>{$val[name]}</option>\r\n";
        $content .= form_item_category_sub($val['catid'], '', '&nbsp;©¸&nbsp;');
	}
	return $content;
}

function form_item_category_sub($pid, $select = '', $name_exp = '') {
	$loader =& _G('loader');
    $C = $loader->model('item:category');
    $rootid = $C->get_parent_id($pid, 1);
	if(!$category = $loader->variable('category_' . $rootid, 'item', FALSE)) {
		return '';
	}
    $cate = $loader->variable('category','item',FALSE);
    $content = '';
    if(($category[$pid]['level']=='1' && $cate[$rootid]['config']['relate_root']) || !$cate) {
        $content .= "\t<option value=\"0\">=".lang('global_unrestrained')."=</option>\r\n";
    }
    if(!$select) $select = array();
    if(!is_array($select)) $select = array($select);
	foreach($category as $key => $val) {
		if($val['pid'] != $pid) continue;
        if(!$val['enabled']) continue;
		$selected = in_array($key,$select) ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>{$name_exp}{$val[name]}</option>\r\n";
	}
	return $content;
}

function form_item_level($pid, $select = '') {
}

function form_item_mysubject($uid, $sid = null) {
    $loader =& _G('loader');
    $S =& $loader->model('item:subject');
    $content = '';
    $S->db->join('dbpre_mysubject','m.sid',$S->table,'s.sid');
    $S->db->where('m.uid', $uid);
    $S->db->select('m.sid,pid,catid,name,subname');
    $S->db->order_by('reviews','DESC');
    if(!$query = $S->db->get()) return $content;
    $sids = !is_array($sid) ? array($sid) : $sid;
    while($val = $query->fetch_array()) {
		$selected = in_array($val['sid'],$sids) ? ' selected="true"' : '';
		$content .= "\t<option value=\"$val[sid]\"$selected>$val[name]  $val[subname]</option>\r\n";
    }
    return $content;
}

function form_item_models($select='') {
	$loader =& _G('loader');
	$model = $loader->variable('model', 'item');
	foreach($model as $key => $val) {
		$selected = $key == $select ? ' selected="selected"' : '';
		$content .= "\t<option value=\"$key\"$selected>$val</option>\r\n";
	}
	return $content;
}

function form_item_taggroup($select='') {
    $loader =& _G('loader');
    if(!$groups = $loader->variable('taggroup','item')) return '';
    $content = '';
	foreach($groups as $key => $val) {
		$selected = $val['tgid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"{$val['tgid']}\"$selected>$val[name]($val[des])</option>\r\n";
	}
	return $content;
}

function form_item_attcat($select='') {
    $loader =& _G('loader');
    if(!$cats = $loader->variable('att_cat','item')) return '';
    $content = '';
	foreach($cats as $key => $val) {
		$selected = $val['catid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"{$val['catid']}\"$selected>$val[name]($val[des])</option>\r\n";
	}
	return $content;
}

function form_item_alubm($sid, $select='') {
    $db =& _G('db');
	$db->from('dbpre_album');
	$db->where('sid',$sid);
	if(!$q = $db->get()) {
		$content = '<option value="0">'.lang('item_album_normal').'</option>';
		return $content;
	}
    $content = '';
	while($v = $q->fetch_array()) {
		$selected = $v['albumid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"{$v['albumid']}\"$selected>$v[name]</option>\r\n";
	}
	$q->free_result();
	return $content;
}

function form_item_category_main_check($name,$select = '',$split='&nbsp;') {
	$loader =& _G('loader');
	if(!$category = $loader->variable('category', 'item', FALSE)) {
		return '';
	}
	foreach($category as $key => $val) {
		if($val['pid'] != 0||!$val['enabled']) continue;
		$selected = $select && in_array($key, $select) ? ' checked="checked"' : '';
        $content .= "\t<input type=\"checkbox\" name=\"$name\" value=\"$key\"$selected>{$val[name]}\r\n";
	}
	return $content;
}

function form_item_fields($modelid, $select='', $type=null) {
	$loader =& _G('loader');
	$fields = $loader->variable('field_'.$modelid, 'item');
	if(!$fields) return '';
	$content = '';
	if($type && is_string($type)) $type = explode(',', $type);
	foreach($fields as $key => $val) {
		if($type && !in_array($val['type'], $type)) continue;
		$selected = $val['fieldid'] == $select ? ' selected="selected"' : '';
		$content .= "\t<option value=\"$val[fieldid]\" fieldname=\"$val[fieldname]\"$selected>{$val[title]} [{$val[fieldname]}]</option>\r\n";
	}
	return $content;
}

function form_item_category_icons($select='') {
    $Dis = new DirectoryIterator(MUDDER_ROOT . 'static'.DS.'images'.DS.'category');
    if(empty($Dis)) return '1';
    $filenames = array();
    foreach($Dis as $fileinfo) {
        if ($fileinfo->isFile()) {
            $ext = strtolower(pathinfo($fileinfo->getFileName(), PATHINFO_EXTENSION));
            if(!in_array($ext, array('png','gif','jpg','jpeg'))) continue;
            $filenames[$fileinfo->getFilename()] = $fileinfo->getFilename();
        }
    }
    if(empty($filenames)) return '2';
    $content = '';
	foreach($filenames as $filename) {
		$selected = $filename == $select ? ' selected' : '';
		$content .= "\t<option value=\"$filename\"$selected>$filename</option>\r\n";
	}
	return $content;
}
?>