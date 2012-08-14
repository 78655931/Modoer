<?php
/**
* 支付模块入口，必须保留
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
if(!defined('MUDDER_ROOT')) {
    require dirname(__FILE__).'/core/init.php';
}

//支付模块接口的特殊处理
if($_GET['act'] != 'return' && !empty($_POST)) {
    /*
    $__input = "GET:\r\n";
    if($_GET) foreach($_GET as $k => $v) {
        $__input .= $k . ':' . $v . "\r\n";
    }
    $__input .= "POST:\r\n";
    foreach($_POST as $k => $v) {
        $__input .= $k . ':' . $v . "\r\n";
    }
    file_put_contents(MUDDER_ROOT . 'data' . DS . 'logs' . DS . 'pay_input_log.txt', $__input);
    unset($__input);
    */
    if(isset($_POST['trade_no']) && isset($_POST['trade_status']) && isset($_POST['total_fee'])) {
        $_GET['act'] = 'notify';
        $_GET['api'] = 'alipay';
    } elseif(isset($_POST['v_oid']) && isset($_POST['v_pstatus']) && isset($_POST['v_amount'])) {
        $_GET['act'] = 'notify';
        $_GET['api'] = 'chinabank';
    }
}

require MUDDER_MODULE . 'pay/common.php';
?>