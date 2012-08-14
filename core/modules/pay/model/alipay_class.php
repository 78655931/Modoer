<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('pay:payment', FALSE);
class msm_pay_alipay extends msm_pay_payment {

    var $gateway = "https://www.alipay.com/cooperate/gateway.do?";
    var $security_code  = '';
    var $sign_type = 'MD5';
    var $mysgin = '';

    var $notify_return = TRUE;
    var $notify_end = FALSE;

    var $royalty = ''; //分润参数

    function __construct() {
        parent::__construct();
        $this->notify_url = _G('cfg','siteurl') . 'pay.php';//?act=notify&api=alipay
        $this->return_url = _G('cfg','siteurl') . 'pay.php?act=return&api=alipay';//
        if($this->charset == 'gb2312') $this->charset = 'GBK';
        $this->security_code = $this->config['alipay_key'];
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
        if($pay['royalty']) $this->royalty = $pay['royalty'];
        $content = $this->create_payurl($title, $price, $unid);
        if(!$content) redirect('pay_tenpay_url_empty');
        echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';
        echo '<html><head>';
        echo '<meta http-equiv="Content-Type" content="text/html; charset='.$this->charset.'">';
        echo '</head>';
        echo '<body onload="javascript:document.alipaysubmit.submit();">';
        echo '<h3>Wait for a moment please.</h3>';
        echo $content;
        echo '</body></html>';
        exit();
    }

    function check_payurl() {
        $retcode = 0;
        if($retcode < 0) redirect($retmsg);
        return $retcode;
    }

    function create_payurl($title, $price, $unid) {
        //构造要请求的参数数组
        $parameter = array(
            "service"         => "create_direct_pay_by_user",
            "payment_type"    => "1",
            //获取配置文件
            "partner"         => $this->config['alipay_partnerid'],
            "seller_email"    => $this->config['alipay_id'],
            "return_url"      => $this->return_url,
            "notify_url"      => $this->notify_url,
            "_input_charset"  => $this->charset,
            "show_url"        => _G('cfg','siteurl'),
            //从订单数据中动态获取到的必填参数
            "out_trade_no"    => $unid,
            "subject"         => $title,
            "body"            => $title,
            "total_fee"       => $price
        );

        //增加分润参数
        if($this->royalty) {
            $parameter["royalty_type"] = "10";
            $parameter["royalty_parameters"] = $this->royalty; //"111@126.com^0.01^分润备注一|222@126.com^0.01^分润备注二";
        }

        $parameter = $this->_para_filter($parameter);
        $this->mysgin = $this->_create_sgin($parameter, FALSE);

        /*
        $arg = $this->_create_linkstring($parameter);
        $url = $this->gateway . $arg . "&sign=" .$this->mysgin . "&sign_type=" . $this->sign_type;
        */
        //生成表单自动提交
        $content = "<form id='alipaysubmit' name='alipaysubmit' action='".$this->gateway."_input_charset=".$this->charset."' method='post'>\r\n";
        foreach($parameter as $key => $val) {
            $content .= "<input type='hidden' name='".$key."' value='".$val."'/>\r\n";
        }
        $content .= "<input type='hidden' name='sign' value='".$this->mysgin."'/>\r\n";
        $content .= "<input type='hidden' name='sign_type' value='".$this->sign_type."'/>\r\n";
        $content .= "</form>";
        return $content;
    }

    function notify_check() {
        $verify_result = $this->_notify_check();
        if($verify_result) {
            $port_orderid = _T($_POST['trade_no']);
            $unid = $_POST['out_trade_no'];  //获取支付宝传递过来的订单号
            $total = $_POST['total_fee'];   //获取支付宝传递过来的总价格
            $this->notify_msg = "success";
            if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
                //$this->log->update($unid, $total, $port_orderid);
                //echo "success";
                $this->_log_result ($unid . ':' . $port_orderid . ':success1');
            } else {
                //$this->log->update($unid, $total, $port_orderid);
                //echo "success"; //其他状态判断。普通即时到帐中，其他状态不用判断，直接打印success。
                $this->_log_result ($unid . ':' . $port_orderid . ':success2');
            }
            return true;
        } else {
            $this->notify_msg = "fail";
            $this->log_result ('fail');
            return false;
        }
    }

    function _create_sgin($params, $utf8urlencode = FALSE) {
        ksort($params);
        reset($params);
        if($this->charset == 'utf-8' && $utf8urlencode) {
            $fun = $this->_create_linkstring_urlencode($params);
        } else {
            $prestr = $this->_create_linkstring($params);
        }
        $mysgin = md5($prestr . $this->security_code);
        return $mysgin;
    }

    function _create_linkstring($array) {
        $arg = $split = "";
        foreach($array as $key=>$val) {
            $arg .= $split . $key . "=" . $val;
            $split = "&";
        }
        return $arg;
    }

    //utf-8专用
    function _create_linkstring_urlencode($array) {
        $arg = '';
        foreach($array as $key => $val) {
            if ($key == "subject" || $key == "body" || $key == "extra_common_param" || $key == "royalty_parameters") {
                $arg .= $key . "=" . urlencode($val) . "&";
            } else {
                $arg .= $key . "=" . $val . "&";
            }
        }
        $arg = substr($arg, 0, count($arg) - 2); //去掉最后一个&字符
        return $arg;
    }

    function _notify_check() {
        $veryfy_url = $this->gateway. "service=notify_verify" . "&partner=".$this->config['alipay_partnerid']."&notify_id=".$_POST["notify_id"];
        $veryfy_result = $this->_get_verify($veryfy_url);
		if(empty($_POST)) { //判断POST来的数组是否为空
			return FALSE;
		} else {
			$post = $this->_para_filter($_POST); //对所有POST返回的参数去空
			$this->mysign = $this->_create_sgin($post); //生成签名结果

            $this->_log_result($this->mysign);

			//写日志记录
			$this->_log_result("veryfy_result=".$veryfy_result."\r\n notify_url_log:sign=".$_POST["sign"]."&mysign=".$this->mysign.",".$this->_create_linkstring($post));

			// mysign与sign不等，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i", $veryfy_result) && $this->mysign == $_POST["sign"]) {
				return true;
			} else {
				return false;
			}
		}
    }

    /**获取远程服务器ATN结果
	 *$url 指定URL路径地址
	 *return 服务器ATN结果集
     */
    function _get_verify($url,$time_out = "60") {
        $urlarr     = parse_url($url);
        $errno      = "";
        $errstr     = "";
        $transports = "";
        if($urlarr["scheme"] == "https") {
            $transports = "ssl://";
            $urlarr["port"] = "443";
        } else {
            $transports = "tcp://";
            $urlarr["port"] = "80";
        }
        $newurl = $transports . $urlarr['host'];
        $this->_log_result($newurl);
        $fp = @fsockopen($newurl, $urlarr['port'], $errno, $errstr, $time_out);
        if(!$fp) {
            $this->_log_result("ERROR: $errno - $errstr");
            die("ERROR: $errno - $errstr<br />\n");
        } else {
            fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
            fputs($fp, "Host: ".$urlarr["host"]."\r\n");
            fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
            fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
            fputs($fp, "Connection: close\r\n\r\n");
            fputs($fp, $urlarr["query"] . "\r\n\r\n");
            while(!feof($fp)) {
                $info[] = @fgets($fp, 1024);
            }
            fclose($fp);
            $info = implode(",",$info);
            return $info;
        }
    }

    /**除去数组中的空值和签名参数
     *$parameter 加密参数组
     *return 去掉空值与签名参数后的新加密参数组
     */
    function _para_filter($parameter) {
        $para = array();
        foreach($parameter as $key => $val) {
            if($key == "sign" || $key == "sign_type" || $val == "") continue;
            else $para[$key] = $parameter[$key];
        }
        return $para;
    }
}
?>