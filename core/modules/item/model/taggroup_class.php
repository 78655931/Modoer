<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_taggroup extends ms_model {
    
    var $table = 'dbpre_taggroup';
    var $key = 'tgid';
    var $cache_name = 'taggroup';
    var $auto_cache_write = TRUE;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    function msm_item_taggroup() {
        $this->__construct();
    }

    function write_cache() {
        $this->db->from($this->table);
        $result = array();
        if($query = $this->db->get()) {
            while($val = $query->fetch_array()) {
                $result[$val['tgid']] = $val;
            }
        }
        write_cache('taggroup', arrayeval($result), $this->model_flag);
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['name']) redirect('itemcp_taggeoup_empty_name');
        if(!$post['sort']) redirect('itemcp_taggeoup_empty_sort');
        if($post['sort'] == 2 && !$post['options']) redirect('itemcp_taggeoup_empty_option');
    }

    //导出标签组
    function export($tgid) {
        if(!$tgid) redirect(lang('global_sql_invalid','tgid'));
        if(!$detail = $this->read($tgid)) redirect(lang('item_taggroup_export_empty',$tgid));
        return $detail;
    }

    //导入标签组
    function import($array) {
        $replace = array();
        foreach($array as $val) {
            $tgid = $val['tgid'];
            unset($val['tgid']);
            $newtgid = $this->save($val);
            $replace[$tgid] = $newtgid;
        }
        return $replace;
    }
}
?>