<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_review {

    //获取点评对象的接口信息
    //参数 idtype,keyname
    function typeinfo($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$idtype) return '';
        $loader =& _G('loader');
        $R =& $loader->model(':review');
        if(!$typeinfo = $R->get_type($idtype)) return '';
        return $typeinfo[$keyname];
    }

    //获取点评对象的内容页地址
    //参数 idtype，id
    function typeurl($params) {
        extract($params);
        if(!$idtype) return '';
        if(!$id) return '';
        $loader =& _G('loader');
        $R =& $loader->model(':review');
        if(!$typeinfo = $R->get_type($idtype)) return '';
        return url(str_replace('_ID_',$id,$typeinfo['detail_url']));
    }

    //获取是否显示计划点评
    function viewdigest($params) {
        if(!$params['digest']) return true;
        if($params['uid']>0&&$params['uid']==_G('user')->uid) return true;
        $R =& _G('loader')->model(':review');
        if(!$R->modcfg['digest_price']) return TRUE;
        $result = _G('user')->check_access('review_viewdigest',$R,FALSE);
        if($result) return TRUE;
        $rid = abs((int) $params['rid']);
        if($params['rid']) {
            $DT =& _G('loader')->model('review:digestpay');
            return $DT->exists($params['rid']);
        }
        return false;
    }

}
?>