<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_product {
    //获取主题的分类列表
    function category($params) {
		$loader =& _G('loader');
		if($params['is_item']) {
			if(!$category = $loader->variable('category','item')) return;
			$result = array();
			foreach($category as $key => $val) {
				if($val['pid']>0 || !$val['enabled']) continue;
				if(!$val['config']['product_modelid']) continue;
				$result[] = $val;
			}
			return $result;
		} else {
			if(!is_numeric($params['sid']) || $params['sid'] < 1) return;
			$C =& $loader->model('product:category');
			return $C->get_list($params['sid']);
		}
    }

	//获取产品列表
	//select,cachetime,rows,orderby,sid
	function getlist($params) {
		extract($params);
        $loader =& _G('loader');
        $db =& _G('db');

        //$db->from('dbpre_product','pt');
		$db->join('dbpre_product','pt.sid','dbpre_subject','s.sid','LEFT JOIN');
        $db->select($select?$select:'pt.pid,pt.modelid,pt.sid,pt.catid,pt.subject,pt.dateline,pt.grade,pt.pageview,pt.comments,pt.thumb,pt.price,pt.description,s.name,s.subname');
        if($city_id) $db->where('s.city_id',explode(',',trim($city_id)));
		if($catid > 0) $db->where('pt.uid',$uid);
		if($sid > 0) $db->where('pt.sid',$sid);
        if($uid > 0) $db->where('pt.uid',$uid);
        if($finer > 0) $db->where('pt.finer',$finer);
        if($comments > 0) $db->where_more('pt.comments',$comments);
        $db->where('pt.status', 1);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}
}
?>