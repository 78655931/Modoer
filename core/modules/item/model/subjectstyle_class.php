<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subjectstyle extends ms_model {

    var $table = 'dbpre_subjectstyle';
	var $key = 'id';
    var $model_flag = 'item';

    var $subject = null;
    var $default_setting = null;

    var $s = null;

	function __construct() {
		parent::__construct();
        !$this->modcfg && $this->modcfg = $this->variable('config');
        $day = (int)$this->modcfg['selltpl_useday'];
        $day < 1 && $this->modcfg['selltpl_useday'] = 180;
	}

    function my($sid,$load_all=true) {
        $this->db->join($this->table,'ss.templateid','dbpre_templates','t.templateid');
        $this->db->where('sid',$sid);
        if(!$load_all) {
            $this->db->where_more('endtime',$this->timestamp);
        }
        return $this->db->get();
    }

    function buy($sid,$templateid) {
        $tpl = $this->check_buy($sid,$templateid,false);
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('templateid',$templateid);
        $cd = $this->db->get_one();
        if($cd) {
            $des = '主题模板《'.$tpl['name'].'》续费';
            $this->_renew($cd['id']);
        } else {
            $des = '主题模板《'.$tpl['name'].'》购买';
            $this->_addnew($this->global['user']->uid,$sid,$templateid);
        }
        //记录积分扣除
        $P =& $this->loader->model('member:point');
        $P->update_point2($this->global['user']->uid, $this->modcfg['selltpl_pointtype'], -$tpl['price'], $des);
        return true;
    }

    function check_buy($sid,$templateid,$check_order=true) {
        if(!$this->loader->model('item:subject')->is_mysubject($sid,$this->global['user']->uid)) redirect('对不起，您不是当前主题的管理员。');
        $T =& $this->loader->model('template');
        if(!$tpl = $T->read($templateid)) {
            redirect('主题风格不存在.');
        }
        if($tpl['tpltype']!='item') redirect('对不起，这不是一个有效的主题风格！');
        //判断余额
        $pt = $this->modcfg['selltpl_pointtype'];
        $ptname = display('member:point',"point/$pt");
        if(!$pt) redirect('对不起，管理员未设置一个购买积分类型。');
        if($this->global['user']->$pt < $tpl['price']) redirect('对不起，您的 $ptname 余额不足！');
        $tpl['pointtype'] = $ptname;
        if($check_order) {
            $this->db->from($this->table);
            $this->db->where('sid',$sid);
            $this->db->where('templateid',$templateid);
            $cd = $this->db->get_one();
            if($cd) $tpl['purchased'] = 'Y';
        }
        return $tpl;
    }

    //个人助手内使用
    function update_status($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where_more('endtime',0);
        $this->db->where_less('endtime',$this->timestamp);
        $this->db->set('status',0);
        $this->db->update();
    }

    //主题页使用
    function check_style_endtime($sid,$templateid,$catid=null) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('templateid',$templateid);
        $result = $this->db->get_one();
        //if(empty($result)) return TRUE;//直接在主题表里修改的，不予判断和处理
        if(!$result['status'] || $result['endtime']<=$this->timestamp) {
            //到期还原
            $this->loader->model('item:subject')->set_template($sid, 0, $catid);
            //更新
            if($result['status']) {
                $this->db->from($this->table);
                $this->db->where('id',$result['id']);
                $this->db->set('status',0);
                $this->db->update();
            }
            return false;
        }
        return true;
    }

    function use_style($sid, $templateid) {
        if($templateid>0) {
            $this->db->from($this->table);
            $this->db->where('sid', $sid);
            $this->db->where('templateid', (int)$templateid);
            $cd = $this->db->get_one();
            if(empty($cd)) redirect('item_style_no_purchase');
            if(empty($cd['status'])) redirect('item_style_expired');
            $tpl = $this->loader->model('template')->read($templateid);
            if(empty($tpl) && $tpl['tpltype']!='item') redirect('item_style_invalid');
        }
        $this->loader->model('item:subject')->set_template($sid, (int)$templateid);
    }

    function get_exists($where) {
        return $this->db->where($where)
            ->from($this->table)->get_one();
    }

    function _addnew($uid,$sid,$templateid,$unit_num=1,$is_time=false) {
        $this->db->from($this->table);
        $this->db->set('sid', $sid);
        $this->db->set('templateid', $templateid);
        $this->db->set('buytime', $this->timestamp);
        $this->db->set('status', 1);
        if($is_time) {
            $datetime = $unit_num;
            $this->db->set('endtime', $datetime);
        } else {
            $day = (int)$this->modcfg['selltpl_useday'];
            $unit_num < 1 && $unit_num = 1;
            $datetime = $day * 3600 * 24 * $unit_num;
            $this->db->set('endtime', $this->timestamp + $datetime);
        }
        $this->db->insert();
    }

    function _renew($id,$unit_num=1,$is_time=false) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $this->db->set('status', 1);
        if($is_time) {
            $datetime = $unit_num;
            $this->db->set('endtime', $datetime);
        } else {
            $day = (int)$this->modcfg['selltpl_useday'];
            $unit_num < 1 && $unit_num = 1;
            $datetime = $day * 3600 * 24 * $unit_num;
            $this->db->set_add('endtime', $datetime);
        }
        $this->db->update();
    }

}
?>