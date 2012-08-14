<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class query_adv {

    function getlist($params) {
        extract($params);
        $loader =& _G('loader');
        $db=&_G('db');

		if(!$orderby) $orderby = 'listorder';
        $begintime = strtotime(date('Y-m-d', _G('timestamp')));
        $endtime = strtotime(date('Y-m-d', _G('timestamp')));

		$db->from('dbpre_adv_list');
		if($apid > 0) $db->where('apid', $apid);
		if($city_id) $db->where('city_id', explode(',',trim($city_id)));
		$db->where('enabled', 'Y');
		if($attr) $db->where('attr', $attr);
		$db->where_less('begintime', $endtime);
		$db->where_exp("( ? >= ? ", 'endtime', $begintime);
		$db->where_exp(" ? = ? )", 'endtime', 0, 'OR');
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