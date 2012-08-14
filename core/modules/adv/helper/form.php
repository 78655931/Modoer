<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_adv_sort($select = '') {
	$loader =& _G('loader');
	$sorts = lang('adv_sort');
	if($sorts) foreach($sorts as $key => $val) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val</option>\r\n";
	}
	return $content;
}

function form_adv_place($select) {
	$loader =& _G('loader');
	$places = $loader->variable('place','adv',false);
	if($places) foreach($places as $key => $val) {
		$selected = $val['apid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"$val[apid]\"$selected>$val[name]</option>\r\n";
	}
	return $content;
}
?>