<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!$MOD['uc_exange']||!defined('IN_UC')) {
    redirect('ucenter_exchange_disable');
}

$POINT = & $_G['loader']->model('member:point');

if(check_submit('dosubmit')) {

    $outextcredits = unserialize($MOD['outextcredits']);

    $tocredits = trim($_POST['tocredits']);
    if(!$outextcredits[$tocredits]['creditsrc']) {
        redirect('ucenter_exchange_intvalid');
    }

    $amount = intval($_POST['amount']);
	$out_point = _T($_POST['out_point']);
    $amount <= 0 && redirect('ucenter_exchange_coin_less0');
    !$tocredits && redirect('ucenter_exchange_dest_empty');

	$config = $_G['loader']->variable('config','member');
	$point_group = unserialize($config['point_group']);
	if(!isset($point_group[$out_point])) redirect('ucenter_exchange_point_type_invalid');
	$group = $point_group[$out_point];
	if(!$group['enabled']) redirect('ucenter_exchange_point_type_disabled');
	if(!$group['out']) redirect('ucenter_exchange_point_type_access');

    list($tmp_uid) = uc_user_login($user->username, $_POST['password_credits']);
    $tmp_uid <= 0 && redirect('ucenter_exchange_password_invalid');
    unset($_POST['password_credits']);

    $user->$out_point < $amount && redirect("ucenter_exchange_coin_not_enough");
    $netamount = floor($amount / $outextcredits[$tocredits]['ratio']);
    if($amount > 0 && $netamount == 0) redirect("ucenter_exchange_revenue_empty");
    list($toappid, $tocredits) = explode('|', $tocredits);

	$fromid = (int)substr($out_point,5);
    if(!$ucresult = uc_credit_exchange_request($user->uid, $fromid, $tocredits, $toappid, $netamount)) {
        redirect('ucenter_exchange_intvalid');
    }

    $pt =& $_G['loader']->model('member:point');
    $pt->update_point2($user->uid,$out_point, -$amount, 'ucenter_exchange_des');
    unset($pt, $amount, $tocredits);

    redirect('global_op_succeed', url('ucenter/member/ac/credits'));

} else {

    $extcredits_exchange = array();
    $outextcredits = unserialize($MOD['outextcredits']);

}
?>