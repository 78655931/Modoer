<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_config extends ms_model {

    var $table = 'dbpre_config';
    var $key = 'variable';

    function __construct() {
        parent::__construct();
    }

    function msm_config() {
        $this->__construct();
    }

    function &read($key, $module=null) {
        $where = array();
        $this->db->from($this->table);
        $this->db->where('variable', $key);
        $this->db->where('module', $module);
        if(!$row = $this->db->get()) {
            $result = '';
            return $result;
        }
        $result = $row->fetch_array();
        return $result;
    }

    function &read_all($module=null) {
        $where = array('module'=>$module ? $module : 'modoer');
        $row = $this->db->get_easy("*", $this->table, $where);
        $result = array();
        if(!$row) return $result;
        while($value = $row->fetch_array()) {
            $result[$value['variable']] = $value['value'];
        }
        return $result;
    }

    function save($setting, $module=null) {
        foreach($setting as $var => $val) {
            $this->db->from($this->table);
            $this->db->set('module', $module ? $module : 'modoer');
            $this->db->set('variable', $var);
            $this->db->set('value', $val);
            $this->db->insert(1); //replace
        }
        $js_cache = count($setting) > 1;
        $this->write_cache($module, $js_cache);
        //$this->write_allcache($js_cache);
    }

    function delete($variable,$module) {
        $this->db->from($this->table);
        $this->db->where('variable', $variable);
        $this->db->where('module', $module);
        $this->db->delete();
    }

    function write_cache($module=null,$js_cache = TRUE) {
        if(!$module) $module = 'modoer';
        if($module) $where = array('module' => $module);
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$row = $this->db->get()) {
            $result = array();
        } else {
            while($value = $row->fetch_array()) {
                $without = array('batchmsg');
                if(!isset($without[$value['variable']])) {
                    $result[$value['variable']] = $value['value'];
                }
            }
        }
        $result['model_flag_'.rand(1,10)] = $module;
        write_cache('config', arrayeval($result), $module);

        if($module == 'modoer' && $js_cache) {
            $this->write_js();
        }
    }

    function write_allcache($js_cache = FALSE) {
        $row = $this->db->from($this->table)->order_by('module')->get();
        if(!$row) {
            $result = array();
        } else {
            while ($value = $row->fetch()) {
                $without = array('batchmsg');
                if(!isset($without[$value['variable']])) {
                    $result[$value['module'].'|'.$value['variable']] = $value['value'];
                }
            }
            $row->free();
        }
        $content = strip_whitespace(arrayeval($result));
        write_cache('config_all', $content);
        if($js_cache) $this->write_js();
    }

    function write_js() {
        $detail = $this->read_all('modoer');

        $contents = '';
        $contents .= "var siteurl = '".$detail['siteurl']."';\n";
        $contents .= "var charset = '{$this->global[charset]}';\n";
        $contents .= "var cookiepre = '{$this->global[cookiepre]}';\n";
        $contents .= "var cookiepath = '{$this->global[cookiepath]}';\n";
        $contents .= "var cookiedomain = '{$this->global[cookiedomain]}';\n";
        $contents .= "var urlroot = '".URLROOT."';\n";
        $contents .= "var sitedomain = '".get_fl_domain()."';\n";
        $contents .= "var rewrite_mod = '".$detail['rewrite_mod']."';\n";
        $contents .= "\n";

        $M =& $this->loader->model('module');
        $row = $M->read_all(0);

        $contents .= "var modules = new Array();\n";
        while($value = $row->fetch_array()) {
            $contents .= "modules['$value[flag]'] = new Array();\n";
            $contents .= "modules['$value[flag]']['flag'] = '$value[flag]';\n";
            $contents .= "modules['$value[flag]']['name'] = '$value[name]';\n";
            $contents .= "modules['$value[flag]']['directory'] = '$value[directory]';\n\n";
        }
        $row->free_result();

        write_cache('config', $contents, $this->model_flag, 'js');
    }
}
?>