<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_qxt extends msm_sms {

    protected $name = '����ͨ';
    protected $descrption = '����ͨ';

    private $username = '';
    private $key = '';
    private $customstr = '';
    private $gateurl = 'http://60.209.7.15:8080/smsServer/submit';

    public function __construct($config) {
        parent::__construct($config);
        $this->set_account();
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
            'des' => '��д�ʺ����ƣ�',
            'content' => form_input('qxt_username', $this->config['qxt_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '�ӿڰ�ȫ����',
            'des' => 'ע��ʱ��д������',
            'content' => form_input('qxt_md5key', $this->config['qxt_md5key'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => 'ģ����ʾ����',
            'des' => '�û��յ��Ķ���Ϣ��ʾ���룬ģ����볤��С��12λ',
            'content' => form_input('qxt_mnno', $this->config['qxt_mnno'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['qxt_username'] = _post('qxt_username',null,MF_TEXT);
        $post['qxt_md5key'] = _post('qxt_md5key',null,MF_TEXT);
        $post['qxt_mnno'] = _post('qxt_mnno',null,MF_TEXT);
		if(!$post['qxt_username']) redirect('�Բ�����δ���ýӿ��û�����');
        if(!$post['qxt_md5key']) redirect('�Բ���δ�����˺����롣');
		if(!$post['qxt_mnno']) redirect('�Բ���δ����ģ����ʾ���롣');
        return $post;
    }

	//���ö����ʺ�
	private function set_account() {
        $this->username = $this->config['qxt_username'];
        $this->key = $this->config['qxt_md5key'];
	}

	//���ɶ��ŽӿڵĲ�����ʽ
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

	//ͨ��httpЭ���post����Ϣ��������api�ķ�����Ϣ(д��log�ļ����Ա����)
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
            $this->writeLog($this->errorCode, $this->errorMsg); //��¼���Ͳ��ɹ��ķ�����Ϣ
            return false;
        }
        return $result;
	}

	//�жϷ��صĶ���Ϣ�Ƿ��ͳɹ�
    private function _return($value=null) {
        static $errlist = array(
            'SUCCESS'                   =>'�ύ�ɹ�',
            'USER_ERROR'                =>'��¼ʧ��',
            'USER_TYPE_ERROR'           =>'����IP��ע��IP����',
            'SEND_ERROR'                =>'����ʧ��',
            'SRCTREMID_LENGTH_ERROR'    =>'ģ����볤��С��12λ',
            'SRCTREMID_ERROR'           =>'ģ����벻�Ϸ�',
            'TIMEOUT'                   =>'��ʱ����7��',
        );
		$value = trim($value);
		if ($value == 'SUCCESS') {
            $this->errorCode = 0;
            $this->errorMsg = '';
            return TRUE;
        } else {
            $this->errorCode = $value;
            $this->errorMsg = '�ӿ���ʾ��' . $errlist[$value];
            return FALSE;
        }
    }

}
?>