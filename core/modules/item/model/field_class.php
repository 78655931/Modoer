<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_field extends ms_model {

    var $table = 'dbpre_subjectfield';
    var $key = 'fieldid';
    var $model_flag = 'item';
    var $fieldtypes;

    function __construct() {
        parent::__construct();
        $this->fieldtypes = read_cache(MUDDER_MODULE.'item'.DS.'model'.DS.'fields'.DS.'typelist.php');
        $this->loader->helper('sql');
    }

    function msm_item_field() {
        $this->__construct();
    }

    function get_fieldid($table,$fieldname) {
        $this->db->from($this->table);
        $this->db->where('tablename',$table);
        $this->db->where('fieldname',$fieldname);
        $this->db->select('fieldid');
        $r = $this->db->get_one();
        return (int)$r['fieldid'];
    }

    function field_list($modelid, $p_cfg = FALSE) {
        $this->db->select('fieldid,tablename,fieldname,type,title,unit,iscore,enablesearch,isadminfield,modelid,
            listorder,allownull,show_list,show_detail,show_side,disable');
        $this->db->from($this->table);
        $this->db->where('modelid', $modelid);
        $this->db->order_by(array('listorder'=>'ASC','fieldid'=>'ASC'));
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value = $row->fetch_array()) {
            if($p_cfg) {
                $value['config'] = unserialize($value['config']);
            }
            $result[] = $value;
        }
        $row->free_result();

        return $result;
    }

    function add($field, $createfield=true, $updatecache=true) {
        $modelid = $field['modelid'];
        foreach($field as $key => $val) {
            if(is_string($val)) $field[$key] = trim($val);
        }
        if(!$field['type']) {
            redirect(lang('admincp_field_empty'));
        } elseif(!array_key_exists($field['type'], $this->fieldtypes)) {
            redirect(lang('admincp_field_unkown_type', $field['type']));
        } elseif(!preg_match("/^[a-z0-9_]+$/",$field['fieldname'])) {
            redirect('admincp_field_name_invalid');
        } elseif($createfield && sql_exists_field($field['tablename'], $field['fieldname'])) {
            redirect(lang('admincp_field_exists_field', array($field['tablename'], $field['fieldname'])));
        }
        // 字段属性设置验证
        $FS =& $this->loader->model('item:fieldsetting');
        // 返回判断设置属性
        $field['config'] = $FS->setting($field['type'], $field['config']);
        if($createfield) {
            $field['iscore'] > 0 && $FS->datatype = $field['datatype'];
            $default = isset($FS->default) ? "DEFAULT '$FS->default'" : '';
        }
        $field['datatype'] = $FS->datatype;
        $field['config'] = serialize($field['config']);
        parent::save($field,null,false);

        // 物理创建
        if($createfield) sql_alter_field($field['tablename'], $field['fieldname'], 'ADD', "`{$field['fieldname']}` $FS->datatype NOT NULL $default");

        $updatecache && $this->write_cache();
        return $this->db->insert_id();
    }

    function edit($fieldid, $field, $alterField=false, $updatecache=true) {
        if(!$fieldid) return FALSE;
        foreach($field as $key => $val) {
            if(is_string($val)) $field[$key] = trim($val);
        }
        if(!$info = $this->read($fieldid)) {
            redirect('admincp_field_not_exists');
        } elseif($info['fieldname'] != 'mappoint' && !sql_exists_field($info['tablename'], $info['fieldname'])) {
            redirect(lang('admincp_field_not_exists_field', array($info['tablename'], $info['fieldname'])));
        }
        // 字段属性设置验证
        $FS = $this->loader->model('item:fieldsetting');
        // 返回序列化的设置属性
        $field['config'] = $FS->setting($info['type'], $field['config']);

        $fieldname = $field['fieldname'];
        if(isset($field['fieldname'])) unset($field['fieldname']);
        if($alterField) {
            $default = $FS->default != null ? "DEFAULT '$FS->default'" : '';
            sql_alter_field($info['tablename'], $fieldname, 'CHANGE', "`$fieldname` `$fieldname` $FS->datatype NOT NULL $default");
        }
        $field['datatype'] = $FS->datatype;
        $field['config'] = serialize($field['config']);
        parent::save($field, $fieldid);

        $updatecache && $this->write_cache();
        return TRUE;
    }

    function delete($fieldid, $updatecache=TRUE) {
        $fieldid = (int) $fieldid;
        if($fieldid < 1) return FALSE;

		if(!$field = $this->read($fieldid)) return FALSE;

	    //$this->db->exec("ALTER TABLE {$this->dbpre}{$field['tablename']} DROP {$field['fieldname']}");
        sql_alter_field($field['tablename'], $field['fieldname'], 'DROP', $field['fieldname']);
        parent::delete($fieldid,$updatecache);
    }

    function update($post) {
        if(!is_array($post) || empty($post)) return;
        foreach($post as $fieldid => $val) {
            $this->db->from($this->table);
            $val['allownull'] = (int) $val['allownull'];
            $val['show_list'] = (int) $val['show_list'];
            $val['show_detail'] = (int) $val['show_detail'];
            $val['show_side'] = (int) $val['show_side'];
            $this->db->set($val);
            $this->db->where('fieldid', $fieldid);
            $this->db->update();
        }
        $this->write_cache();
    }

	function disable($fieldid, $value) {
		$this->db->from($this->table);
		$this->db->set('disable', (int)$value);
		$this->db->where('fieldid', $fieldid);
		$this->db->update();
		$this->write_cache();
	}

    function from($modelid) {
        $fields = $this->field_list($modelid);
        $contents = '';
        $isadmin = defined("IN_ADMIN");

        $FF =& $this->loader->model('item:fieldform');
        foreach($fields as $val) {
            if($val['isadminfield'] && !$isadmin) continue;
            $contents .= $FF->form($val);
        }
        return $contents;
    }

    function validator($modelid, &$post, $sid=null) {
        $fields = $this->variable('field_' . $modelid);
        $FV =& $this->loader->model('item:fieldvalidator');
		$FV->all_data($post);
        if($sid && !$this->in_admin) {
            //判断是否为主题管理员
            $FV->ismysubject = $this->loader->model('item:subject')->is_mysubject($sid, $this->global['user']->uid);
        }
        $data = array();
        foreach($fields as $val) {
            $value = $FV->validator($val, $post[$val['fieldname']]);
            if(!$FV->leave) {
                //mappoint
                if($val['fieldname'] == 'mappoint' && isset($post[$val['fieldname']])) {
                    if($value) {
                        list($lng,$lat) = explode(',',$value);
                    } else {
                        $lng = $lat = 0;
                    }
                    $data['map_lng'] = $lng;
                    $data['map_lat'] = $lat;
                } else {
                    $data[$val['fieldname']] = $value;
                }
            }
        }
        return $data;
    }

    //导出字段
    function export($modelid) {
        $this->db->from($this->table);
        $this->db->where('modelid',$modelid);
        $this->db->order_by('fieldid');
        if(!$q = $this->db->get()) return;
        $result = array();
        while($v=$q->fetch_array()) {
            unset($v['fieldid'],$v['modelid'],$v['tablename']);
            $v['config'] = unserialize($v['config']);
            $result[$v['fieldname']] = $v;
        }
        $q->free_result();
        return $result;
    }

    function write_cache($modelid = null) {
        if($modelid) {
            $this->_write_cache($modelid);
            return;
        }
        $this->db->from('dbpre_model');
        if(!$row = $this->db->get()) return;
        while($value = $row->fetch_array()) {
            $this->_write_cache($value['modelid']);
        }
    }

    function _write_cache($modelid) {
        $this->db->from($this->table);
        $this->db->where('modelid', $modelid);
		$this->db->where('disable', 0);
        $this->db->order_by(array('listorder'=>'ASC', 'fieldid'=>'ASC'));
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;

        while($value = $row->fetch_array()) {
            $value['config'] = unserialize($value['config']);
            $result[$value['fieldid']] = $value;
        }
        $row->free_result();
        write_cache('field_' . $modelid, arrayeval($result), $this->model_flag);
    }

}
?>