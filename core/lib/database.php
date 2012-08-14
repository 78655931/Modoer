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

    //��֧�ֵ�����Ӿ伯��
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

    //����
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

    // ��ȡһ������
    function get_table($tablename) {
        return str_replace("dbpre_", $this->dns['dbpre'], $tablename);
    }

    // ����SQL�е�from�Ӿ�
    function from($tablename, $asname = null, $sql = null) {
        $this->from = $this->get_table($tablename) . ($asname ? " $asname" : '');
        if($sql) $this->from .= ' ' . $sql;
        return $this;
    }

    /*
    // ����2����
    function join($table1, $asname1, $key1, $table2, $asname2, $key2, $jointype = 'JOIN') {
        $key1 = $this->_ck_field($key1);
        $key2 = $this->_ck_field($key2);
        $table1 = $this->get_table($table1);
        $table2 = $this->get_table($table2);
        $this->from = sprintf("%s %s %s %s %s ON (%s.%s = %s.%s)", 
            $table1, $asname1, $jointype, $table2, $asname2, $asname1, $key1, $asname2, $key2);
    }
    */

    // ����2����
    function join($table1,$key1, $table2,$key2, $jointype = 'JOIN') {
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key1, $match1)) redirect('��Ч�Ĺ������ֶΡ�');
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key2, $match2)) redirect('��Ч�Ĺ������ֶΡ�');

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

    // ������3,4..����
    function join_together($together_key, $table, $key, $jointype = 'JOIN') {
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $together_key, $match1)) redirect('��Ч�Ĺ������ֶΡ�');
        if(!preg_match("/^([a-z]+)\.([a-z0-9_]+)$/i", $key, $match2)) redirect('��Ч�Ĺ������ֶΡ�');

        $together_asname = $match1[1];
        $asname = $match2[1];

        $table = $this->get_table($table);
        $together_key = $this->_ck_field($match1[2]);
        $key = $this->_ck_field($match2[2]);

        $this->from .= sprintf(" %s %s %s ON (%s.%s = %s.%s)", 
            $jointype, $table, $asname, $together_asname, $together_key, $asname, $key);
        return $this;
    }

    // ���ò�ѯ�ֶ�
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
    
    // ���ø����ֶλ�����ֶ�����
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

    // ���ø���2���ֶ�ֵ��ͬ
    function set_equal($key1,$key2) {
        $this->_ck_field($key1);
        $this->_ck_field($key2);
        $this->set[$key1] = $key2;
        return $this;
    }
    
    // ���ø����ֶ��ۼ�
    function set_add($key, $value=1) {
        if(!$value) return;
        $this->_ck_field($key);
        $this->set[$key] = $key . '+' . $this->_escape($value);
        return $this;
    }

    // ���ø����ֶμ���
    function set_dec($key, $value=1) {
        $this->_ck_field($key);
        $this->set[$key] = $key . '-' . $this->_escape($value);
        return $this;
    }

    // ֱ��ʹ��SQL��ʾset��ֵ
    function set_src($key, $value) {
        $this->set[$key] = $key . '-' . $value;
        return $this;
    }
    
    // ���ò�ѯ�ֶ�
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

    // ��ѯ�ֶα��ʽ���û����ӵ�where�Ӿ�
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

    // ���ò�ѯ�Ӿ���ʽ field != 'value'
    function where_not_equal($key, $value, $split='AND') {
        $where = $this->_ck_field($key) . " != " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // ���ò�ѯ�Ӿ���ʽ field >= 'value' �� field > 'value'
    function where_more($key, $value, $equal=1, $split='AND') {
        $mark = $equal ? '>=' : '>';
        $where = $this->_ck_field($key) . $mark . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // ���ò�ѯ�Ӿ���ʽ field <= 'value' �� field < 'value'
    function where_less($key, $value, $equal=1, $split='AND') {
        $mark = $equal ? '<=' : '<';
        $where = $this->_ck_field($key) . $mark . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // ���ò�ѯ�Ӿ���ʽ in
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

    // ���ò�ѯ�Ӿ���ʽ not in
    function where_not_in($key, $values, $split='AND') {
        foreach($values as $_key => $_val) {
            $values[$key] = $this->_escape($_val);
        }
        $where = $this->_ck_field($key) . " NOT IN (". implode(",", $values) .")";
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }
    
    // ���ò�ѯ�Ӿ���ʽ field between 100 and 200
    function where_between_and($key, $begin, $end, $split='AND') {
        $where = $this->_ck_field($key) . " BETWEEN ". $this->_escape($begin) ." AND " . $this->_escape($end);
        $this->where .= ($this->where ? " $split " : '') . $where;
        return $this;
    }

    // ���ò�ѯ�Ӿ���ʽ field like '100%'
    function where_like($key, $value, $split='AND', $kh = '') {
        $where = $this->_ck_field($key) . " LIKE " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    
    // ���ò�ѯ�Ӿ���ʽ or
    function where_or($key, $value) {
        if(is_array($value)) {
            $this->where_in($key, $value, 'OR');
        } else {
            $where = $this->_ck_field($key) . " = " . $this->_escape($value);
            $this->where .= ($this->where ? ' OR ' : '') . $where;
        }
        return $this;
    }

    // ���ò�ѯ�Ӿ���ʽ CONCAT(field,field2) like '100%'
    function where_concat_like($keys, $value, $split='AND', $kh='') {
        if(is_string($keys)) $keys = explode(',', $keys);
        foreach($keys as $k=>$v) {
            $keys[$k] = $this->_ck_field(trim($v));
        }
        $where = "CONCAT(".implode(',',$keys).") LIKE " . $this->_escape($value);
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    // ����exist�Ӳ�ѯ
    function where_exist($sql, $split='AND', $kh = '') {
        $where = 'exists(' .$this->get_table($sql) . ')';
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
        return $this;
    }
    // ����exist�Ӳ�ѯ
    function where_in_select($key, $sql, $split='AND', $kh = '') {
        $where = $key . ' IN(' .$this->get_table($sql) . ')';
        $this->where .= ($this->where ? " $split " : '') . ($kh=='('?'(':'') . $where . ($kh==')'?')':'');
    }
    // ����group_by�Ӿ�
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

    //����having�Ӿ�
    function having($exp) {
        $this->having = $exp;
    }
    
    // ����order_by�Ӿ�
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

    // ���� limit �Ӿ�
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

    //���һ����ѯsql
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

    //����ϵ�sql�л�ȡmysql_result���
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

    //��ȡһ������
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

    //��ȡһ���ֶ�����
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

    //������������
    function count($clear_var=TRUE) {
        $SQL = $this->get_sql(1);
        $row = $this->query($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
        return $row->result(0);
    }

    //���һ������sql
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

    //ִ�в��루�滻�������
    function insert($replace=FALSE, $clear_var=TRUE) {
        $SQL = $this->insert_sql($replace, 0);
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //���±�����
    function replace($clear_var=TRUE) {
        $SQL = $this->insert_sql(true, 0);
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //��ϸ����
    function update($clear_var=TRUE) {
        $SQL = $this->insert_sql(0, 1);
        $this->exec($SQL, 'unbuffer');
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //��ϳ�һ��ɾ��sql
    function delete_sql() {
        foreach($this->var_fields as $v) {
            $$v = $v;
        }
        if(!$this->$from) show_error(lang('global_sql_invalid', "FROM(delete)"));
        $SQL = "DELETE FROM " . $this->$from . ($this->$where ? " WHERE " . $this->$where : "");
        return $SQL;
    }

    //ִ��ɾ������
    function delete($clear_var=TRUE) {
        $SQL = $this->delete_sql();
        $this->exec($SQL);
        $this->_cache_sql();
        if($clear_var) {
            $this->clear();
        }
    }

    //���һ����
    function clear_table() {
        if(!$this->$from) show_error(lang('global_sql_invalid', 'FROM(Clear Table)'));
        $this->db->exec("TRUNCATE TABLE " . $this->$from);
    }

    //ɾ��һ����
    function drop_table() {
        if(!$this->$from) show_error(lang('global_sql_invalid', 'FROM(Dorp Table)'));
        $this->db->exec("DROP TABLE IF EXISTS " . $this->$from);
    }

    //����ǰ��sql���Ӿ�����
    function clear($name='ALL') {
        if($name == 'ALL') {
            foreach($this->var_fields as $v) {
                $this->$v = '';
            }
        } elseif(isset($name[$this->var_fields])) {
            $this->$name = '';
        }
    }
    
    //SQL����ع������ڻ����SQL�������óɵ�ǰ��Ч��SQL��֧�ֻع����ֲ���
    function sql_roll_back($vars = null) {
        $arr = $vars ? (is_array($vars) ? $vars : explode(',',$vars)) : $this->var_fields;
        foreach($arr as $v) {
            $n = 'cache_'.$v;
            $this->$v = $this->$n;
        }
        return $this;
    }

    //���⺬���sql����
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

    //ת������sql��ֵ
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

    //����SQL���Ӿ�����
    function _cache_sql($clear=FALSE) {
        foreach($this->var_fields as $v) {
            $n = 'cache_'.$v;
            $this->$n = $this->$v;
            if($clear) $this->$v = '';
        }
    }
}

