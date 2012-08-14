<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class query {

    function sql($params) {
        extract($params);
        if(!$sql) echo lang('global_sql_empty');
        $sql = str_replace('dbpre_', _G('dns','dbpre'),$sql) . ' LIMIT '.$start.','.$rows;
        $db =& _G('db');
        if(!$q = $db->query($sql)) { return null; }
        $result = array();
        while($value = $q->fetch_array()) {
            $result[] = $value;
        }
        $q->free_result();
        return $result;
    }

    function table($params) {
        $db =& _G('db');
        extract($params);
        if(!$table) echo lang('global_sql_invalid','from');
        $table = str_replace('dbpre_', _G('dns','dbpre'), $table);
        $select = $select ? $select : '*';
        $where = $where ? ('WHERE ' . $where) : '';
        $groupby = $groupby ? ('GROUP BY ' . $groupby) : '';
        $orderby = $orderby ? ('ORDER BY ' . $orderby) : '';
        $sql = "SELECT $select FROM $table $where $groupby $orderby LIMIT $start,$rows";
        if(!$q = $db->query($sql)) { return null; }
        $result = array();
        while($value = $q->fetch_array()) {
            $result[] = $value;
        }
        $q->free_result();
        return $result;
    }

    function area($params) {
        extract($params);
        $loader =& _G('loader');
		$AREA =& $loader->model('area');
        if($city) {
            $city_id = $city;
        } elseif(!$pid) {
            $city_id = 1;
            $pid = 0;
        } else {
            $rel = $loader->variable('area_rel');
            if(!$rel[$pid]) return '';
			//$city_id = $AREA->get_parent_aid($aid, 1);
            list($paid, $level) = explode(':', $rel[$pid]);
            if($level == 3) {
                list($city_id,) = explode(':', $rel[$paid]);
            } else if($level == 2) {
                $city_id = $paid;
            } else {
                $city_id = $pid;
            }
        }
        $area = $loader->variable('area_' . $city_id,'',false);
        $pid = $pid > 0 ? $pid : 1;
        $result = array();
        if($area) foreach($area as $key => $val) {
            if($val['pid'] == $pid) {
                $result[] = $val;
            }
        }
        return $result;
    }

    function bcastr($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');
        $db->from('dbpre_bcastr');
        $db->where('groupname', $groupname);
        $db->where('available', 1);
        if($city_id) $db->where('city_id',explode(',',$city_id));
        $db->order_by('orders');
        if(!$q = $db->get()) return array();
        $result = array();
        while($v=$q->fetch_array()) {
            $result[] = $v;
        }
        $q->free_result();
        return $result;
    }

    function citys($params) {
        extract($params);
        $loader =& _G('loader');
        $citys = $loader->variable('area');
        if(empty($num)) return $citys;
        $index = 1;
        $result = array();
        foreach($citys as $k=>$v) {
			if(!$v['enabled']) continue;
            if($k==$exce_city_id) continue;
            $result[$k]=$v;
            if(++$index>$num) break;
        }
        return $result;
    }
}
?>