<?php
/**
* @author ��<service@cmsky.org>
* @copyright (c)2009-2011 ������
* @copyright ������(www.cmsky.org)
*/

class display_exchange {
    //ȡ�����������
    //���� catid,keyname
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