<?php
/**
 * sina weibo
 */
class ms_weibo {

    private $appkey     = '';
    private $appsecret  = '';
    private $token      = '';
    private $client     = null;

    public function __construct() {
        if(!class_exists('SaeTClientV2')) {
            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
        }
        $modcfg = _G('loader')->variable('config','member');
        $this->appkey = $modcfg['passport_weibo_appkey'];
        $this->appsecret = $modcfg['passport_weibo_appsecret'];

        $token = _G('loader')->model('member:passport')->get_token(_G('user')->uid, 'weibo');
        $this->token = $token['access_token'];
        $this->expired = $token['expired'];

        $this->client = new SaeTClientV2($this->appkey, $this->appsecret, $this->token);
    }

    private function check_token_expired() {
        return _G('timestamp') < $this->expired;
    }

    public function post_text($content = '') {
        if(!$this->check_token_expired()||!$content) return;
        if(strtoupper(_G('charset')) != 'UTF-8') {
            $content = charset_convert($content, _G('charset'), 'utf-8');
        }
        $result = $this->client->update($content);
        if($result['error_code']) {
            //error log
            $result['user'] = $this->global['user']->uid . "\t" . $this->global['user']->username;
            log_write('sina_weibo', $result);
            return false;
        }
        return true;
    }
}
?>