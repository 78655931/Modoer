<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class msm_adv_list extends ms_model {

    var $table = 'dbpre_adv_list';
	var $key = 'adid';
    var $model_flag = 'adv';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'adv';
		$this->init_field();
        $this->modcfg = $this->variable('config');
		//$this->loader->helper('json');
	}

    function msm_adv_place() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('apid,city_id,adname,sort,begintime,endtime,config,ader,adtel,ademail,enabled,listorder,attr');
		$this->add_field_fun('apid,city_id,listorder', 'intval');
		$this->add_field_fun('begintime,endtime', 'strtotime');
        $this->add_field_fun('adname,sort,ader,adtel,ademail,enabled,attr', '_T');
	}

	function read($id,$select='*') {
		if(!$result = parent::read($id,$select)) return;
		$result['config'] = unserialize($result['config']);
		return $result;
	}

	function get_list($select,$where,$orderby,$start,$offset) {
		$result = array(0,'');
		$this->db->join($this->table,'al.apid','dbpre_adv_place','ap.apid');
		$this->db->where($where);
		if($result[0] = $this->db->count()) {
			$this->db->sql_roll_back('from,where');
			$this->db->select($select);
			$this->db->limit($start,$offset);
			$this->db->order_by($orderby);
			$result[1] = $this->db->get();
		}
		return $result;
	}

    function save($post, $apid = NULL) {
        $edit = $apid != null;
        if($edit) {
            if(!$detail = $this->read($apid)) redirect('adv_empty');
        }
		$sortfun = '_save_' . $post['sort'];
		if(!method_exists($this, $sortfun)) redirect('adv_post_sort_empty');
		$post['code'] = $this->$sortfun($post['config']);
		$post['config'] = serialize($post['config']);
        $apid = parent::save($post, $apid);
        return $apid;
    }

	function update($post) {
		if(!$post || !is_array($post)) redirect('global_op_unselect');
		foreach($post as $adid => $val) {
			$val['listorder'] = (int) $val['listorder'];
			$this->db->from($this->table);
			$this->db->set($val);
			$this->db->where('adid',$adid);
			$this->db->update();
		}
		$this->write_cache();
	}

    function delete($ids) {
        $ids = parent::get_keyids($ids);
        parent::delete($ids);
    }

    function delete_place($apids) {
        if(!$apids) return;
        $this->db->from($this->table);
        $this->db->where('apid',$apids);
        $this->db->delete();
    }

    function check_post(& $post, $apid = null) {
        if(!$post['apid']) redirect('adv_post_apid_empty');
        if(!$post['adname']) redirect('adv_post_adname_empty');
        if(!$post['sort']) redirect('adv_post_sort_empty');
        if(!$post['begintime']) redirect('adv_post_begintime_empty');
        //if(!is_numeric($post['endtime'])) redirect('adv_post_endtime_empty');
    }

    function write_cache() {
		if(method_exists($this->global['datacall'], 'delete_datacall_cache_module')) {
			$this->global['datacall']->delete_datacall_cache_module($this->model_flag);
		}
    }

	function _save_word(& $config) {
		foreach(array_keys($config) as $k) {
			if(substr($k,0,4)!='word') unset($config[$k]);
		}
		if(!$config['word_href']) redirect('adv_post_url_empty');
		if(!$config['word_title']) redirect('adv_post_word_text_empty');
        $code = '<a href="'.$config['word_href'].'" target="_blank"><span style="font-size:'.$config['word_size'].'px;">'.$config['word_title'].'</span></a>';
		return $code;
	}

	function _save_img(& $config) {
		foreach(array_keys($config) as $k) {
			if(substr($k,0,3)!='img') unset($config[$k]);
		}
		if(!$config['img_href']) redirect('adv_post_url_empty');
		if($img_src = $this->_upload_picture()) {
			$config['img_src'] = URLROOT . '/' . $img_src;
		} elseif(!$config['img_src']) {
			redirect('adv_post_img_src_empty');
		}
        $code = '<a href="'.$config['img_href'].'" alt="'.$config['img_title'].'" target="_blank"><img src="'.$config['img_src'].'" width="'.$config['img_width'].'" height="'.$config['img_height'].'" /></a>';
		return $code;
	}

	function _save_flash(& $config) {
		foreach(array_keys($config) as $k) {
			if(substr($k,0,5)!='flash') unset($config[$k]);
		}
		if(!$config['flash_src']) redirect('adv_post_url_empty');
        $code = '<embed width="'.$config['flash_width'].'" height="'.$config['flash_height'].'" src="'.$config['flash_src'].'" type="application/x-shockwave-flash"></embed>';
		return $code;
	}

	function _save_code(& $config) {
		foreach(array_keys($config) as $k) {
			if(substr($k,0,4)!='code') unset($config[$k]);
		}
		if(!$config['code']) redirect('adv_post_code_empty');
        $code = $config['code'];
		return $code;
	}

	function _upload_picture() {
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
			$img->set_max_size($this->global['cfg']['picture_upload_size']);
			$img->useSizelimit = false; //不对上传图片进行最大尺寸限制
			$img->set_ext($this->global['cfg']['picture_ext']);
			$img->upload('adv');
			return $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
        } else {
			return;
		}
	}


}
?>