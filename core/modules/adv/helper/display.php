<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_adv {

    //取得分类的名称或其它
    //参数 catid,keyname
    function category($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$catid) return '';
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        if(!isset($category[$catid][$keyname])) return '';
        return $category[$catid][$keyname];
    }

	function show($params) {
		extract($params);
		if(!$name) return lang('adv_show_name_empty');
		$loader =& _G('loader');
		$places = $loader->variable('place','adv');
		if(!$places) return ;//lang('adv_show_place_empty');
		$place = null;
		foreach($places as $val) {
			if($val['name'] == $name) {
				$place = $val;
				break;
			}
		}
		if(!$place) return ;//lang('adv_show_place_empty');
		
		$cachedir = 'data' . DS . 'templates' . DS;
		$name = 'block_adv_'.$place['apid'].'.htm';
		$tplfile = 'data' . DS . 'block' . DS . $name;
		$objfile = $cachedir . $name . '.tpl.php';

		if(!is_file(MUDDER_ROOT . $tplfile) || (@filemtime(MUDDER_ROOT . $tplfile) > @filemtime(MUDDER_ROOT . $objfile))) {
			$loader->helper('template');
			parse_template($tplfile, $objfile);
		}

		return MUDDER_ROOT . $objfile;
	}
}
?>