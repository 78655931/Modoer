<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('pay:payment', FALSE);
class msm_pay_chinabank extends msm_pay_payment {

    var $notify_return = FALSE;
    var $notify_end = TRUE;

    function __construct() {
        parent::__construct();
        $this->config['return_url'] = _G('cfg','siteurl') . 'pay.php?act=notify&api=chinabank';
    }

    function get_unid() {
        $payid = $_POST['v_oid']? $_POST['v_oid'] : $_GET['v_oid'];
        return trim($payid);
    }

    function get_payment_orderid() {
        $orderid = $_POST['v_oid']? $_POST['v_oid'] : $_GET['v_oid'];
        return trim($orderid);
    }

    function goto_pay($payid,$unid) {
        if(!$pay = $this->pay->read($payid)) redirect('pay_order_empty');
        $price = $pay['price'];
        $title = $pay['order_name'];
        $content = $this->create_payurl($title, $price, $unid);
        if(!$content) redirect('pay_tenpay_url_empty');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<title>' . lang('pay_cb_title') . '</title>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">';
        echo '</head>';
        echo '<body onload="javascript:document.E_FORM.submit();">';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function check_payurl() {
        $retcode = 0;
        if (empty($this->config['cb_mid'])) {
            $retcode = -1;
            $retmsg  = 'pay_cb_mid_empty';
        }
        if (empty($this->config['cb_key'])) {
            $retcode = -2;
            $retmsg  = 'pay_cb_key_empty';
        }
        if($retcode < 0) redirect($retmsg);
        return $retcode;
    }

    function create_payurl($title, $price, $unid) {
        $this->check_payurl();
        ////"CNY", $czprice, $user->uid
        $moneytype = 'CNY';
        $mid = $this->config['cb_mid'];
        $url = $this->config['return_url'];
	    $text = $price . $moneytype . $unid . $mid . $url . $this->config['cb_key']; //md5 encode
        $md5info = strtoupper(md5($text));  //md5
        $content .= '<form method="post" name="E_FORM" action="https://pay3.chinabank.com.cn/PayGate">';
        $content .= '<input type="hidden" name="v_mid"         value="'.$mid.'">';
        $content .= '<input type="hidden" name="v_oid"         value="'.$unid.'">';
        $content .= '<input type="hidden" name="v_amount"      value="'.$price.'">';
        $content .= '<input type="hidden" name="v_moneytype"   value="'.$moneytype.'">';
        $content .= '<input type="hidden" name="v_url"         value="'.$url.'">';
        $content .= '<input type="hidden" name="v_md5info"     value="'.$md5info.'">';
        $content .= '<input type="hidden" name="remark1"       value="'.$unid.'">'; //user custom
        $content .= '<input type="hidden" name="remark2"       value="'.$unid.'">';
        $content .= '</form>';
        return $content;
    }

    function notify_check() {
        $v_oid = trim($_POST['v_oid']);       // 商户发送的v_oid定单编号   
        $v_pmode = trim($_POST['v_pmode']);    // 支付方式（字符串）   
        $v_pstatus = trim($_POST['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
        $v_pstring = trim($_POST['v_pstring']);   // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
        $v_amount = trim($_POST['v_amount']);     // 订单实际支付金额
        $v_moneytype = trim($_POST['v_moneytype']); //订单实际支付币种
        $v_md5str = trim($_POST['v_md5str' ]);   //拼凑后的MD5校验值

        $pay_remark1 = trim($_POST['remark1' ]);      //充值订单号
        $pay_remark2 = trim($_POST['remark2' ]);      //充值订单号

        $md5string = strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$this->config['cb_key']));

        $retcode = 0;
        $errormsg = 'pay_succeed';

        if($v_md5str != $md5string) {
            $retcode = 1;
            $errormsg = 'pay_cb_md5_invalid';
        }elseif($v_pstatus == "30") {
            $retcode = 30;
            $errormsg = $v_pstring;
        }elseif($v_pstatus != "20") {
            $retcode = $v_pstatus;
            $errormsg = $v_pstring;
        }

        if($retcode > 0) {
            //lost
            $this->_log_result ($v_oid . ':' . $errormsg . "\r\n" . $strResponseText);
            return FALSE;
        } else {
            //succeed
            return TRUE;
        }
    }

}
?>