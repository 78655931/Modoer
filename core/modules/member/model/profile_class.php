<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_profile extends ms_model {

	public $table = 'dbpre_member_profile';
    public $key = 'uid';
    public $model_flag = 'member';

    private $uid = 0;
    private $fields = array('realname','gender','birthday','alipay','qq','msn','address','zipcode');
    private $vars = array();

    public function __construct() {
        parent::__construct();
    }

    public function set_uid($uid) {
        $this->uid = $uid;
        return $this;
    }

    public function read($uid=null) {
        $uid = (int) $uid;
        if($uid > 0) $this->uid = $uid;
        $detail = parent::read($this->uid);
        if(!$detail) return false;
        foreach ($detail as $key => $value) {
            $this->$key = $value;
        }
        return $detail;
    }

    public function __set($key, $value) {
        if(in_array($key, $this->fields)) {
            $this->vars[$key] = $this->check_post($key, $value);
        }
    }

    public function __get($key) {
        return $this->vars[$key];
    }

    public function save() {
        if(!$this->vars||!$this->uid) return;
        $exists = $this->check_exists();

        $this->db->from($this->table);
        $this->db->set($this->vars);
        if($exists) {
            $this->db->where('uid',$this->uid);
            $this->db->update();
        } else {
            $this->db->set('uid',$this->uid);
            $this->db->insert();
        }
    }

    public function save_alipay($alipay) {
        if(!$this->uid) return;
        $exists = $this->check_exists();

        $this->db->from($this->table);
        $this->db->set('alipay', $alipay);
        if($exists) {
            $this->db->where('uid',$this->uid);
            $this->db->update();
        } else {
            $this->db->set('uid',$this->uid);
            $this->db->insert();
        }
    }

    public function check_exists() {
        $this->db->from($this->table);
        $this->db->where('uid',$this->uid);
        return $this->db->count() >= 1;
    }

    public function check_post($key, $value) {
        $value = trim($value);
        if($key == 'gender') {
            $value = (int) $value;
            if($value < 0 or $value > 2) $value = 0;
        } elseif ($key == 'birthday' && $value) {
            if(!preg_match("/[0-9]{4}\-[0-9]{2}\-[0-9]{2}/", $value) || !strtotime($value)) {
                redirect('对不起，您填写的生日未填写或格式错误。');
            }
        } elseif ($key == 'qq' && $value) {
            if(!preg_match("/^[0-9]{5,13}$/", $value) || $value{0} === '0') {
                redirect('对不起，您填写的QQ号格式有误。');
            }
         } elseif ($key == 'alipay' && $value) {
            $this->loader->helper('validate');
            if(!validate::is_email($value)) {
                redirect('对不起，您填写的支付宝账号格式有误。');
            }
        } elseif ($key == 'msn' && $value) {
            $this->loader->helper('validate');
            if(!validate::is_email($value)) {
                redirect('对不起，您填写的MSN账号格式有误。');
            }
        } elseif ($key == 'zipcode' && $value) {
            if(!preg_match("/^[A-Z0-9\s]{3,}$/", $value) || $value{0} === '0') {
                redirect('对不起，您填写的邮编格式有误。');
            }
        } else {
            $value = _T($value);
        }
        return $value;
    }
}
?>