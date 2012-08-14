<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_article {
    //获取文章文类
    function category($params) {
        $loader =& _G('loader');
        $category = $loader->variable('category','article');
        $result = '';
        !$params['pid'] && $params['pid'] = 0;
        if($params['pid'] && $params['parent']) {
            $params['pid'] = (int) $category[$params['pid']]['pid'];
        }
        foreach($category as $key => $val) {
            if($val['pid'] == $params['pid']) {
                $result[$key] = $val;
            }
        }
        return $result;
    }

    //获取文章列表
    function getlist($params) {
        extract($params);
        $loader =& _G('loader');
        $A =& $loader->model(':article');
        if($sid>0) {
            $A->db->join('dbpre_subjectlink', 'sl.flagid', $A->table, 'a.articleid');
        } else {
            $A->db->from($A->table, 'a');
        }
        $A->db->select($select?$select:'articleid,catid,subject,author,comments,digg,a.dateline');
        if($catid>0) {
            $loader->helper('misc','article');
            $catids = misc_article::category_catids($catid);
            $A->db->where('catid',$catids?$catids:$catid);
        }
        if($city_id) $A->db->where('city_id',explode(',', $city_id));
        if($sid > 0) {
            $A->db->where('sl.sid',$sid);
            $A->db->where('sl.flag','article');
        }
        if($att > 0) $A->db->where('att',$att);
        if($havepic) $A->db->where('havepic',1);
        if($comments > 0) $A->db->where_more('comments',$comments);
        if($digg > 0) $A->db->where_more('digg',$digg);
        $A->db->where('a.status', 1);
        if($orderby) {
            if($sid>0 && substr($orderby,0,8) == 'dateline') $orderby = 'sl.'.$orderby;
            $A->db->order_by($orderby);
        }
        $A->db->limit($start, $rows);

        if(!$r=$A->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }
}
?>