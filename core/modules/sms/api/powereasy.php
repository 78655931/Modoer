<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_powereasy extends msm_sms {

    protected $name = '动易短信通';
    protected $descrption = '“动易短信通”服务平台整合移动、联通、电信和网通四网于一体，四大运营商短信全部覆盖，全国各地短信通行无阻；全面支持中国移动、中国联通和中国电信天翼用户。访问和注册动易短信通：<a href="http://sms.powereasy.net/" target="_blank">sms.powereasy.net</a>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://sms.powereasy.net/MessageGate/Message.aspx';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
        // if($this->global['charset']=='utf-8') {
        //     $this->gateurl = 'http://sms.powereasy.net/MessageGateUTF8/Message.aspx';
        // }
    }

    //发送短信息
    public function send($mobile, $message) {
        if(!$mobile) redirect('tuan_sms_mobile_empty');
        if(!$message) redirect('tuan_sms_content_empty');
        /*        
        if(strtolower($this->global['charset']) == 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese('utf-8', $this->global['charset']);
            $message = $message ? $CHS->Convert($message) : '';
        }*/
        $params = $this->_createParam($mobile, $message);
        return $this->_send($params);
    }

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '动易短信通帐号',
            'des' => '填写动易短信通帐号；注册地址:sms.powereasy.net；<br /><span class="font_1">注意不要注册<b>中文</b>帐号，避免因此造成帐号不存在的问题！</span>',
            'content' => form_input('powereasy_username', $this->config['powereasy_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '动易短信通MD5密钥',
            'des' => '在动易短信通的管理平台设置MD5密钥，并复制到此处，确保两边的密钥一致',
            'content' => form_input('powereasy_md5key', $this->config['powereasy_md5key'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['powereasy_username'] = _post('powereasy_username',null,MF_TEXT);
        $post['powereasy_md5key'] = _post('powereasy_md5key',null,MF_TEXT);
        if(!$post['powereasy_username']) redirect('未设置动易短信通接口用户名');
        if(!$post['powereasy_md5key']) redirect('未设置动易短信通接口MD5密钥');
        return $post;
    }

    //设置短信帐号
    private function set_account() {
        $this->username = $this->config['powereasy_username'];
        $this->key = $this->config['powereasy_md5key'];
        if($this->config['powereasy_customstr']) $this->customstr = $this->config['powereasy_customstr'];
        if($this->config['powereasy_gateurl']) $this->gateurl = $this->config['powereasy_gateurl'];
        if(strtolower($this->global['charset']) == 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese($this->global['charset'], 'gb2312');
            $this->username = $CHS->Convert($this->username);
        }
    }

    //生成短信接口的参数格式
    private function _createParam($mobile, $message) {

        //因动易对PHP的UTF-8编码不支持，因此专为gbk
        if(strtolower($this->global['charset']) == 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese($this->global['charset'], 'gb2312');
            $mobile = $CHS->Convert($mobile);
            $message = $CHS->Convert($message);
        }

        $id = $this->_create_id();
        $params = array (
            'ID'            => $id,
            'UserName'      => $this->username,
            'Key'           => $this->key,
            'SendNum'       => $mobile,
            'Content'       => $message,
            'SendTiming'    => 0,
            'SendTime'      => '',
            'Reserve'       => $this->customstr,
        );
        $params['MD5String'] = md5(implode('', $params));
        unset($params['Key']);
        return $params;
    }

    //生成一个短信发送的句柄id
    private function _create_id() {
        return $this->username . '_' . $this->timestamp . '_' . mt_rand(10000000,99999999);
    }

    //通过http协议的post短信息，并返回api的反馈信息(写入log文件，以便调试)
    private function _send($data) {
        $url = parse_url($this->gateurl);
        if (!isset($url['port'])) {
            $url['port'] = "";
        }
        if (!isset($url['query']))  { 
            $url['query'] = ""; 
        }
        $encoded = "";
        foreach($data as $k => $v) {
            $encoded .= ($encoded ? "&" : "");
            $encoded .= rawurlencode($k)."=".rawurlencode($v);
        }
        $fp = @fsockopen($url['host'], $url['port'] ? $url['port'] : 80);
        if (!$fp) {
            $this->writeLog("Failed to open socket to $url[host]");
            return 0;
        }
        fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
        fputs($fp, "Host: $url[host]\n");
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
        fputs($fp, "Content-length: " . strlen($encoded) . "\n");
        fputs($fp, "Connection: close\n\n");
        fputs($fp, "$encoded\n");
        $line = fgets($fp, 1024);
        if (!eregi("^HTTP/1\.. 200", $line)) {
            $this->writeLog($line);
            return 0;
        }
        $results = ""; $inheader = 1;
        while(!feof($fp))  {
            $line = fgets($fp, 1024);
            if ($inheader && ($line == "\n" || $line == "\r\n"))  {
                $inheader = 0;
            }
            if (!$inheader)  {
                $results .= $line;
            }
        }
        fclose($fp);
        $results = trim($results);
        $this->errorUrl = $encoded;
        if($this->_return($results)) {
            return true;
        } else {
            $this->writeLog($this->errorCode, $this->errorMsg); //记录发送不成功的返回信息
            return false;
        }
    }

    //判断返回的短信息是否发送成功
    private function _return($value=null) {
        //动易返回的判断信息是中文的，为了统一和繁体版的判断，故编码为MD5来进行对比判断
        if (md5($value) == 'fc4a5e487fc9d6d625cedad5e241fe95') {
            //vp(1);
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            //vp(2);
            $this->errorCode = strlen($value);
            $this->errorMsg = $value;
            if($this->errorMsg && strtolower($this->global['charset']) == 'utf-8') {
                $this->loader->lib('chinese', NULL, FALSE);
                $CHS = new ms_chinese('gbk','utf-8');
                $this->errorMsg = $CHS->Convert($this->errorMsg);
            }
        }
    }

}
?>