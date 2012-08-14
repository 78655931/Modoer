<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
* @version $Id 3 2008-08-25 18:35 $
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!check_submit('mysubmit')) {

    $extcredits_exchange = array();
    $outextcredits = $_config['outextcredits'];

} else {

    if($ucenter_enable && $MOD['uc_exange']) {

        $outextcredits = $_config['outextcredits'];
        if($outextcredits[$tocredits]['creditsrc'] != 0) {
            redirect('积分兑换失败！请联系管理员。');
        }

        $amount = intval($amount);
        !$tocredits && redirect('没有提供兑换目标，请返回选择。');
        $amount <= 0 && redirect('支出金币必须大于0，请返回填写。');
        list($tmp_uid) = uc_user_login($user->username, $password_credits);
        $tmp_uid <= 0 && redirect('密码错误，请返回填写。');
        $password_credits = '';
        $user->coin < $amount && redirect("现在的家当没那么多，无法兑换，请返回填写。");
        $netamount = floor($amount / $outextcredits[$tocredits]['ratio']);
        if($amount > 0 && $netamount == 0) redirect("收入为0，无法兑换，请返回填写。");
        list($toappid, $tocredits) = explode('|', $tocredits);
        $ucresult = uc_credit_exchange_request($user->uid, 0, $tocredits, $toappid, $netamount);
        !$ucresult && redirect('兑换失败，请与管理员联系。');

        $db->update("UPDATE {$dbpre}members SET coin=coin-$amount WHERE uid='{$user->uid}'");
        $amount = $tocredits = 0;

        redirect('积分兑换成功。', 'member.php?ac='.$ac);

    } else {

        redirect('积分兑换功能已关闭。');

    }

}
?>