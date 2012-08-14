<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_member {

    //获取会员组信息
    //参数 groupid,keyname
    function group($params) {
        extract($params);
        if(!$groupid) return '';
        if(!$keyname) $keyname = 'groupname';
        $loader =& _G('loader');
        $ug = $loader->variable('usergroup','member');
        if(isset($ug[$groupid])) {
            if($color && $ug[$groupid]['color'] && $keyname == 'groupname') {
                return '<font color="'.$ug[$groupid]['color'].'">'.$ug[$groupid]['groupname'].'</font>';
            }
            return $ug[$groupid][$keyname];
        } else {
            return '';
        }
    }

    //获取扩展积分信息
    //参数 point,keyname
    function point($params) {
        extract($params);
        if(!$point) return;
        $loader =& _G('loader');
        $cfg = $loader->variable('config','member');
        if(!$cfg['point_group']) return;
        $point_group = $cfg['point_group'] ? unserialize($cfg['point_group']) : array();
        if(!$point_group) return;
        if(!$keyname) $keyname = 'name';
		if($point == 'rmb' && $keyname=='name') return lang('member_point_rmb');
        return $point_group[$point][$keyname];
    }
}
?>