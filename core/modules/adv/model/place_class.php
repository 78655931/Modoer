<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_adv_place extends ms_model {

    var $table = 'dbpre_adv_place';
	var $key = 'apid';
    var $model_flag = 'adv';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'adv';
		$this->init_field();
        $this->modcfg = $this->variable('config');
	}

    function msm_adv_place() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('templateid,name,des,template,enabled');
		$this->add_field_fun('templateid', 'intval');
        $this->add_field_fun('name,link,des,enabled', '_T');
        $this->add_field_fun('template', '_HTML');
	}

    function save($post, $apid = NULL) {
        $edit = $apid != null;
        if($edit) {
            if(!$detail = $this->read($apid)) redirect('adv_place_empty');
			if($this->_check_name_exists($post['name'], $apid)) redirect('adv_place_name_exists');
        }
        $apid = parent::save($post, $apid);
        return $apid;
    }

    function delete($ids) {
        $ids = parent::get_keyids($ids);
        parent::delete($ids);
		//删除广告位内的广告数据
		$adv =& $this->loader->model('adv:list');
		$adv->delete_place($ids);
		unset($adv);
    }

    function check_post(& $post, $apid = null) {
        if(!$post['name']) redirect('adv_place_name_empty');
        if(!$post['template']) redirect('adv_place_template_empty');
		if(!$apid && $this->_check_name_exists($post['name'])) redirect('adv_place_name_exists');
    }

    function write_cache() {
        $result = array();
		$this->db->from($this->table);
		$this->db->where('enabled','Y');
		if($q = $this->db->get()) {
			while($v=$q->fetch_array()) {
				$this->_write_template($v['apid'], $v['template']);
				unset($v['template']);
				$result[$v['apid']] = $v;
			}
		}
        write_cache('place', arrayeval($result), $this->model_flag);
    }

	function _write_template($apid,$template) {
		$dir = MUDDER_ROOT . 'data' . DS . 'block';
		if(!is_dir($dir)) {
			if(!@mkdir($dir, 0777)) {
				show_error(lang('global_mkdir_no_access', str_replace(MUDDER_ROOT,'./',$dir)));
			}
		}
		$filename = "block_adv_$apid.htm";
		$file = $dir . DS . $filename;
		//多城市增加城市判断
		$replace = strpos($replace,'city_id/')===false ? ($apid . '/city_id/_NULL_CITYID_') : $apid;
		$template = str_replace('_APID_',$replace, $template);
		if(!$x = file_put_contents($file, $template)) {
			show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, '.'.DS, $file)));
		}
		@chmod($file, 0777);
	}

	function _check_name_exists($name, $outApid=null) {
		$this->db->from($this->table);
		$this->db->where('name',$name);
		if($outApid>0) $this->db->where_not_equal('apid',$outApid);
		return $this->db->count() > 0;
	}

}
?>