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
            redirect('���ֶһ�ʧ�ܣ�����ϵ����Ա��');
        }

        $amount = intval($amount);
        !$tocredits && redirect('û���ṩ�һ�Ŀ�꣬�뷵��ѡ��');
        $amount <= 0 && redirect('֧����ұ������0���뷵����д��');
        list($tmp_uid) = uc_user_login($user->username, $password_credits);
        $tmp_uid <= 0 && redirect('��������뷵����д��');
        $password_credits = '';
        $user->coin < $amount && redirect("���ڵļҵ�û��ô�࣬�޷��һ����뷵����д��");
        $netamount = floor($amount / $outextcredits[$tocredits]['ratio']);
        if($amount > 0 && $netamount == 0) redirect("����Ϊ0���޷��һ����뷵����д��");
        list($toappid, $tocredits) = explode('|', $tocredits);
        $ucresult = uc_credit_exchange_request($user->uid, 0, $tocredits, $toappid, $netamount);
        !$ucresult && redirect('�һ�ʧ�ܣ��������Ա��ϵ��');

        $db->update("UPDATE {$dbpre}members SET coin=coin-$amount WHERE uid='{$user->uid}'");
        $amount = $tocredits = 0;

        redirect('���ֶһ��ɹ���', 'member.php?ac='.$ac);

    } else {

        redirect('���ֶһ������ѹرա�');

    }

}
?>