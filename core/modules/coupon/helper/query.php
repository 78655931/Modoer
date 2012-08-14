<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_coupon {

    function list_new($params=null) {
        $params['orderby'] = 'dateline';
        $params['ordersc'] = 'DESC';
        return query_coupon::_list($params);
    }

    function list_hot($params=null) {
        $params['effect1'] = 1;
        $params['orderby'] = 'effect1';
        $params['ordersc'] = 'DESC';
        return query_coupon::_list($params);
    }

    function list_print($params=null) {
        $params['prints'] = 1;
        $params['orderby'] = 'prints';
        $params['ordersc'] = 'DESC';
        return query_coupon::_list($params);
    }

    function list_pageview($params=null) {
        $params['pageview'] = 1;
        $params['orderby'] = 'pageview';
        $params['ordersc'] = 'DESC';
        return query_coupon::_list($params);
    }

    function list_subject($params=null) {
        $params['orderby'] = 'dateline';
        $params['ordersc'] = 'DESC';
        return query_coupon::_list($params);
    }

    function category($params=null) {
        $loader =& _G('loader');
        if(!$params['nocache']) return $loader->variable('category','coupon');
        $db =& _G('db');
        $db->from('dbpre_coupon_category');
        $db->order_by('listorder');
        $result = array();
        if($r = $db->get()) {
            while($v=$r->fetch_array()) {
                $result[$v['catid']] = $v;
            }
            $r->free_result();
        }
        return $result;
    }

    function _list($params=null) {
        extract($params);
        $loader =& _G('loader');
        $C =& $loader->model(':coupon');

        $C->db->select($select?$select:'couponid,catid,sid,subject,starttime,endtime,thumb,pageview,effect1,prints,comments,dateline');
        $C->db->from($C->table);
		if($city_id) $C->db->where('city_id',explode(',',$city_id));
        if($catid>0) $C->db->where('catid', $catid);
        if($sid>0) $C->db->where('sid', $sid);
        if($effect1>0) $C->db->where_more('effect1', $effect1);
        if($prints>0) $C->db->where_more('prints', $prints);
        if($pageview>0) $C->db->where_more('pageview', $pageview);
        if($comments>0) $C->db->where_more('comments', $comments);
        if($starttime>0) $C->db->where_more('starttime', $starttime);
        if($endtime>0) $C->db->where_less('endtime', $endtime);
        $C->db->where('status', 1);

        $orderby && $C->db->order_by($orderby, $ordersc);
        $C->db->limit($start, $rows);
        if(!$r = $C->db->get()) { return null; }

        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

}
?>