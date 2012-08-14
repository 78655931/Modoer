<?php
/**
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2011 Moufersoft
 * @website www.modoer.com
 */
class msm_sms_powereasy extends msm_sms {

    protected $name = '���׶���ͨ';
    protected $descrption = '�����׶���ͨ������ƽ̨�����ƶ�����ͨ�����ź���ͨ������һ�壬�Ĵ���Ӫ�̶���ȫ�����ǣ�ȫ�����ض���ͨ�����裻ȫ��֧���й��ƶ����й���ͨ���й����������û������ʺ�ע�ᶯ�׶���ͨ��<a href="http://sms.powereasy.net/" target="_blank">sms.powereasy.net</a>';

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

    //���Ͷ���Ϣ
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
            'title' => '���׶���ͨ�ʺ�',
            'des' => '��д���׶���ͨ�ʺţ�ע���ַ:sms.powereasy.net��<br /><span class="font_1">ע�ⲻҪע��<b>����</b>�ʺţ������������ʺŲ����ڵ����⣡</span>',
            'content' => form_input('powereasy_username', $this->config['powereasy_username'], 'txtbox2'),
        );
        $elements[] = 
            array(
            'title' => '���׶���ͨMD5��Կ',
            'des' => '�ڶ��׶���ͨ�Ĺ���ƽ̨����MD5��Կ�������Ƶ��˴���ȷ�����ߵ���Կһ��',
            'content' => form_input('powereasy_md5key', $this->config['powereasy_md5key'], 'txtbox2'),
        );
        return $elements;
    }

    protected function check_from() {
        $post = array();
        $post['powereasy_username'] = _post('powereasy_username',null,MF_TEXT);
        $post['powereasy_md5key'] = _post('powereasy_md5key',null,MF_TEXT);
        if(!$post['powereasy_username']) redirect('δ���ö��׶���ͨ�ӿ��û���');
        if(!$post['powereasy_md5key']) redirect('δ���ö��׶���ͨ�ӿ�MD5��Կ');
        return $post;
    }

    //���ö����ʺ�
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

    //���ɶ��ŽӿڵĲ�����ʽ
    private function _createParam($mobile, $message) {

        //���׶�PHP��UTF-8���벻֧�֣����רΪgbk
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

    //����һ�����ŷ��͵ľ��id
    private function _create_id() {
        return $this->username . '_' . $this->timestamp . '_' . mt_rand(10000000,99999999);
    }

    //ͨ��httpЭ���post����Ϣ��������api�ķ�����Ϣ(д��log�ļ����Ա����)
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
            $this->writeLog($this->errorCode, $this->errorMsg); //��¼���Ͳ��ɹ��ķ�����Ϣ
            return false;
        }
    }

    //�жϷ��صĶ���Ϣ�Ƿ��ͳɹ�
    private function _return($value=null) {
        //���׷��ص��ж���Ϣ�����ĵģ�Ϊ��ͳһ�ͷ������жϣ��ʱ���ΪMD5�����жԱ��ж�
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