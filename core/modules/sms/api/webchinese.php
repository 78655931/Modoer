<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_webchinese extends msm_sms {

    protected $name = '�й�����SMS����ͨ';
    protected $descrption = '�й�����SMS����ͨ�����ʺ�ע�᣺<a href="http://sms.webchinese.cn/" target="_blank">sms.webchinese.cn</a>';

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

	//���Ͷ���Ϣ
    public function send($mobile, $message) {
		if(!$mobile) redirect('�ֻ����벻��Ϊ�ա�');
		if(!$message) redirect('�������ݲ���Ϊ�ա�');
		$params = $this->_createParam($mobile, $message);
		return $this->_send($params);
	}

    protected function create_from() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '�ʺ�',
            'des' => '��д�ʺ����ƣ�ע���ַ:http://sms.webchinese.cn/Login.shtml',
            'content' => form_input('webchinese_username', $this->config['webchinese_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '�ӿڰ�ȫ����',
            'des' => 'ע��ʱ��д�Ľӿڰ�ȫ���루�ɵ��û�ƽ̨�޸İ�ȫ���룩',
            'content' => form_input('webchinese_md5key', $this->config['webchinese_md5key'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['webchinese_username'] = _post('webchinese_username',null,MF_TEXT);
        $post['webchinese_md5key'] = _post('webchinese_md5key',null,MF_TEXT);
		if(!$post['webchinese_username']) redirect('�Բ�����δ���ýӿ��û�����');
		if(!$post['webchinese_md5key']) redirect('�Բ���δ���ýӿڰ�ȫ���롣');
        return $post;
    }

	//���ö����ʺ�
	private function set_account() {
        $this->username = $this->config['webchinese_username'];
        $this->key = $this->config['webchinese_md5key'];
	}

	//���ɶ��ŽӿڵĲ�����ʽ
    private function _createParam($mobile, $message) {
        $params = array (
            'Uid'		=> $this->username,
            'Key'		=> $this->key,
            'smsMob'	=> $mobile,
            'smsText'	=> $message,
        );
        return $params;
    }

	//ͨ��httpЭ���post����Ϣ��������api�ķ�����Ϣ(д��log�ļ����Ա����)
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
			$this->writeLog($this->errorCode, $this->errorMsg); //��¼���Ͳ��ɹ��ķ�����Ϣ
			return false;
		}
	}

	//�жϷ��صĶ���Ϣ�Ƿ��ͳɹ�
    private function _return($value=null) {
    	static $errlist = array(
    		'0'		=>'δ֪',
			'-1'	=>'û�и��û��˻�',
			'-2'	=>'��Կ����ȷ�������û����룩',
			'-3'	=>'������������',
			'-11'	=>'���û�������',
			'-14'	=>'�������ݳ��ַǷ��ַ�',
			'-4'	=>'�ֻ��Ÿ�ʽ����ȷ',
			'-41'	=>'�ֻ�����Ϊ��',
			'-42'	=>'��������Ϊ��',
    	);
		$value = trim($value);
		if (is_numeric($value) && $value > 0) {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $value;
            $this->errorMsg = '�ӿ���ʾ��' . $errlist[$value];
            return false;
        }
    }

}
?>