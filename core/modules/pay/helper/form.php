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

function form_pay_groups($select = '') {
	$loader =& _G('loader');

	$config = $loader->variable('config', 'member');
    $groups = $config['point_group'] ? unserialize($config['point_group']) : '';
    if(!$groups) return '';

	$paycfg = $loader->variable('config', 'pay');
	$cztype = $paycfg['cz_type'] ? unserialize($paycfg['cz_type']) : array();

	foreach($groups as $key => $val) {
        if($val['enabled'] && in_array($key, $cztype)) {
			$selected = $key == $select ? ' selected' : '';
			$ratio = is_numeric($paycfg['ratio_'.$key]) && $paycfg['ratio_'.$key] > 0 ? $paycfg['ratio_'.$key] : 1;
			$content .= "\t<option value=\"$key\" ratio=\"$ratio\" unit=\"$val[unit]\"$selected>$val[name]</option>\r\n";
		}
	}
	return $content;
}
?>