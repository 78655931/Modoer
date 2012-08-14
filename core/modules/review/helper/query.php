<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class query_review {

    function review($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');
        if(!$select) $select = 'rid,pcatid,city_id,sid,uid,username,ip,title,content,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,price,best,posttime';
        $db->from('dbpre_review','r');
        if($idtype) $db->where('r.idtype',explode(',',$idtype));
        if($id>0) $db->where('r.id', $id);
		if($city_id) $db->where('r.city_id',explode(',',$city_id));
        if($pid>0) $db->where('r.pcatid', $pid);
        if(isset($best)) $db->where('best', (int)$best);
        if(isset($digst)) $db->where('digst', (int)$digst);
        if(isset($havepic)) $db->where('havepic', (int)$havepic);
        $db->where('status', 1);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r = $db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    //catid
    function reviewopt($params) {
        extract($params);
        $loader =& _G('loader');
        $result = array();
        if($catid > 0 && !$modelid) {
            $category = $loader->variable('category', 'item');
            if(isset($category[$catid])) {
                if($category[$catid]['pid'] > 0) {
                    $pid = $category[$catid]['pid'];;
                } else {
                    $pid = $catid;
                }
            }
            $modelid = $category[$pid]['modelid'];
            $rogid = $category[$pid]['review_opt_gid'];
        }
        $result = $loader->variable('opt_' . $rogid, 'review', false);
        return $result;
    }

    //flower
    function flowers($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');
        $db->from('dbpre_flowers');
        if($rid) $db->where('rid', $rid);
        if($uid) $db->where('uid', $uid);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r = $db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

}
?>