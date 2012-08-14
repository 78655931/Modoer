<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_mobile_verify extends ms_model {

	public $table = 'dbpre_mobile_verify';
    public $key = 'id';
    public $model_flag = 'member';

    private $sms = null;
    private $uniq = '';
    private $serial = '';
    private $mobile = '';

    private $timelife = 600;

    public function __construct() {
        parent::__construct();
        if(!$this->modcfg) $this->modcfg = $this->variable('config');
        $this->get_sms();
    }

    public function set_uniq($uniq) {
        $this->uniq = $uniq;
        return $this;
    }

    public function set_mobile($mobile) {
        $this->loader->helper('validate');
        if(!validate::is_mobile($mobile)) {
            redirect('member_reg_ajax_mobile_invalid');
        }
        $this->mobile = $mobile;
        return $this;
    }

    public function set_serial($serial) {
        $this->serial = $serial;
        return $this;
    }

    public function send() {
        if(empty($this->serial)) $this->create_serial();
        $message = $this->modcfg['member_mobile_verify_message'];
        empty($message) && $message = lang('member_mobile_verify_message');
        $search = array('$sitename','$serial');
        $replace = array(_G('cfg','sitename'), $this->serial);
        $message = str_replace($search, $replace, $message);
        //$succeed = true;
        $succeed = $this->sms->send($this->mobile, $message);
        if($succeed) $this->save();
        return $succeed;
    }

    public function checking() {
        $det = $this->db->from($this->table)->where('uniq', $this->uniq)->get_one();
        if(!$det) return false;
        if($det['serial'] != $this->serial) return false;
        $this->db->from($this->table)->where('uniq', $this->uniq)->set('status', 1)->update();
        return true;
    }

    public function get_status() {
        $det = $this->db->from($this->table)->where('uniq', $this->uniq)->get_one();
        if($det && ($det['dateline'] + $this->timelife < $this->timestamp)) {
            $this->delete();
            return null;
        }
        return $det;
    }

    public function delete() {
        $this->db->from($this->table)->where('uniq', $this->uniq)->delete();
    }

    public function get_resend_time() {
        $v = $this->get_status();
        if(empty($v)) return 0;
        $time = 59 - ($this->timestamp - $v['dateline']);
        if($time>0 && $time<=59) return $time;
        return 0;
    }

    public function save() {
        $this->delete();
        $this->db->from($this->table)
            ->set('uniq',$this->uniq)
            ->set('serial',$this->serial)
            ->set('mobile',$this->mobile)
            ->set('dateline',$this->timestamp)->insert();
        return $this->db->insert_id();
    }

    public function delete_time_limit() {
        $this->db->from($this->table)->where_less('dateline', $this->timestamp-$this->timelife)->delete();
    }

    private function create_serial() {
        $this->serial = mt_rand(100000,999999);
    }

    private function get_sms() {
        $this->loader->model('sms:factory',null);
        $this->sms = msm_sms_factory::create();
    }

    //mobile_verify
}
?>