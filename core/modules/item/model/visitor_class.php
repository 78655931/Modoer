<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_visitor extends ms_model {
    
    var $table = 'dbpre_visitor';
    var $key = 'vid';

    var $interval = 5;  //seconds

    function __construct() {
        parent::__construct();
        $this->model_flag = 'item';
    }

    function visit($sid) {
        if(!$this->global['user']->uid) return;
        $last = $this->my_last($sid);
        if($last) {
            if($this->global['timestamp'] - $last['dateline'] > $this->interval) return;
            $this->db->from($this->table);
            $this->db->where('vid',$last['vid']);
            $this->db->set('dateline',$this->global['timestamp']);
            if(date('Ymd',$last['dateline'])<date('Ymd',$this->global['timestamp'])) {
                $this->db->set_add('total',1);
            }
            $this->db->update();
        } else {
            $this->db->from($this->table);
            $this->db->set('sid',$sid);
            $this->db->set('uid',$this->global['user']->uid);
            $this->db->set('username',$this->global['user']->username);
            $this->db->set('dateline',$this->global['timestamp']);
            $this->db->set('total',1);
            $this->db->insert();
        }
    }

    function my_last($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('uid',$this->global['user']->uid);
        return $this->db->get_one();
    }

}
?>