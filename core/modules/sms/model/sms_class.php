<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
interface ISMS {
    public function send($mobile, $message);
}

abstract class msm_sms extends ms_base implements ISMS {

    protected $config = array();
    protected $name = '';
    protected $descrption = '';
    protected $apiname = '';

    protected $errorCode = 0;
    protected $errorMsg = '';
    protected $errorUrl = '';

    public function __construct($config) {
        parent::__construct();
        $this->config = $config;
    }

    public function get_name() {
        return $this->name;
    }

    public function get_descrption() {
        return $this->descrption;
    }

    public function get_form() {
        $content = '';
        $elements = $this->create_from();
        if($elements) foreach($elements as $item) {
            $content .= "<tr><td class=\"altbg1\"><strong>{$item[title]}:</strong>$item[des]</td>";
            $content .= "<td>$item[content]</td>";
        }
        return $content;
    }

    public function save_config() {
        $post = $this->check_from();
        $this->loader->model('config')->save($post, 'sms');
    }

    public function set_use() {
        $post = array('use_api' => $this->get_apiname());
        $this->loader->model('config')->save($post, 'sms');
    }

    public function is_used() {
        return $this->config['use_api'] == $this->get_apiname();
    }

    public function get_apiname() {
        return str_replace('msm_sms_', '', get_class($this));
    }

    public function get_error_code() {
        return $this->errorCode;
    }

    public function get_error_msg() {
        return $this->errorMsg;
    }

    public function get_error_url() {
        return $this->errorUrl;
    }

    public function send($mobile, $message) {}

    protected function writeLog() {
        $filename =  'sms_' . $this->get_apiname();
        $log = array(
            "datetime:\t" . strftime("%Y-%m-%d %H:%M:%S", $this->timestamp) . "\r\n",
            "url:\t" . $this->get_error_url() . "\r\n",
            "errorcode:\t" . $this->get_error_code() . "\r\n",
            "errorMsg:\t" . $this->get_error_msg() . "\r\n",
        );
        log_write($filename, $log);
    }

    protected function check_from() {}
    protected function create_from() {}
}
?>