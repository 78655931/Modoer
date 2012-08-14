<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_comment_idtype($select = '') {
	$loader =& _G('loader');
    $CM =& $loader->model(':comment');
	if($CM->idtypes) foreach($CM->idtypes as $key => $val) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val[name]</option>\r\n";
	}
	return $content;
}
?>