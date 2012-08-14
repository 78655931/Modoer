<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product_att_data extends ms_model {
    
    var $table = 'dbpre_productatt';
    var $key = 'id';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
        $this->modcfg = $this->variable('config');
    }

    function msm_product_att_data() {
        $this->__construct();
    }

    function check_atts($catid, $attids) {
        $cats = $this->loader->variable('att_cat','item');
        if(!isset($cats[$catid])) return '';
        $atts = $this->loader->variable('att_list_'.$catid, 'item');
        if(!is_array($attids)) $attids = array($attids);
        $result = array();
        foreach($attids as $attid) {
            if(isset($atts[$attid])) $result[] = $attid;
        }
        return !$result ? '' : implode(',', $result);
    }

    function save($pid, $attid) {
        if(!$pid) redirect(lang('global_sql_keyid_invalid','pid'));
        if(!$attid) redirect(lang('global_sql_keyid_invalid','attid'));
        if($this->exists($pid,$attid)) return;
        $this->db->from($this->table);
        $this->db->set('pid', $pid);
        $this->db->set('attid', $attid);
        //$this->db->set('att_catid', $catid);
        $this->db->insert();
    }

    function add($catid,$pid,$attids) {
        if(!$attids) return;
        if(!is_array($attids)) {
            if(!$attids = explode(',', $attids))return;
        }
        foreach($attids as $attid) {
            $this->db->from($this->table);
            $this->db->set('att_catid',$catid);
            $this->db->set('pid',$pid);
            $this->db->set('attid',$attid);
            $this->db->insert();
        }
        return $attids;
    }

    function replace($catid, $pid, $newatts, $oldatts) {
        $new = $old = true;
        if(!$newatts) $new = false;
        if(!$oldatts) $old = false;
        if(!$new && !$old) return;
        if(!$new && $old) {
            $this->delete($catid, $pid, $oldatts);
            return;
        } elseif($new && !$old) {
            $this->add($catid, $pid, $newatts);
            return;
        }
        //�Ա��µĺ;ɵ��Ƿ��д�����ͬ�ģ����������ͬ��ɾ��Ҳ�����
        $newatts = explode(',', $newatts);
        $oldatts = explode(',', $oldatts);
        $adds = $dels = $keeps = array();
        foreach($newatts as $_val) {
            if(!in_array($_val, $oldatts)) {
                $adds[] = $_val;
            }
        }
        foreach($oldatts as $_val) {
            if(!in_array($_val, $newatts)) {
                $dels[] = $_val;
            }
        }
        //���û�б仯���򷵻ؾ�����
        if(empty($dels) && empty($adds)) {
            return implode(',',$oldatts);
        }
        $this->delete( $catid, $pid, $dels);
        $result = $this->add($catid, $pid, $adds);
    }

    function delete($catid, $pid, $attids) {
        if(!$attids) return;
        if(!is_array($attids)) {
            if(!$attids = explode(',', $attids))return;
        }
        $this->db->from($this->table);
        //$this->db->where('att_catid',$catid);
        $this->db->where('pid',$pid);
        $this->db->where_in('attid', $attids);
        $this->db->delete();
    }

    function exists($pid, $attid) {
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->where('attid', $attid);
        return $this->db->count() > 0;
    }

    function delete_pid_catid($pid,$catid) {
        if(!$sid) return;
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->where('att_catid', $catid);
        $this->db->delete();
    }

    function delete_pid($pid) {
        if(!$pid) return;
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $this->db->delete();
    }

    function delete_attid($attid) {
        if(!$attid) return;
        $this->db->from($this->table);
        $this->db->where('attid', $attid);
        $this->db->delete();
    }

}
?>