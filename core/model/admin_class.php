<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

class msm_admin extends ms_model {

	var $table = 'admin';
    var $key = 'id';

    var $licensed = false;
    var $license = array();

    function __construct() {
        global $module,$act;
        parent::__construct();
        $this->table = $this->global['dns']['dbpre'] . $this->table;
    }

    function msm_admin() {
        $this->__construct();
    }

    function save($post,$adminid=null) {
        $edit = $adminid != null;
        if($this->global['admin']->is_founder) {
            if($this->global['admin']->id == $adminid && $post['closed']) redirect('admincp_admin_cannot_colsed');
            $post['mycitys'] = is_array($post['mycitys']) ? implode(',',$post['mycitys']) : '';
            $post['mymodules'] = is_array($post['mymodules']) ? implode(',',$post['mymodules']) : '';
        } else {
            unset($post['is_founder'],$post['mymodules'],$post['colsed']);
        }
        parent::save($post,$adminid);
    }

    function & find($id, $start, $offset=20) {
        $result = array();

        $this->db->from($this->table);
        if($id > 0) {
            $this->db->where_not_equal("id", $id);
        }

        $result[] = $this->db->count();
        $this->db->sql_roll_back('from,where');
        $this->db->limit($start, $offset);
        
        $result[] = $this->db->get();
        return $result;
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['adminname'] && !$edit) redirect('admincp_admin_name_empty');
        if(!$post['password'] && !$edit) redirect('admincp_admin_password_empty');
        if(!$post['email']) redirect('admincp_admin_email_empty');
        if(!$edit) {
            $this->db->from($this->table);
            $this->db->where("adminname", $post['adminname']);
            if($this->db->count()>0) redirect('admincp_admin_exists_name');
        }
    }

    function get_founder() {
        $this->db->from($this->table);
        $this->db->where('is_founder','Y');
        return $this->db->get_one();
    }

}
?>