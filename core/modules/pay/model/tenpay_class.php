<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('pay:payment', FALSE);
class msm_pay_tenpay extends msm_pay_payment {

    var $notify_return = FALSE;
    var $notify_end = TRUE;

    function __construct() {
        parent::__construct();
        $this->config = $this->loader->variable('config','pay');
        $this->config['tenpay_dir'] = URLROOT . '/core/modules/pay/model/pay';
        $this->config['site_name'] = _G('cfg','sitename');
        $this->config['return_url'] = _G('cfg','siteurl') . 'pay.php?act=notify&api=tenpay';
        $this->config['pay_url'] = "https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi";
    }

    function get_unid() {
        $payid = $_POST['sp_billno']? $_POST['sp_billno'] : $_GET['sp_billno'];
        return trim($payid);
    }

    function get_payment_orderid() {
        $orderid = $_POST['transaction_id']? $_POST['transaction_id'] : $_GET['transaction_id'];
        return trim($orderid);
    }

    function goto_pay($payid,$unid) {
        if(!$pay = $this->pay->read($payid)) redirect('pay_order_empty');
        $price = $pay['price'] * 100; //分表示价格单位
        $title = $pay['order_name'];
        $content = $this->create_payurl($title, $price, $unid);
        if(!$content) redirect('pay_tenpay_url_empty');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">';
        echo '</head>';
        echo '<body onload="javascript:document.E_FORM.submit();">';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function check_payurl() {
        $retcode = 0;
        if (empty($this->config['spid'])) {
            $retcode = -1;
            $retmsg  = "tenpay_spid_empty";
        }
        if (empty($this->config['spkey'])) {
            $retcode = -2;
            $retmsg  = "tenpay_spkey_empty";
        }
        if (empty($this->config['return_url'])) {
            $retcode = -3;
            $retmsg  = "tenpay_return_url_empty";
        }
        if (empty($this->config['tenpay_dir'])) {
            $retcode = -4;
            $retmsg  = "tenpay_tenpay_dir_empty";
        }
        if (empty($this->config['site_name'])) {
            $retcode = -5;
            $retmsg = "tenpay_site_name_empty";
        }
        if($retcode < 0) redirect($retmsg);
        return $retcode;
    }

    function create_payurl($title, $price, $unid) {
        $this->check_payurl();
        $retcode = 0;
        $bank_type = '0';
        $purchaser_id = '';
        $sp_billno = $this->global['timestamp'];
        if (empty($price)) {
            $retcode = -1;
            $retmsg  = 'pay_tenpay_price_empty';
        }elseif (empty($title)) {
            $retcode = -2;
            $desc = 'pay_tenpay_title_empty';
        }elseif (empty($unid)) {
            $retcode = -3;
            $retmsg = "pay_tenpay_orderid_empty";
        }
        if($retcode < 0) redirect($retmsg);

        $transaction_id = $this->config['spid'] . date('Ymd', $this->global['timestamp']) . $sp_billno;
        $sign_text = "cmdno=1" . "&date=" . date('Ymd', $this->global['timestamp']) . "&bargainor_id=" . $this->config['spid'] ."&transaction_id=" . 
            $transaction_id . "&sp_billno=" . $unid . "&total_fee=" . $price . "&fee_type=1"  . "&return_url=" . 
            $this->config['return_url'] . "&attach=" . $unid . '&spbill_create_ip=' . $this->global['ip'];
        $strSign = strtoupper(md5($sign_text . "&key=" . $this->config['spkey']));

        if(strtolower($this->charset) == 'utf-8') {
            $this->loader->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese('utf-8', $this->charset);
            $title = $CHS->Convert($title);
        }

        $content = '<form method="post" name="E_FORM" action="'.$this->config['pay_url'].'">';
        $content .= '<input type="hidden" name="cmdno"              value="1">';
        $content .= '<input type="hidden" name="date"               value="'.date('Ymd', $this->global['timestamp']).'">';
        $content .= '<input type="hidden" name="bank_type"          value="'.$bank_type.'">';
        $content .= '<input type="hidden" name="desc"               value="'.$title.'">';
        $content .= '<input type="hidden" name="purchaser_id"       value="">';
        $content .= '<input type="hidden" name="bargainor_id"       value="'.$this->config['spid'].'">';
        $content .= '<input type="hidden" name="transaction_id"     value="'.$transaction_id.'">';
        $content .= '<input type="hidden" name="sp_billno"          value="'.$unid.'">';
        $content .= '<input type="hidden" name="total_fee"          value="'.$price.'">';
        $content .= '<input type="hidden" name="fee_type"           value="1">';
        $content .= '<input type="hidden" name="return_url"         value="'.$this->config['return_url'].'">';
        $content .= '<input type="hidden" name="attach"             value="'.$unid.'">';
        $content .= '<input type="hidden" name="spbill_create_ip"   value="'.$this->global['ip'].'">';
        $content .= '<input type="hidden" name="sign"               value="'.$strSign.'">';
        $content .= '</form>';
        //$redurl = $this->config['pay_url'] . "?". $sign_text . "&sign=" . $strSign ."&desc=" . $title . "&bank_type=" . $bank_type;
        return $content;
    }

    function notify_check() {

        /*return*/
        $strCmdno			= $_GET['cmdno'];
        $strPayResult		= $_GET['pay_result'];
        $strPayInfo		    = $_GET['pay_info'];
        $strBillDate		= $_GET['date'];
        $strBargainorId	    = $_GET['bargainor_id'];
        $strTransactionId	= $_GET['transaction_id'];
        $strSpBillno		= $_GET['sp_billno']; //orderid
        $strTotalFee		= $_GET['total_fee'];
        $strFeeType		    = $_GET['fee_type'];
        $strAttach		    = $_GET['attach'];
        $strMd5Sign		    = $_GET['sign'];

        /*sec*/
        $strResponseText  = "cmdno=" . $strCmdno . "&pay_result=" . $strPayResult . "&date=" . $strBillDate . "&transaction_id=" . $strTransactionId ."&sp_billno=" . $strSpBillno . "&total_fee=" . $strTotalFee ."&fee_type=" . $strFeeType . "&attach=" .
        $strAttach . "&key=" . $this->config['spkey'];
        $strLocalSign = strtoupper(md5($strResponseText));
        $s_price = is_numeric($strTotalFee) ? $strTotalFee : 0;

        if($s_price > 0) $s_price = $s_price / 100; //fen => yuan

        $retcode = 0;
        $retmsg = 'pay_succeed';

        if($this->config['spid'] != $strBargainorId){
            $retcode = 1;
            $retmsg = 'pay_tenpay_id_invalid';
        }
        if($strLocalSign != $strMd5Sign) {
            $retcode = 2;
            $retmsg = 'pay_tenpay_md5_invalid';
        }
        if($strPayResult != '0') {
            $retcode = 3;
            $retmsg = 'pay_tenpay_error';
        }

        if($retcode > 0) {
            //lost
            $this->_log_result ($strSpBillno . ':' . $retmsg . "\r\n" . $strResponseText);
            return FALSE;
        } else {
            //succeed
            return TRUE;
        }
    }

    function goto_url($url) {
        $strMsg = "<html><head><meta name=\"TENCENT_ONELINE_PAYMENT\" content=\"China TENCENT\"></head>\n";
        $strMsg .= "<body><script language=javascript>\n";
        $strMsg .= "window.location.href='" . $url . "';\n";
        $strMsg .= "</script></body></html>";
        exit($strMsg);
    }

}
?>