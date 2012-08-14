<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op', null, MF_TEXT);
switch($op) {

    case 'save':
        $profile = _post('profile', null, MF_TEXT);
        $MP =& $_G['loader']->model('member:profile');
        $MP->set_uid($user->uid);
        foreach ($profile as $key => $value) {
            $MP->$key = $value;
        }
        $MP->save();
        redirect('global_op_succeed');
        break;

    case 'send_verify_mail':
        if($user->groupid=='4') {
            $result = $user->_send_verify_mail($user->uid);
            if(!$result) redirect('member_verify_send_mail_invalid');
            redirect(lang('member_verify_send_mail',$user->email),url('member/index'));
        } else {
            redirect('member_verify_groupid_invalid');
        }
        break;

    case 'setalipay':
            $token = $_G['loader']->model('member:passport')->get_token($user->uid, 'taobao');
            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
            $client = new TaobaoClient($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret'], 
                $token['access_token']);
            if($me = $client->user_get('user_id,nick,alipay_account')) {
                if($me['alipay_account'] && $user->alipay != $me['alipay_account']) {

                    //发送邮件提醒
                    send_update_alipay_mail($me['alipay_account']);
                    //发送手机短信息
                    send_update_alipay_mail_sms($me['alipay_account']);
                    //处理
                    $_G['loader']->model('member:profile')
                        ->set_uid($user->uid)
                        ->save_alipay($me['alipay_account']);
                }
                echo $me['alipay_account'];
            } else {
                echo 'EMPTY';
            }
            exit;
        break;

    case 'changemobile':
        $MV =& $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq);
        //$MV->delete_time_limit();
        switch(_input('do')) {
        case 'mobile':
            $_G['loader']->helper('validate');
            $mobile = _post('mobile', null, MF_TEXT);
            if(!$mobile) {
                echo lang('member_reg_ajax_mobile_empty'); exit;
            }
            if(!validate::is_mobile($mobile)) {
                echo lang('member_reg_ajax_mobile_invalid'); exit;
            }
            if($user->check_mobile_exists($mobile)) {
                echo lang('member_reg_ajax_mobile_exists'); exit;
            }
            echo 'OK';exit;
            break;

        case 'send':
            $mobile = _input('mobile',null,MF_TEXT);
            if($time = $MV->get_resend_time()) {
                echo $time; exit;
            }
            $succeed = $MV->set_mobile($mobile)->send();
            if($succeed) {
                echo 'OK';
            } else {
                echo 'ERROR';
            }
            exit;
            break;

        case 'check':
            $serial = _input('serial', null, MF_TEXT);
            $succeed = $MV->set_serial($serial)->checking();
            $verify = $MV->get_status();
            if($succeed) {
                $user->db->from($user->table)->where('uid',$user->uid)->set('mobile', $verify['mobile'])->update();
                $MV->delete();
                echo 'OK';
            } else {
                echo 'ERROR';
            }
            exit;
            break;

        default:
            require_once template('changemobile','member',MOD_FLAG);
        }
        output();
        break;

    case 'changepw':
        if($_POST['dosubmit']) {
            $user->change_password($_POST['old'], $_POST['new'], $_POST['new2']);
            redirect('global_op_succeed');
        } else {
            require_once template('changepw','member',MOD_FLAG);
            output();
        }
        break;

    case 'change_password':
        if($error = $user->change_password($_POST['old'], $_POST['new'], $_POST['new2'], 1)) {
            echo '<script type="text/javascript">alert("'.$error.'");</script>';
        } else {
            echo '<script type="text/javascript">alert("'.lang('global_op_succeed').'");window.parent.document.forms["changepasswordfrm"].hide.click();</script>';
        }
        output();
        break;

    default:

        $PT =& $_G['loader']->model('member:passport');
        $pstoken = $_G['loader']->model('member:passport')->get_token_status($user->uid);

        $smscfg = $modcfg = _G('loader')->variable('config','sms');
        $usdmobile = $smscfg['use_api'];
        
}

function send_update_alipay_mail($alipay) {
    global $user,$_G;

    $subject = _G('cfg','sitename') . ':您的支付宝帐号已更新!';
    $message = "您的帐号 {$user->username} 在 ".date('Y-m-d H:i:s', $_G['timestamp'])." 更新了支付宝帐号为 {$alipay}，如果您没有进行过操作，请及时登录网站联系管理员，以免产生损失。";
    $message = wordwrap($message, 75, "\r\n") . "\r\n";

    $cfg =& _G('cfg');
    if($cfg['mail_use_stmp']) {
        $cfg['mail_stmp_port'] = $cfg['mail_stmp_port'] > 0 ? $cfg['mail_stmp_port'] : 25;
        $auth = ($cfg['mail_stmp_username'] && $cfg['mail_stmp_password']) ? TRUE : FALSE;
        $_G['loader']->lib('mail',null,false);
        $MAIL = new ms_mail($cfg['mail_stmp'], $cfg['mail_stmp_port'], $auth, $cfg['mail_stmp_username'], $cfg['mail_stmp_password']);
        $MAIL->debug = $cfg['mail_debug'];
        $result = @$MAIL->sendmail($user->email, $cfg['mail_stmp_email'], $subject, $message, 'TXT');
        unset($MAIL);
    } else {
        $header = "Content-Type:text/plain; charset="._G('charset')."\r\n";
        $header .= "From: $from<".$from.">\r\n";
        $header .= "Subject: =?".strtoupper(_G('charset')).'?B?'.base64_encode($subject)."?=\r\n";
        $result = @mail($user->email, $subject, $message);
    }
}

function send_update_alipay_mail_sms($alipay) {
    global $user,$_G;

    if(!$user->mobile) return;

    $message = "您的帐号 {$user->username} 在 ".date('m-d H:i', $_G['timestamp'])." 更新了支付宝帐号 {$alipay} 【"._G('cfg','sitename')."】";

    $_G['loader']->model('sms:factory', null);
    $sms = msm_sms_factory::create();
    $sms->send($user->mobile, $message);
}
?>