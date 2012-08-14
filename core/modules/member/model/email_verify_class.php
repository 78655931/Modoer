<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_email_verify extends ms_model {

	public $table = 'dbpre_email_verify';
    public $key = 'id';
    public $model_flag = 'member';

    private $uniq = '';
    private $serial = '';
    private $email = '';
    private $subject = '';
    private $content = 'content';

    private $timelife = 600;

    public function __construct() {
        parent::__construct();
        if(!$this->modcfg) $this->modcfg = $this->variable('config');
    }

    public function set_uniq($uniq) {
        $this->uniq = $uniq;
        return $this;
    }

    public function set_mail($mail) {
        $this->loader->helper('validate');
        if(!validate::is_email($mail)) {
            redirect('member_reg_ajax_email_invalid');
        }
        $this->mail = $mail;
        return $this;
    }

    public function set_serial($serial) {
        $this->serial = $serial;
        return $this;
    }

    public function set_content($subject, $content) {
        $search = array('$sitename','$serial');
        $replace = array(_G('cfg','sitename'), $this->serial);
        $this->subject = $subject;
        $this->content = str_replace($search, $replace, $content);
        return $this;
    }

    public function send() {
        $cfg = $this->global['cfg'];
        if($cfg['mail_use_stmp']) {
            $cfg['mail_stmp_port'] = $cfg['mail_stmp_port'] > 0 ? $cfg['mail_stmp_port'] : 25;
            $auth = ($cfg['mail_stmp_username'] && $cfg['mail_stmp_password']) ? TRUE : FALSE;
            $this->loader->lib('mail',null,false);
            $MAIL = new ms_mail($cfg['mail_stmp'], $cfg['mail_stmp_port'], $auth, $cfg['mail_stmp_username'], $cfg['mail_stmp_password']);
            $MAIL->debug = $cfg['mail_debug'];
            $succeed = @$MAIL->sendmail($this->email, $cfg['mail_stmp_email'], $this->subject, $this->content, 'TXT');
            unset($MAIL);
        } else {
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/plain;charset=" . _G('charset') . "\r\n";
            $succeed = @mail($this->email, $this->subject, $this->content, $headers);
        }

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
            ->set('email',$this->email)
            ->set('dateline',$this->timestamp)->insert();
        return $this->db->insert_id();
    }

    public function delete_time_limit() {
        $this->db->from($this->table)->where_less('dateline', $this->timestamp-$this->timelife)->delete();
    }

    private function create_serial() {
        $this->serial = mt_rand(100000,999999);
    }

}

/* end */