<?php
/**
* @author Ðù<service@cmsky.org>
* @copyright (c)2009-2012 ·ç¸ñµêÆÌ
* @website www.cmsky.org
*/
function form_exchange_category($select = '') {
	$loader =& _G('loader');
    if(!$category =& $loader->variable('category', 'exchange')) return;
    $list = array();
	foreach($category as $val) {
        $list[$val['catid']] = $val['name'];
    }
    $loader->helper('form');
    return form_option($list,$select);
}
?>