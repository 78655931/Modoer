<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_payment extends ms_base {

    var $config = array();      //֧��ģ��������Ϣ
    var $pay = null;            //֧���ӿ�ʹ�ü�¼��

    var $notify_url = '';       //֧���ɹ�ʱ���ӿ��̷��͵�֪ͨ��ַ
    var $return_url = '';       //֧����ɺ�ķ��ص�ַ
    var $charset = '';          //����
    var $use_log = true;        //�Ƿ��¼֧���ӿڲ�����־��������־��

    var $notify_msg = '';       //׼����֧���ӿ�֪ͨ������Ϣ
    var $notify_return = TRUE;  //�Ƿ���֪ͨ������Ϣ
    var $notify_end = FALSE;    //�Ƿ��ڽӿڷ���֪ͨ������Ϣʱ�������̣��ж���Ϻ�ֱ����תcallback_url��

    function __construct() {
        parent::__construct();
        $this->config = $this->loader->variable('config','pay');
        $this->charset = $this->global['charset'];
        $this->pay =& $this->loader->model(':pay');
    }

    //����PAY���ṩ�İ���payid��Ωһid
    function get_unid() {}

    //����֧��ƽ̨�ڲ��Ķ���ID
    function get_payment_orderid() {}

    //����֧�����Ӳ�����֧��ҳ��
    function goto_pay($payid) {}

    //֧��ƽ̨��֧���ɹ������ҷ�������֪ͨ���ҷ����м���Ƿ񣬲�����ҵ��
    function notify_check() {}

    //��Щ֧��ƽ̨��Ҫ��֪ͨ�������ҷ������Ѿ����յ���Ϣ��������ܻᶨ�����ҷ�����֧���ɹ���֪ͨ
    function notify_return() {
        echo $this->notify_msg;
        exit;
    }

    //������Ϣ��ӡ
    function halt($code, $message, $url) {
        $content = '<p>';
        $content .= '<b>message:</b>' . lang($message) . '<br /><br />';
        $content .= '<b>code:</b>' . $code . '<br /><br />';
        $content .= '<b>url:</b>' . $url;
        $content .= '</p>';
        show_error($content);
    }

    //��ת
    function goto_url($url) {
        $url = str_replace('&amp;','&', $url);
        $strMsg = "<html><head><meta name=\"Modoer Pay\" content=\"ModoerStudio\"></head>\n";
        $strMsg .= "<body><script language=javascript>\n";
        $strMsg .= "window.location.href='" . $url . "';\n";
        $strMsg .= "</script></body></html>";
        exit($strMsg);
    }

    //��־��Ϣ,�ѷ��صĲ�����¼����
    function _log_result($word) {
        if(!$this->use_log) return;
        $content = "exec date��".strftime("%Y%m%d%H%M%S", $this->global['timestamp'])."\r\n".$word."\r\n";
        $filename = str_replace('ms_', '', get_class($this));
        log_write($filename, $content);
    }

    function do_get($url) {
        $url = str_replace('&amp;', '&', $url);
        if (ini_get("allow_url_fopen") == "1") return file_get_contents($url);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result =  curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    function do_post($url, $params) {
        $fields_string = '';
        foreach($params as $key=>$value){
            $fields_string .="{$key}={$value}&";
        }
        rtrim($fields_string,'&');
        $xurl = $url . '?' . $fields_string;
        if(!function_exists('curl_exec')) {
            log_write('pay', '������PHPδ���� curl ��.');
            return false;
        }
        $ch = curl_init ($url."?");
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
        $result = curl_exec($ch);
        curl_close ($ch);  
        return $result;
    }

}
