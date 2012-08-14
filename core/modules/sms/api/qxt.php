<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_qxt extends msm_sms {

    protected $name = '企信通';
    protected $descrption = '企信通';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://60.209.7.15:8080/smsServer/submit';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
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
            'des' => '填写帐号名称；',
            'content' => form_input('qxt_username', $this->config['qxt_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '接口安全密码',
            'des' => '注册时填写的密码',
            'content' => form_input('qxt_md5key', $this->config['qxt_md5key'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '模拟显示号码',
            'des' => '用户收到的短信息显示号码，模拟号码长度小于12位',
            'content' => form_input('qxt_mnno', $this->config['qxt_mnno'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['qxt_username'] = _post('qxt_username',null,MF_TEXT);
        $post['qxt_md5key'] = _post('qxt_md5key',null,MF_TEXT);
        $post['qxt_mnno'] = _post('qxt_mnno',null,MF_TEXT);
		if(!$post['qxt_username']) redirect('对不起，您未设置接口用户名。');
        if(!$post['qxt_md5key']) redirect('对不起，未设置账号密码。');
		if(!$post['qxt_mnno']) redirect('对不起，未设置模拟显示号码。');
        return $post;
    }

	//设置短信帐号
	private function set_account() {
        $this->username = $this->config['qxt_username'];
        $this->key = $this->config['qxt_md5key'];
	}

	//生成短信接口的参数格式
    private function _createParam($mobile, $message) {
        $params = array (
            'CORPID'	=> $this->username,
            'CPPW'		=> $this->key,
            'PHONE'	    => $mobile,
            'CONTENT'   => $message,
            'SRCTERMID'	=> $this->config['qxt_mnno'],
        );
        return $params;
    }

	//通过http协议的post短信息，并返回api的反馈信息(写入log文件，以便调试)
	private function _send($data) {
        foreach($data as $key=>$value) { 
            $fields_string .= $key.'='.$value.'&' ; 
        }
        rtrim($fields_string ,'&') ;
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->gateurl);
        curl_setopt($ch, CURLOPT_POST, count($data));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        $this->errorUrl = $this->gateurl;
        if($this->_return($result)) {
            return true;
        } else {
            $this->writeLog($this->errorCode, $this->errorMsg); //记录发送不成功的返回信息
            return false;
        }
        return $result;
	}

	//判断返回的短信息是否发送成功
    private function _return($value=null) {
        static $errlist = array(
            'SUCCESS'                   =>'提交成功',
            'USER_ERROR'                =>'登录失败',
            'USER_TYPE_ERROR'           =>'请求IP与注册IP不符',
            'SEND_ERROR'                =>'发送失败',
            'SRCTREMID_LENGTH_ERROR'    =>'模拟号码长度小于12位',
            'SRCTREMID_ERROR'           =>'模拟号码不合法',
            'TIMEOUT'                   =>'定时超过7天',
        );
		$value = trim($value);
		if ($value == 'SUCCESS') {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $value;
            $this->errorMsg = '接口提示：' . $errlist[$value];
            return FALSE;
        }
    }

}
?>