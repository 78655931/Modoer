<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_webchinese extends msm_sms {

    protected $name = '中国网建SMS短信通';
    protected $descrption = '中国网建SMS短信通。访问和注册：<a href="http://sms.webchinese.cn/" target="_blank">sms.webchinese.cn</a>';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://gbk.sms.webchinese.cn/';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
        if(strtolower($this->global['charset']) == 'utf-8') {
        	$this->gateurl = 'http://utf8.sms.webchinese.cn/';
        } else {
        	$this->gateurl = 'http://gbk.sms.webchinese.cn/';
        }
    }

	//发送短信息
    public function send($mobile, $message) {
		if(!$mobile) redirect('手机号码不能为空。');
		if(!$message) redirect('短信内容不能为空。');
		$params = $this->_createParam($mobile, $message);
		return $this->_send($params);
	}

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '帐号',
            'des' => '填写帐号名称；注册地址:http://sms.webchinese.cn/Login.shtml',
            'content' => form_input('webchinese_username', $this->config['webchinese_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '接口安全密码',
            'des' => '注册时填写的接口安全密码（可到用户平台修改安全密码）',
            'content' => form_input('webchinese_md5key', $this->config['webchinese_md5key'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['webchinese_username'] = _post('webchinese_username',null,MF_TEXT);
        $post['webchinese_md5key'] = _post('webchinese_md5key',null,MF_TEXT);
		if(!$post['webchinese_username']) redirect('对不起，您未设置接口用户名。');
		if(!$post['webchinese_md5key']) redirect('对不起，未设置接口安全密码。');
        return $post;
    }

	//设置短信帐号
	private function set_account() {
        $this->username = $this->config['webchinese_username'];
        $this->key = $this->config['webchinese_md5key'];
	}

	//生成短信接口的参数格式
    private function _createParam($mobile, $message) {
        $params = array (
            'Uid'		=> $this->username,
            'Key'		=> $this->key,
            'smsMob'	=> $mobile,
            'smsText'	=> $message,
        );
        return $params;
    }

	//通过http协议的post短信息，并返回api的反馈信息(写入log文件，以便调试)
	private function _send($data) {
		if($data) foreach($data as $k => $v) {
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k)."=".rawurlencode($v);
		}
		$url = $this->gateurl . '?' . $encoded;
		if(function_exists('file_get_contents')){
			$results = file_get_contents($url);
		} else {
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$results = curl_exec($ch);
			curl_close($ch);
		}
        $this->errorUrl = $url;
		if($this->_return($results)) {
			return true;
		} else {
			$this->writeLog($this->errorCode, $this->errorMsg); //记录发送不成功的返回信息
			return false;
		}
	}

	//判断返回的短信息是否发送成功
    private function _return($value=null) {
    	static $errlist = array(
    		'0'		=>'未知',
			'-1'	=>'没有该用户账户',
			'-2'	=>'密钥不正确（不是用户密码）',
			'-3'	=>'短信数量不足',
			'-11'	=>'该用户被禁用',
			'-14'	=>'短信内容出现非法字符',
			'-4'	=>'手机号格式不正确',
			'-41'	=>'手机号码为空',
			'-42'	=>'短信内容为空',
    	);
		$value = trim($value);
		if (is_numeric($value) && $value > 0) {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $value;
            $this->errorMsg = '接口提示：' . $errlist[$value];
            return false;
        }
    }

}
?>