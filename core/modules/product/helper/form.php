<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_product_model($select = '') {
	$loader =& _G('loader');
	$model = $loader->variable('model', 'product', FALSE);
	if(!$model) return;
	foreach($model as $key => $val) {
		$selected = $key == $select ? ' selected' : '';
		$content .= "\t<option value=\"$key\"$selected>$val</option>\r\n";
	}
	return $content;
}

function form_product_category($sid, $catid=null) {
    $loader =& _G('loader');
    $C =& $loader->model('product:category');
    $content = '';
    if(!$r = $C->get_list($sid)) return '';
    foreach($r as $k => $v) {
        $selected = $catid > 0 && $v['catid']==$catid ? ' selected="selected"' : '';
        $content .= "<option value=\"$k\"$selected>$v[name]</option>\n";
    }
    return $content;
}
?>