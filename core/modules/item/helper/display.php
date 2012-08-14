<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_item {

    //取得分类的名称或其它
    //参数 catid,keyname
    function category($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$catid) return '';
        $loader =& _G('loader');
        
        $C =& $loader->model('item:category');
        $root_id = $C->get_parent_id($catid);

        if(!$category = $loader->variable('category_' . $root_id, 'item')) return '';

        if(substr($keyname,0,7)=='config:') {
            $keyname = substr($keyname,7);
            //vp($category[$catid]['config']);
            return $category[$catid]['config'][$keyname];
        } else {
            return $category[$catid][$keyname];
        }
    }

    //取得分类对应模型表的内容
    //参数 catid,keyname
    function model($params) {
        extract($params);
        if(!$keyname) $keyname = 'item_name';
        if(!$catid) return '';
        $loader =& _G('loader');

        $C =& $loader->model('item:category');
        $root_id = $C->get_parent_id($catid);

        if(!$category = $loader->variable('category_' . $root_id, 'item', false)) return '';
        if(!$modelid = $category[$root_id]['modelid']) return '';
        if(!$model = $loader->variable('model_' . $modelid, 'item', false)) return '';

        return $model[$keyname];
    }

    //取得选项字段具体值
    //参数 catid,modelid,fieldname,value
    function option($params) {
        extract($params);
        if(!$value) return '';
        $loader =& _G('loader');
        if($catid > 0 && !$modelid) { 
            $C =& $loader->model('item:category');
            $pid = $C->get_parent_id($catid);
            $category = $loader->variable('category', 'item');
            $modelid = $category[$pid]['modelid'];
        }
        $fields = $loader->variable('field_'.$modelid, 'item', false);
        if(!$fields) return "$fieldname is empty.";
        $field = array();
        foreach($fields as $_val) {
            if($_val['fieldname'] == $fieldname && $_val['type'] == 'option') {
                $field = $_val;
                break;
            }
        }
        if(!$field) return $value;
        $options = $field['config']['value'];
        $result = '';
        $__val = explode(",", $value);
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $options));
        $split = '';
        foreach($list as $sval) {
            $v = explode("=",$sval);
            if($__val && in_array($v[0], $__val)) {
                $result .= $split . $v[1];
                $split = $field['config']['display_split'];
            }
        }
        if(!$result) $result = "N/A";
        return $result;
    }

    //取得tag的云图css样式
    //参数 total
    function tagclassname($params) {
        extract($params);
        $s = array(1=>'f1', 3=>'f2', 5=>'f3', 10=>'f4', 20=>'f4', 30=>'f5', 40=>'f6', 50=>'f7');
        foreach($s as $k => $v) {
            if($total <= $k) return $v;
        }
        return 'f0';
    }

    //获取标签的值
    // 参数 catid,modelid,fieldname,value
    function tag($params) {
        extract($params);
        if(!$value) return '';
        $loader =& _G('loader');
        if($catid > 0 && !$modelid) {
            $C =& $loader->model('item:category');
            $pid = $C->get_parent_id($catid);
            $category = $loader->variable('category', 'item');
            $modelid = $category[$pid]['modelid'];
        }
        $fields = $loader->variable('field_'.$modelid, 'item');
        $field = array();
        foreach($fields as $_val) {
            if($_val['fieldname'] == $fieldname && $_val['type'] == 'tag') {
                $field = $_val;
                break;
            }
        }
        if(!$field) return $value;
        $tags = @unserialize($value);
        $content = '';
        !$field['config']['split'] && $field['config']['split'] = ',';
        if($target) $target = ' target="'.$target.'"';
        foreach($tags as $tagname) {
            $content .= $split . '<a href="' . url('item/tag/tagname/' . $tagname, '', 1) . '"'.$target.'>'  . $tagname . '</a>';
            $split = $field['config']['split'];
        }
        return $content;
    }

    //返回一个主题地址
    //参数 sid,domain
    function url($params) {
        extract($params);
        if(!$sid && !$domain) return '';
        if(!$domain) return url('item/detail/id/'.$sid);
        $loader =& _G('loader');
        $modcfg = $loader->variable('config','item');
        if($modcfg['sldomain']==2 || !$modcfg['base_sldomain']) {
            return url('item/detail/name/'.$domain);
        } else {
            return 'http://' . $domain . '.' . $modcfg['base_sldomain'];
        }
    }

    //取得选项字段具体值
    //参数 catid,modelid,fieldname,value
    function att($params) {
        extract($params);
        if(!$value) return '';
        $loader =& _G('loader');
        if($catid > 0 && !$modelid) {
            $C =& $loader->model('item:category');
            $pid = $C->get_parent_id($catid);
            $category = $loader->variable('category', 'item');
            $modelid = $category[$pid]['modelid'];
        }
        if(!$modelid) return '';
        $fields = $loader->variable('field_'.$modelid, 'item');
        $field = array();
        foreach($fields as $_val) {
            if($_val['fieldname'] == $fieldname && $_val['type'] == 'att') {
                $field = $_val;
                break;
            }
        }
        if(!$field) return $value;
        if(!$use_catid = $field['config']['catid']) return '';
        $atts = $loader->variable('att_list_'.$use_catid, 'item');
        $content = '';
        if($value) $value = explode(',', $value);
        !$field['config']['split'] && $field['config']['split'] = ',';
        if($value) foreach($value as $attid) {
            if(!isset($atts[$attid])) continue;
            $name = $atts[$attid]['name'];
            $icon = $atts[$attid]['icon'];
            if($icon) $name = "<img src=\"".URLROOT."/static/images/att/$icon\" title=\"$name\">";
            $content .= $split . $name;
            $split = $field['config']['split'];
        }
        return $content;
    }
	
	//显示淘宝客积分等级
	function taoke_credit($params) {
		extract($params);
		if($value) $credit = $value;
		$credit = (int) $credit;
		if($credit<1) return URLROOT . '/static/images/rank/s_red_zero.gif';
		if($credit<=5) return URLROOT . '/static/images/rank/s_red_'.$credit.'.gif';
		if($credit<=10) return URLROOT . '/static/images/rank/s_blue_'.($credit-5).'.gif';
		if($credit<=15) return URLROOT . '/static/images/rank/s_cap_'.($credit-10).'.gif';
		if($credit<=20) return URLROOT . '/static/images/rank/s_crown_'.($credit-15).'.gif';
		return URLROOT . '/static/images/rank/s_crown_5.gif';
	}
}
?>