<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display {

    //参数 aid,keyname
    function area($params) {
        extract($params);
        if(!$aid) return lang('global_global');
        if(!$keyname) $keyname = 'name';
        $loader =& _G('loader');
        $A =& $loader->model('area');
        $city_id = $A->get_parent_aid($aid, 1);
        if($city_id == $aid) {
            $area = $loader->variable('area');
        } else {
            $area = $loader->variable('area_' . $city_id,'',0);
        }
        if(!isset($area[$aid][$keyname])) return lang('global_global');
        return $area[$aid][$keyname];
    }

	//参数 domain,city_id
	function cityurl($params) {
		extract($params);
        $loader =& _G('loader');
        $city_id = (int) $city_id;
        $citys = $loader->variable('area');
        $domain = $citys[$city_id]['domain'];
        if($domain && _G('cfg','city_sldomain')>0) {
            unset($params['city_id']);
            if($forward) {
            $forward = rawurldecode($forward);
            return url($forward,'',TRUE,FALSE,$city_id);
            } else {
                if(_G('cfg','city_sldomain')=='1') {
                    $ext = get_fl_domain();
                    return 'http://' . $domain . '.' . $ext . '/';
                } elseif(_G('cfg','city_sldomain')=='2') {
                    $show_index = '';
                    if(!_G('cfg','rewrite_hide_index')) {
                        $show_index = 'index.php/';
                    }
                    return _G('cfg','siteurl') . $show_index . $domain . '/';
                }
            }
        } else {
            if(!$forward) $forward = null;
		    return url("index/city/city_id/$city_id/forward/$forward");
        }
	}

}
?>