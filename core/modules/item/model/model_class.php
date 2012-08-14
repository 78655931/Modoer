<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_model extends ms_model {

    var $model_flag = 'item';
    var $table = 'dbpre_model';
    var $key = 'modelid';

    function __construct() {
        parent::__construct();
        $this->loader->helper('sql');
    }

    function msm_item_model() {
        $this->__construct();
    }

    function & model_list() {
        $this->db->select('modelid,name,tablename,item_name,item_unit,usearea');
        $this->db->from($this->table);
        $this->db->order_by(array('disable'=>'ASC','modelid'=>'ASC'));
        $row = $this->db->get();

        $result = array();

        if(!$row) return $result;
        while($value = $row->fetch_array()) {
            $result[] = $value;
        }
        $row->free_result();
        return $result;
    }

    function add($post, $export=false) {
        if(!preg_match("/^[a-z0-9_]+$/",$post['tablename'])) redirect('itemcp_model_tablename_invalid');
        if(strlen($post['tablename'])>20) redirect(lang('itemcp_model_tablename_charlen', 7));

        if(sql_exists_table($post['tablename'])) {
            redirect(lang('itemcp_model_table_exists', $this->global['dns']['dbpre'] . $post['tablename']));
        }

        if($_FILES['model_import_file']['name']) {
            $this->loader->lib('upload_file', NULL, FALSE);
            $UP = new ms_upload_file('model_import_file', 'xml');
            $xmlfile = $UP->get_filename();
            $this->loader->helper('mxml');
            if(!$import_array = mxml::to_array($xmlfile)) redirect('item_model_importfile_invalid');
        }

    
        $default_field = read_cache(MOD_ROOT . 'model' . DS . 'fields' . DS . 'defaultfield.php');
        if(empty($default_field)) {
            redirect(lang('itemcp_model_not_fount_defaultfield', 'model/fields/defaultfield.php'));
        }
        if($import_array) {
            foreach(array_keys($default_field) as $key) {
                if(isset($import_array['field'][$key])) $default_field[$key] = $import_array['field'][$key];
                unset($import_array['field'][$key]);
            }
        }

        if(!$post['usearea']) {
            // 去除地理特征字段
            unset($default_field['aid']);
            unset($default_field['mappoint']);
        }

        // 建立新模型，并返回模型id
        $modelid = parent::save($post);

        $F =& $this->loader->model(MOD_FLAG.':field');
        // 加入默认字段至字段信息索引表
        foreach($default_field as $key => $val) {
            $val['modelid'] = $modelid;
            $val['tablename'] = 'subject';
            // 字段记录,默认字段数据库不进行物理增加
            $F->add($val, false, false);
        }

        // 建立主题附加表
        $this->_create_model_table($post['tablename'], $this->_load_field_sql($post['usearea']));

        // 批量生成默认的字段
        if($import_array['field']) {
            $create_field =& $import_array['field']; //导入的文件
        } else {
            $createfile = MOD_ROOT . 'model' . DS . 'fields' . DS . 'createfield.php';
            if(is_file($createfile)) {
                $create_field = read_cache(MOD_ROOT . 'model' . DS . 'fields' . DS . 'createfield.php');
                if(empty($create_field)) {
                    redirect(lang('itemcp_model_not_fount_createfield', 'model/fields/createfield.php'));
                }
            }
        }

        // 存在需要导入的属性组
        if($import_array['att_cat']) {
            $A =& $this->loader->model('item:att_cat');
            $attcats = $A->import($import_array['att_cat']);
        }
        // 在村需要导入的标签组
        if($import_array['taggroup']) {
            $TG =& $this->loader->model('item:taggroup');
            $taggroups = $TG->import($import_array['taggroup']);
        }

        if($create_field) foreach($create_field as $key => $val) {
            $val['modelid'] = $modelid;
            $val['tablename'] = $post['tablename'];
            if($val['type']=='att') {
                $val['config']['catid'] = $attcats[$val['config']['catid']];
            } else {
                if($val['type']=='tag') $val['config']['groupid'] = $taggroups[$val['config']['groupid']];
            }
            $F->add($val, true, false); // 建立新字段
        }
        $F->write_cache();

        return $modelid;
    }

    function edit($post, $modelid) {
        $this->save($post, $modelid);
    }

    function delete($modelid) {
        if(empty($modelid)) redirect(lang('global_sql_keyid_invalid', 'modelid'));
        $model = $this->read($modelid);
        if(empty($model)) redirect('itemcp_model_not_found');

        $this->db->from('dbpre_category');
        $this->db->where('modelid', $modelid);
        if($this->db->count() > 0) {
            redirect(lang('itemcp_model_using', $model['name']));
        }

        // 删除字段记录
        $this->db->from('dbpre_subjectfield');
        $this->db->where('modelid', $modelid);
        $this->db->delete();
        @unlink(MUDDER_CACHE . $this->model_flag . '_field_' . $modelid . '.php');

        // 删除附属表
        $this->_delete_model_table($model['tablename']);

        // 删除点评项表数据
        //$this->db->from('dbpre_review_opt');
        //$this->db->where('modelid', $modelid);
        //$this->db->delete();
        //@unlink(MUDDER_CACHE . $this->model_flag . '_review_opt_' . $modelid . '.php');

        // 删除模型记录
        parent::delete($modelid);
    }

    function write_cache($moduleid = null) {
        $result = array();

        $this->db->from($this->table);
        $this->db->order_by('disable');
        $row = $this->db->get();

        if(!$row) return $result;
        while($value = $row->fetch_array()) {
            $result[$value['modelid']] = $value['name'];
            if(!$moduleid || $moduleid == $value['modelid']) {
                write_cache('model_' . $value['modelid'], arrayeval($value), $this->model_flag);
            }
        }
        $row->free_result();
        write_cache('model', arrayeval($result), $this->model_flag);
    }

    function export($modelid) {
		$content = $this->_export($modelid);
		ob_end_clean();
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Encoding: none');
		header('Content-Length: '.strlen($content));
        $filename = 'modoer_model_'. date('Y-m-d', $this->global[timestamp]) .'.xml';
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 
			'application/octet-stream'));
		echo $content;
		exit();
    }

    function _export($modelid) {
        if(!$modelid) redirect(lang('global_sql_invalid','modelid'));
        $F =& $this->loader->model('item:field');
        if(!$field_data = $F->export($modelid)) redirect('item_model_export_field_empty');
        $ATT_C =& $this->loader->model('item:att_cat');
        $TAG =& $this->loader->model('item:taggroup');
        $att_cat = $taggroup = array();
        foreach($field_data as $field) {
            if($field['type']=='att' && $field['config']['catid']>0) {
                $att_cat[$field['config']['catid']] = $ATT_C->export($field['config']['catid']);
            } elseif($field['type']=='tag' && $field['config']['groupid']>0) {
                $taggroup[$field['config']['groupid']] = $TAG->export($field['config']['groupid']);
            }
        }
        $data['modoer'] = $this->global['modoer'];
        if($att_cat) $data['att_cat'] = $att_cat;
        if($taggroup) $data['taggroup'] = $taggroup;
        $data['field'] = $field_data;
        $this->loader->helper('mxml');
        $xmlfile = mxml::from_array($data);
        return $xmlfile;
    }

    function _create_model_table($tablename, $fields) {
        sql_create_table($tablename, $fields);
        return sql_exists_table($tablename);
    }

    function _delete_model_table($tablename) {
        $this->db->exec("DROP TABLE IF EXISTS " . $this->global['dns']['dbpre'] . $tablename);
    }

    function _load_field_sql($usearea) {
        $file = 'model' . DS . 'fields' . DS . 'createsubject.php'; 
        if(!is_file(MOD_ROOT . $file)) {
            show_error(lang('global_file_not_exist', $file));
        }
        if(!$content = @file_get_contents(MOD_ROOT . $file)) {
            show_error(lang('global_file_not_exist', $file));
        }
        $content = str_replace('<?php exit(0);?>', '', $content);
        if(!$usearea) {
            $content = str_replace('areacode,', '', $content);
            $list = explode("\n", str_replace("\r", '', $content));
            foreach($list as $key => $val) {
                if(in_array(substr($val,0,8), array('areacode','mappoint'))) {
                    unset($list[$key]);
                }
            }
            $content = implode("\n", $list);
        }
        return $content;
    }
}
?>