<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_review_opt_group($select='') {
    $loader =& _G('loader');
    if(!$cats = $loader->variable('opt_group','review',0)) return '';
    $content = '';
	foreach($cats as $key => $val) {
		$selected = $val['gid'] == $select ? ' selected' : '';
		$content .= "\t<option value=\"{$val['gid']}\"$selected>$val[name]($val[des])</option>\r\n";
	}
	return $content;
}

function form_review_idtype($select = '') {
	$loader =& _G('loader');
    $R =& $loader->model(':review');
	if($R->idtypes) foreach($R->idtypes as $key => $val) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val[name]</option>\r\n";
	}
	return $content;
}
?>