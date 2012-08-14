<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_payment extends ms_base {

    var $config = array();      //支付模块配置信息
    var $pay = null;            //支付接口使用记录类

    var $notify_url = '';       //支付成功时，接口商发送的通知地址
    var $return_url = '';       //支付完成后的返回地址
    var $charset = '';          //编码
    var $use_log = true;        //是否记录支付接口操作日志（错误日志）

    var $notify_msg = '';       //准备向支付接口通知反馈信息
    var $notify_return = TRUE;  //是否发送通知反馈信息
    var $notify_end = FALSE;    //是否在接口发送通知反馈信息时结束流程（判断完毕后直接跳转callback_url）

    function __construct() {
        parent::__construct();
        $this->config = $this->loader->variable('config','pay');
        $this->charset = $this->global['charset'];
        $this->pay =& $this->loader->model(':pay');
    }

    //返回PAY表提供的包涵payid的惟一id
    function get_unid() {}

    //返回支付平台内部的订单ID
    function get_payment_orderid() {}

    //生成支付连接并进入支付页面
    function goto_pay($payid) {}

    //支付平台在支付成功后，向我方发布的通知，我方进行检查是否，并处理业务
    function notify_check() {}

    //有些支付平台需要在通知后，再有我方发送已经接收的信息，否则可能会定制向我方发送支付成功的通知
    function notify_return() {
        echo $this->notify_msg;
        exit;
    }

    //错误信息打印
    function halt($code, $message, $url) {
        $content = '<p>';
        $content .= '<b>message:</b>' . lang($message) . '<br /><br />';
        $content .= '<b>code:</b>' . $code . '<br /><br />';
        $content .= '<b>url:</b>' . $url;
        $content .= '</p>';
        show_error($content);
    }

    //跳转
    function goto_url($url) {
        $url = str_replace('&amp;','&', $url);
        $strMsg = "<html><head><meta name=\"Modoer Pay\" content=\"ModoerStudio\"></head>\n";
        $strMsg .= "<body><script language=javascript>\n";
        $strMsg .= "window.location.href='" . $url . "';\n";
        $strMsg .= "</script></body></html>";
        exit($strMsg);
    }

    //日志消息,把返回的参数记录下来
    function _log_result($word) {
        if(!$this->use_log) return;
        $content = "exec date：".strftime("%Y%m%d%H%M%S", $this->global['timestamp'])."\r\n".$word."\r\n";
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
            log_write('pay', '服务器PHP未开启 curl 库.');
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
