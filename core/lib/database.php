<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define("MF_DB_J",'JOIN');
define("MF_DB_LJ",'LEFT JOIN');
define("MF_DB_RJ",'RIGHT JOIN');

$_G['loader']->lib('mysql', NULL, FALSE);

class ms_database extends ms_mysql {

    //所支持的组合子句集合
    var $var_fields = array('set', 'select', 'from', 'where', 'group_by', 'having', 'order_by', 'limit');
    
    var $table = '';
    var $where = '';
    var $set = '';
    var $select = '';
    var $from = '';
    var $group_by = '';
    var $having = '';
    var $order_by = '';
    var $limit = '';

    //缓存
    var $cache_table = '';
    var $cache_where = '';
    var $cache_select = '';
    var $cache_from = '';
    var $cache_group_by = '';
    var $cache_having = '';
    var $cache_order_by = '';
    var $cache_limit = '';

    function ms_database(& $dns) {
        $this->__construct($dns);
    }
    
    function __construct(& $dns) {
        parent::__construct($dns);
        $this->connect();
    }

    // 获取一个表名
    function get_table($tablename) {
        return str_replace("dbpre_", $this->dns['dbpre'], $tablename);
    }

    // 设置SQL中的from子句
    function from($tablename, $asname = null, $sql = null) {
        $this->from = $this->get_table($tablename) . ($asname ? " $asname" : '');
        if($sql) $this->from .= ' ' . $sql;
        return $this;
    }

    /*
    // 关联2个表
    function join($table1, $asname1, $key1, $table2, $asname2, $key2, $jointype = 'JOIN') {
        $key1 = $this->_ck_field($key1);
        $key2 = $this->_ck_field($key2);
        $table1 = $this->get_table($table1);
        $table2 = $this->get_table($table2);
        $this->from = sprintf("%s %s %s %s %s ON (%s.%s = %s.%s)", 
            $table1, $asname1, $jointype, $table2, $asname2, $asname1, $key1, $asname2, $key2);
    }
    */

    // 关联2个表
    function join($table1,$key1, $table2,$key2, $jointype = 'JOIN') {
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key1, $match1)) redirect('无效的关联表字段。');
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key2, $match2)) redirect('无效的关联表字段。');

        $table1 = $this->get_table($table1);
        $table2 = $this->get_table($table2);
        $asname1 = $match1[1];
        $asname2 = $match2[1];
        $key1 = $this->_ck_field($match1[2]);
        $key2 = $this->_ck_field($match2[2]);

        $this->from = sprintf("%s %s %s %s %s ON (%s.%s = %s.%s)", 
            $table1, $asname1, $jointype, $table2, $asname2, $asname1, $key1, $asname2, $key2);
        return $this;
    }

    // 关联第3,4..个表
    function join_together($together_key, $table, $key, $jointype = 'JOIN') {
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $together_key, $match1)) redirect('无效的关联表字段。');
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key, $match2)) redirect('无效的关联表字段。');

        $together_asname = $match1[1];
        $asname = $match2[1];

        $table = $this->get_table($table);
        $together_key = $this->_ck_field($match1[2]);
        $key = $this->_ck_field($match2[2]);

        $this->from .= sprintf(" %s %s %s ON (%s.%s = %s.%s)", 
            $jointype, $table, $asname, $together_asname, $together_key, $asname, $key);
        return $this;
    }

    // 设置查询字段
    function select($fields, $asname = NULL, $fun = NULL) {
        $split = $this->select ? ',' : '';
        if(is_string($fields)) {
            if($fun) {
                $fields = str_replace('?', $this->_ck_field($fields), $fun);
            } else {
                $fields = $this->_ck_field($fields);
            }
            $select = $fields . ($asname ? (' AS ' . $this->_ck_field($asname)) : '');
            $this->select .= $split . $select;
        } elseif(is_array($fields)) {
            foreach ($fields as $key) {
                $this->select .= $split . $this->_ck_field($fields);
                $split = ',';
            }
        }
        return $this;
    }
    
    // 设置更新字段或插入字段数据
    function set($key, $value=null) {
        if(is_array($key)) {
            foreach($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {
            $this->_ck_field($key);
            $this->set[$key] = $this->_escape($value);
        }
        return $this;
    }

    // 设置更新2个字段值相同
    function set_equal($key1,$key2) {
        $this->_ck_field($key1);
        $this->_ck_field($key2);
        $this->set[$key1] = $key2;
        return $this;
    }
    
    // 设置更新字段累加
    function set_add($key, $value=1) {
        if(!$value) return;
        $this->_ck_field($key);
        $this->set[$key] = $key . '+' . $this->_escape($value);
        return $this;
    }

    // 设置更新字段减少
    function set_dec($key, $value=1) {
        $this->_ck_field($key);
        $this->set[$key] = $key . '-' . $this->_escape($value);
        return $this;
    }

    // 直接使用SQL表示set的值
    function set_src($key, $value) {
        $this->set[$key] = $key . '-' . $value;
        return $this;
    }
    
    // 设置查询字段
    function where($key, $value='', $split='AND') {
        if(is_array($key)) {
            foreach ($key as $k => $v) {
                if(is_array($v) && count($v)==2 && is_array($v[1])) {
                    $fun = $v[0];
                    $args = array_merge(array($k), $v[1]);
                    call_user_func_array(array(&$this, $fun), $args);
                } else {
                    $this->where($k, $v, $split);
                }
            }
        } elseif($key=='{sql}') {
            $this->_exp_where('sql', $value, $split);
        } elseif(is_array($value)) {
            $this->where_in($key, $value, $split);
        } else {
            $where = $this->_ck_field($key) . " = " . $this->_escape($value);
            $this->where .= ($this->where ? " $split " : '') . $where;    
        }
        return $this;
    }

    // 查询字段表达式，用户复杂的where子句
    function where_exp($exp, $key, $value, $split='AND') {
        if(is_array($value)) {
            foreach($value as $_k => $val) {
                $value[$_k] = $this->_escape($val);
            }
            $value = implode(",", $value);
        }
        $where = sprintf(str_replace(" ? "," %s ", $exp), $this->_ck_field($key), $value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 field != 'value'
    function where_not_equal($key, $value, $split='AND') {
        $where = $this->_ck_field($key) . " != " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 field >= 'value' 或 field > 'value'
    function where_more($key, $value, $equal=1, $split='AND') {
        $mark = $equal ? '>=' : '>';
        $where = $this->_ck_field($key) . $mark . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 field <= 'value' 或 field < 'value'
    function where_less($key, $value, $equal=1, $split='AND') {
        $mark = $equal ? '<=' : '<';
        $where = $this->_ck_field($key) . $mark . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 in
    function where_in($key, $values, $split='AND') {
        if(count($values) == 0) return;
        if(count($values) == 1) {
            $this->where($key, $values[0], $split);
            return;
        }
        foreach($values as $_key => $_val) {
            $values[$_key] = $this->_escape($_val);
        }
        $where = $this->_ck_field($key) . " IN (". implode(",", $values) .")";
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 not in
    function where_not_in($key, $values, $split='AND') {
        foreach($values as $_key => $_val) {
            $values[$key] = $this->_escape($_val);
        }
        $where = $this->_ck_field($key) . " NOT IN (". implode(",", $values) .")";
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }
    
    // 设置查询子句形式 field between 100 and 200
    function where_between_and($key, $begin, $end, $split='AND') {
        $where = $this->_ck_field($key) . " BETWEEN ". $this->_escape($begin) ." AND " . $this->_escape($end);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // 设置查询子句形式 field like '100%'
    function where_like($key, $value, $split='AND', $kh = '') {
        $where = $this->_ck_field($key) . " LIKE " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    
    // 设置查询子句形式 or
    function where_or($key, $value) {
        if(is_array($value)) {
            $this->where_in($key, $value, 'OR');
        } else {
            $where = $this->_ck_field($key) . " = " . $this->_escape($value);
            $this->where .= ($this->where ? ' OR ' : '') . $where;
        }
        return $this;
    }

    // 设置查询子句形式 CONCAT(field,field2) like '100%'
    function where_concat_like($keys, $value, $split='AND', $kh='') {
        if(is_string($keys)) $keys = explode(',', $keys);
        foreach($keys as $k=>$v) {
            $keys[$k] = $this->_ck_field(trim($v));
        }
        $where = "CONCAT(".implode(',',$keys).") LIKE " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    // 设置exist子查询
    function where_exist($sql, $split='AND', $kh = '') {
        $where = 'exists(' .$this->get_table($sql) . ')';
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    // 设置exist子查询
    function where_in_select($key, $sql, $split='AND', $kh = '') {
        $where = $key . ' IN(' .$this->get_table($sql) . ')';
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
    }
    // 设置group_by子句
    function group_by($groups) {
        if(is_array($groups)) {
            foreach($groups as $key => $val) {
                $groups[$key] = $this->_ck_field($val);
            }
            $groupby = implode(',', $groups);
        } elseif(is_string($groups)) {
            $groupby = $this->_ck_field($groups);
        }
        $this->group_by .= ($this->group_by ? ',' : '') . $groupby;
        return $this;
    }

    //设置having子句
    function having($exp) {
        $this->having = $exp;
    }
    
    // 设置order_by子句
    function order_by($field, $type='ASC') {
        if($field=='NULL') {
            $this->order_by = "NULL";
            return $this;
        } elseif(is_string($field)) {
            if(strpos($field, ' ')) {
                list($field, $type) = explode(' ', $field);
            }
            $orderby = $this->_ck_field($field) . ($type=='DESC' ? (' ' . $type) : '');
        } elseif(is_array($field)) {
            $split = '';
            foreach ($field as $key => $val) {
                $orderby .= $split . $this->_ck_field($key) . ($val=='DESC' ? (' ' . $val) : '');
                $split = ',';
            }
        }
        $this->order_by .= ($this->order_by ? ',' : '') . $orderby;
        return $this;
    }

    // 设置 limit 子句
    function limit($start, $offset) {
        $start = (int) $start;
        $offset = (int) $offset;
        if(!$start && !$offset) return ;
        if(!$start) {
            $this->limit = "$offset";
        }
        if (!$offset) {
            $offset = 10;
        }
        $this->limit = "$start, $offset";
        return $this;
    }

    //组合一个查询sql
    function get_sql($get_count_sql=FALSE) {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(!$this->$from) show_error(lang('global_sql_invalid', "FROM(Get)"));
        $SQL = "SELECT " . ($get_count_sql ? "COUNT(*)":($this->$select ? $this->$select : "*")) .
            " FROM " . $this->$from .
            ($this->$where ? " WHERE " . $this->where : "") .
            ($this->$group_by ? " GROUP BY " . $this->$group_by : "") .
            ($this->$having ? " HAVING " . $this->$having : "") .
            ($this->$order_by ? " ORDER BY " . $this->$order_by : "") .
            ($get_count_sql ? '' : ($this->$limit ? " LIMIT " . $this->$limit : ""));
        
        return $SQL;
    }

    //从组合的sql中获取mysql_result句柄
    function get($method='', $clear_var=TRUE) {
        $SQL = $this->get_sql(0);
        $result = $this->query($SQL, $method);
        $this->_cache_sql(); //sql cache 
        if($clear_var) {
            $this->clear();
        }
        return $result;
    }

    function get_easy($select, $from, $where=null, $group_by=null, $order_by=null, $limit=null) {
        $this->select($select);
        $this->from($from);
        if($where) {
            foreach($where as $sk => $sv) {
                $this->where($sk, $sv);
            }
        }
        if($group_by) $this->group_by($group_by);
        if($order_by) $this->order_by($order_by[0], $order_by[1]);
        if($limit) $this->limit($limit[0],$limit[1]);

        return $this->get();
    }

    //获取一条数据
    function get_one($method='', $clear_var=TRUE) {
		if(!$this->limit) $this->limit(0,1);
        $SQL = $this->get_sql(0);
        $result = $this->query($SQL, $method);
        $this->_cache_sql(); //sql cache 
        if($clear_var) {
            $this->clear();
        }
        if($result) {
            return $result->fetch_array();
        } else {
            return false;
        }
    }

    //获取一个字段数据
    function get_value($clear_var=TRUE) {
        $this->limit(0,1);
        $SQL = $this->get_sql(0);
        $result = $this->query($SQL);
        $this->_cache_sql(); //sql cache 
        if($clear_var) {
            $this->clear();
        }
        return $result ? $result->result(0) : FALSE;
    }

    //返回数据条数
    function count($clear_var=TRUE) {
        $SQL = $this->get_sql(1);
        $row = $this->query($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
        return $row->result(0);
    }

    //组合一个插入sql
    function insert_sql($replace=FALSE, $update=FALSE) {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(empty($this->$from)) show_error(lang('global_sql_invalid', 'FROM(insert)'));
        if(empty($this->$set)) show_error(lang('global_sql_invalid', 'SET(insert)'));
        
        $split = $setsql = '';
        foreach($this->$set as $key => $val) {
            $setsql .= $split . $key . '=' . $val;
            $split = ',';
        }

        if($update) {
            $SQL = "UPDATE " . $this->$from . " SET " . $setsql . ($this->$where ? " WHERE " . $this->$where : "");
        } else {
            $SQL = ($replace ? "REPLACE " : "INSERT ") . $this->$from . " SET " . $setsql;
        }

        return $SQL;
    }

    //执行插入（替换）表操作
    function insert($replace=FALSE, $clear_var=TRUE) {
        $SQL = $this->insert_sql($replace, 0);
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //更新表数据
    function replace($clear_var=TRUE) {
        $SQL = $this->insert_sql(true, 0);
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //更细数据
    function update($clear_var=TRUE) {
        $SQL = $this->insert_sql(0, 1);
        $this->exec($SQL, 'unbuffer');
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //组合成一个删除sql
    function delete_sql() {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(!$this->$from) show_error(lang('global_sql_invalid', "FROM(delete)"));
        $SQL = "DELETE FROM " . $this->$from . ($this->$where ? " WHERE " . $this->$where : "");
        return $SQL;
    }

    //执行删除操作
    function delete($clear_var=TRUE) {
        $SQL = $this->delete_sql();
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //清空一个表
    function clear_table() {
        if(!$this->$from) show_error(lang('global_sql_invalid', 'FROM(Clear Table)'));
        $this->db->exec("TRUNCATE TABLE " . $this->$from);
    }

    //删除一个表
    function drop_table() {
        if(!$this->$from) show_error(lang('global_sql_invalid', 'FROM(Dorp Table)'));
        $this->db->exec("DROP TABLE IF EXISTS " . $this->$from);
    }

    //清理当前的sql个子句内容
    function clear($name='ALL') {
        if($name == 'ALL') {
            foreach($this->var_fields as $v) {
                $this->$v = '';
            }
        } elseif(isset($name[$this->var_fields])) {
            $this->$name = '';
        }
    }
    
    //SQL代码回滚，将在缓存的SQL重新设置成当前有效的SQL，支持回滚部分参数
    function sql_roll_back($vars = null) {
        $arr = $vars ? (is_array($vars) ? $vars : explode(',',$vars)) : $this->var_fields;
        foreach($arr as $v) {
            $n = 'cache_'.$v;
            $this->$v = $this->$n;
        }
        return $this;
    }

    //特殊含义的sql解析
    function _exp_where($exp,$value,$split) {
        $pattern_arr = array("/ union /i", "/ select /i", "/ update /i", "/ outfile /i");
        $where = '';
        switch($exp) {
        case 'sql':
            foreach($pattern_arr as $p) if(preg_match($p,$value)) return;
            $where = $value;
            break;
        }
        if(!$where) return;
        $this->where .= ($this->where ? " $split " : '') . $where; 
    }

    //转换插入sql的值
    function _escape($str) {
        switch (gettype($str)) {
            case 'string':
                $str = "'".$this->_escape_str($str)."'";
                break;
            case 'boolean': 
                $str = ($str === FALSE) ? 0 : 1;
                break;
            default:
                $str = ($str === NULL) ? 'NULL' : $str;
                break;
        }        
        return $str;
    }

    //缓存SQL各子句数据
    function _cache_sql($clear=FALSE) {
        foreach($this->var_fields as $v) {
            $n = 'cache_'.$v;
            $this->$n = $this->$v;
            if($clear) $this->$v = '';
        }
    }
}

