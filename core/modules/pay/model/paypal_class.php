<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('pay:payment', FALSE);
class msm_pay_paypal extends msm_pay_payment {

    var $gateway = "https://www.paypal.com/row/cgi-bin/webscr";
    var $mysgin = '';

    var $test = false; //sandbox

    function __construct() {
        parent::__construct();
        $this->notify_url = _G('cfg','siteurl') . 'pay.php?act=notify&api=paypal';//?act=notify&api=paypal
        //$this->notify_url = _G('cfg','siteurl') . 'paypal.php';
        $this->return_url = _G('cfg','siteurl') . 'pay.php?act=return&api=paypal';//
        if($this->test = !empty($this->config['paypal_test'])) {
            $this->gateway = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        }
    }

    function get_unid() {
        $payid = $_POST['out_trade_no']? $_POST['out_trade_no'] : $_GET['out_trade_no'];
        return trim($payid);
    }

    function get_payment_orderid() {
        $orderid = $_POST['trade_no']? $_POST['trade_no'] : $_GET['trade_no'];
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
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">';
        echo '</head>';
        echo '<body onload="javascript:document.paysubmit.submit();">';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function create_payurl($title, $price, $orderid) {
        //create param
        $parameter = array(
            "cmd"           => "_xclick", // "_xclick" buy
            "business"      => $this->config['paypal_email'], //PayPal acc
            "currency_code" => $this->config['paypal_currency'], //USD,EUR,GBP,CAD,JPY
            "amount"        => $price,
            "item_name"     => $title,
            "item_number"   => $orderid,
            "notify_url"    => $this->notify_url,
            "return"        => $this->return_url . '&act=return&item_number='.$orderid.'&total='.$price, //callback
            "charset"       => $this->charset,

            "on0"           => $this->create_sign($orderid . $this->config['paypal_email'] . $this->config['paypal_currency']),
        );
        //create order
        $content = "<form id='paysubmit' name='paysubmit' action='".$this->gateway."' method='post'>\r\n";
        foreach($parameter as $key => $val) {
            $content .= "<input type='hidden' name='".$key."' value='".$val."'/>\r\n";
        }
        $content .= '<span>Please wait a moment.....</span>';
        $content .= "</form>";
        return $content;
    }

    function notify_check() {
        $sign = $this->create_sign($_POST['item_number'] . $_POST['receiver_email'] . $_POST['mc_currency']);
        if(strcmp($_POST['option_name1'], $sign) == 0) {
            $orderid = $_POST['item_number'];           //modoer orderid
            $item_name = $_POST['item_name'];           //name
            $total = $_POST['mc_gross'];                //total price
            $port_orderid = $_POST['txn_id'];           //payapl orderid
            $receiver_email = $_POST['receiver_email']; //receive email
            $payer_email = $_POST['payer_email'];       //pay email
            $payment_currency = $_POST['mc_currency'];
            $payment_status = $_POST["payment_status"];

            if (trim($payment_status) != "Completed") {
                $this->_log_result ($orderid . ':' . $port_orderid . ' : ' . $payment_status);
                return false;
            } elseif($receiver_email != $this->config['paypal_email']) {
                $this->_log_result ($orderid . ':' . $port_orderid . ' : not equal: ' . $receiver_email);
                return false;
            } else {
                //$this->log->update($orderid, $total, $port_orderid);
                $this->_log_result ($orderid . ':' . $port_orderid . ' : OK!');
                return true;
            }
        } else {
            $this->_log_result ($orderid . ':' . $port_orderid . ' : INVALID');
            return false;
        }
    }

    function create_sign($str) {
        return md5($this->global['cfg']['authkey'] . $str);
    }
}