<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2011 风格店铺
* @copyright 风格店铺(www.cmsky.org)
*/

class display_exchange {
    //取得主题的名称
    //参数 catid,keyname
    function itemname($params) {
        extract($params);
        if(!$sid) return '';
        $loader =& _G('loader');
        if($sid > 0) {
            $C =& $loader->model('item:subject');
            if(!$detail = $C->read($sid)) return '';
            return $detail['name'];
        }
    }

    function group($params) {
        extract($params);
        if(!$giftid) return '';
        $loader =& _G('loader');
        $GT =& $loader->model('exchange:gift');
        if(!$detail = $GT->read($giftid)) return '';
        $groupid = explode(',',trim($detail['usergroup'],','));
        $ug = $loader->variable('usergroup','member');
        $content = '';
        !$split && $split = ',';
        if($groupid) foreach($groupid as $id) {
            if(isset($ug[$id])) {
                $content .= $xsplit . $ug[$id]['groupname'];
                $xsplit = $split;
            }
        }
        return $content;
    }
}
?>