<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_bcastr extends ms_model {

	var $table = 'dbpre_bcastr';
    var $key = 'bcastr_id';

    function __construct() {
        parent::__construct();
        $this->init_field();
    }

    function msm_bcastr() {
		$this->__construct();
    }

    function init_field() {
        $this->add_field('groupname,city_id,available,itemtitle,link,item_url,orders');
        $this->add_field_fun('city_id,available,orders', 'intval');
        $this->add_field_fun('groupname,itemtitle,link,item_url', '_T');
    }

    function group_list() {
        $this->db->from($this->table);
        $this->db->select('groupname');
        $this->db->select('groupname', 'count', 'COUNT(?)');
        $this->db->group_by('groupname');
        if(!$q = $this->db->get()) return;
        $result = array();
        while($v=$q->fetch_array()) {
            $result[$v['groupname']]  = $v['count'];
        }
        return $result;
    }

    function save($post, $bcastr_id = null) {
        $edit = $bcastr_id != null;
        if($edit) {
            if(!$detail = $this->read($bcastr_id)) redirect('admincp_bcastr_empty');
        }
        //上传图片部分
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $img->upload('bcastr','');
            $post['link'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            @unlink(MUDDER_ROOT . $detail['link']);
        } elseif(!$edit) {
            redirect('global_upload_error_4');
        }
        return parent::save($post, $bcastr_id);
    }

    function check_post(& $post, $edit = FALSE, $update = FALSE) {
        if(!$post['groupname'] && !$update) redirect('admincp_bcastr_groupname_empty');
        if(!$post['itemtitle']) redirect('admincp_bcastr_itemtitle_empty');
        if(!$post['link'] && !$edit) redirect('admincp_bcastr_link_empty');
        if(!$post['item_url']) redirect('admincp_bcastr_item_url_empty');
    }

    function update($post) {
        if(!$post || !is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $val['available'] = (int) $val['available'];
            $this->check_post($val, true, true);
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('bcastr_id',$id);
            $this->db->update();
        }
        $this->write_cache();
    }

    function delete($ids) {
        $ids = $this->get_keyids($ids);
        $this->db->from($this->table);
        $this->db->where('bcastr_id', $ids);
        if(!$r = $this->db->get()) return;
        while($v=$r->fetch_array()) {
            @unlink(MUDDER_ROOT . $v['link']);
        }
        parent::delete($ids);
    }

    function write_cache() {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('available','1');
        $this->db->order_by('orders');
        $q = $this->db->get();
        if($q) while($v=$q->fetch_array()) {
            $result[$v['groupname']][] = $v;
        }
        write_cache('bcastr', arrayeval($result));
    }
}
?>