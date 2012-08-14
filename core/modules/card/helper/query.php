<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_card {

    function list_new($params=null) {
        $params['orderby'] = 'c.addtime';
        $params['ordersc'] = 'DESC';
        return query_card::_list($params);
    }

    function list_finer($params=null) {
        $params['c.finer'] = 1;
        $params['orderby'] = 'c.finer';
        $params['ordersc'] = 'DESC';
        return query_card::_list($params);
    }

    function _list($params=null) {
        extract($params);
        $loader =& _G('loader');
        $C =& $loader->model('card:discount');
        $C->db->join($C->table,'c.sid',$C->subject_table,'s.sid','LEFT JOIN');
        $C->db->select($select?$select:'c.*');
        $C->db->select($subject_select?$subject_select:'s.name,s.subname');
        if($city_id>0) $C->db->where('city_id', $city_id);
        if($sid>0) $C->db->where('sid', $sid);
        if($finer>0) $C->db->where_more('finer', 1);
        $C->db->where('c.available', 1);

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