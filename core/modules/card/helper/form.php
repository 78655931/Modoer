<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
function form_card_use_model($select = '') {
	$loader =& _G('loader');
    if(!$category = $loader->variable('category','item')) return '';
	$modcfg = $loader->variable('config','card');
    $ids = $modcfg['modelids'] ? unserialize($modcfg['modelids']) : '';
    if(!$ids) return '';
    foreach($category as $val) {
        if(in_array($val['modelid'],$ids)) {
            $list[$val['catid']] = $val['name'];
        }
    }
    $loader->helper('form');
	return form_option($list,$select);
}
?>