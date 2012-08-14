<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class display_review {

    //��ȡ��������Ľӿ���Ϣ
    //���� idtype,keyname
    function typeinfo($params) {
        extract($params);
        if(!$keyname) $keyname = 'name';
        if(!$idtype) return '';
        $loader =& _G('loader');
        $R =& $loader->model(':review');
        if(!$typeinfo = $R->get_type($idtype)) return '';
        return $typeinfo[$keyname];
    }

    //��ȡ�������������ҳ��ַ
    //���� idtype��id
    function typeurl($params) {
        extract($params);
        if(!$idtype) return '';
        if(!$id) return '';
        $loader =& _G('loader');
        $R =& $loader->model(':review');
        if(!$typeinfo = $R->get_type($idtype)) return '';
        return url(str_replace('_ID_',$id,$typeinfo['detail_url']));
    }

    //��ȡ�Ƿ���ʾ�ƻ�����
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