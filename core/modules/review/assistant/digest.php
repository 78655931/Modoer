<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
$op = _input('op');
$DT =& $_G['loader']->model('review:digestpay');

switch($op) {
    case 'view':
        $rid = _post('rid',null,MF_INT_KEY);
        if(!$DT->is_enabled() || $DT->exists($rid)) {
            echo 'OK';exit;
        }
        $review = $DT->buycheck($rid);
        $tplname = 'digest_pay';
        break;
    case 'pay':
        $rid = _post('rid',null,MF_INT_KEY);
        $DT->pay($rid);
        echo fetch_iframe('OK');exit;
        break;
	default:

}
?>