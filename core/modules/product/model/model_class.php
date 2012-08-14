<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_model extends ms_model {

    var $model_flag = 'product';
    var $table = 'dbpre_product_model';
    var $key = 'modelid';

    function __construct() {
        parent::__construct();
        $this->loader->helper('sql');
    }

    function msm_product_model() {
        $this->__construct();
    }

    function model_list() {
        $this->db->select('modelid,name,tablename');
        $this->db->from($this->table);
        $this->db->order_by('disable');
        $row = $this->db->get();

        $result = array();

        if(!$row) return $result;
        while($value = $row->fetch_array()) {
            $result[] = $value;
        }
        $row->free_result();
        return $result;
    }

    function add($post) {

        if(!preg_match("/^[a-z0-9_]+$/i",$post['tablename'])) redirect('productcp_model_tablename_invalid');
        if(strlen($post['tablename'])>20) redirect(lang('productcp_model_tablename_charlen', 7));

        if(sql_exists_table($post['tablename'])) {
            redirect(lang('productcp_model_table_exists', $this->global['dns']['dbpre'] . $post['tablename']));
        }

        $default_field = read_cache(MOD_ROOT . 'model' . DS . 'fields' . DS . 'defaultfield.php');
        /*
        if(empty($default_field)) {
            redirect(lang('productcp_model_not_fount_defaultfield', 'model/fields/defaultfield.php'));
        }
        */

        // 建立新模型，并返回模型id
        $modelid = parent::save($post);

        $F =& $this->loader->model(MOD_FLAG.':field');
        // 加入默认字段至字段信息索引表
        if($default_field) foreach($default_field as $key => $val) {
            $val['idtype'] = 'product';
            $val['id'] = $modelid;
            $val['tablename'] = 'product';
            // 字段记录,默认字段数据库不进行物理增加
            $F->add($val, false, false);
        }

        // 建立主题附加表
        $this->_create_model_table($post['tablename'], $this->_load_field_sql(FALSE));

        // 批量生成默认的字段
        $ctratefile = MOD_ROOT . 'model' . DS . 'fields' . DS . 'createfield.php';
        if(is_file($ctratefile)) {
            $create_field = read_cache(MOD_ROOT . 'model' . DS . 'fields' . DS . 'createfield.php');
            if(empty($create_field)) {
                redirect(lang('productcp_model_not_fount_createfield', 'model/fields/createfield.php'));
            }
            foreach($create_field as $key => $val) {
                $val['idtype'] = 'product';
                $val['id'] = $modelid;
                $val['tablename'] = $post['tablename'];
                $F->add($val, true, false); // 建立新字段
            }
        }

        $F->write_cache();

        return $modelid;
    }

    function edit($post, $modelid) {
        $this->save($post, $modelid);
    }

    function delete($modelid) {
        if(empty($modelid)) redirect(sprintf(lang('global_sql_keyid_invalid'), 'modelid'));
        $model = $this->read($modelid);
        if(empty($model)) redirect('productcp_model_not_found');

        // 删除字段记录
        $this->db->from('dbpre_field');
        $this->db->where('idtype', 'product');
        $this->db->where('id', $modelid);
        $this->db->delete();
        @unlink(MUDDER_CACHE . $this->model_flag . '_field_' . $modelid . '.php');

        // 删除附属表
        $this->_delete_model_table($model['tablename']);

        // 删除模型记录
        parent::delete(array($modelid));
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

    function _create_model_table($tablename, $fields) {
        sql_create_table($tablename, $fields);
        return sql_exists_table($tablename);
    }

    function _delete_model_table($tablename) {
        $this->db->exec("DROP TABLE IF EXISTS " . $this->global['dns']['dbpre'] . $tablename);
    }

    function _load_field_sql($usearea) {
        $file = 'model' . DS . 'fields' . DS . 'createproduct.php'; 
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