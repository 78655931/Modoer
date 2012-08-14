<?php
/**
* user register
* @author moufer<moufer@163.com>
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'reg');

if($user->isLogin) redirect('member_reg_logined');

$forward = $_GET['forward'] ? $_GET['forward'] : url('member/index','',true);
if(strposex($forward,'op=logout') || !strposex($forward, $_G['web']['domain'])) {
    $forward = $_G['cfg']['siteurl'];
}
$forward = _T(rawurldecode(rawurldecode($forward)));

$_G['loader']->helper('validate');
switch($_GET['op']) {
    case 'check_username':
        if(!$username = trim($_POST['username'])) {
            echo lang('member_reg_ajax_name_empty'); exit;
        }
        if($_G['charset'] != 'utf-8') {
            $_G['loader']->lib('chinese', NULL, FALSE);
            $CHS = new ms_chinese('utf-8', $_G['charset']);
            $username = _T($CHS->Convert($username));
        }
        $user->check_username($username, true);
        if($user->check_username_exists($username)) {
            echo lang('member_reg_ajax_name_exists');
            exit;
        }
        echo lang('member_reg_ajax_name_normal'); exit;
        break;
    case 'check_email':
        if(!$email = trim($_POST['email'])) {
            echo lang('member_reg_ajax_email_empty'); exit;
        }
        if(!validate::is_email($email)) {
            echo lang('member_reg_ajax_email_invalid'); exit;
        }
        if(!$MOD['existsemailreg'] && $user->check_email_exists($email)) {
            echo lang('member_reg_ajax_email_exists');exit;
        }
        echo lang('member_reg_ajax_email_normal'); exit;
        break;
    case 'check_mobile':
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
        if($verify = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->get_status()) {
            if($verify['status'] && $verify['mobile']==$mobile) {
                echo 'SUCCEED';exit;
            }
        }
        echo 'OK';exit;
    case 'send_verify':
        $mobile = _input('mobile', null, MF_TEXT);
        $MV = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq);
        if($time = $MV->get_resend_time()) {
            echo $time; exit;
        }
        $succeed = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->set_mobile($mobile)->send();
        if($succeed) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;
    case 'check_mobile_verify':
        $serial = _input('serial', null, MF_TEXT);
        $succeed = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->set_serial($serial)->checking();
        if($succeed) {
            echo 'OK';
        } else {
            echo 'ERROR';
        }
        exit;
}

if($user->passport['enable']) {
    location($user->passport['reg_url']);
}

if($MOD['closereg']) {
    redirect('member_reg_closed');
}

if($_POST['dosubmit']) {

    if($MOD['seccode_reg']) check_seccode($_POST['seccode']);
    if($MOD['mobile_verify']) {
        $verify = $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->get_status();
        if(!$verify['status'] || $_POST['mobile'] != $verify['mobile']) redirect('member_reg_mobile_verify_invalid');
    }
    $sync = $user->register($user->get_post($_POST));
    $msg = $_G['email_verify'] ? lang('member_reg_succeed_verify',$_POST['email']) : lang('member_reg_succeed');
    if($user->uid > 0 && $MOD['mobile_verify']) {
        $_G['loader']->model('member:mobile_verify')->set_uniq($user->uniq)->delete();
    }
    //task apply
    $_G['loader']->model('member:task')->automatic_apply();
    redirect($msg . $sync, $forward);

} else {

    $passport_type = _get('passport', '', MF_TEXT);
    $access_token = '';
    $expires_in = 0;
    switch ($passport_type) {
        case 'weibo':
            if(!$MOD['passport_login'] || $MOD['passport_weibo']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
            $access_token = _T($_G['cookie']['passport_weibo_token']);
            $expires_in = (int) $_G['cookie']['passport_weibo_expires_in'];
            $c = new SaeTClientV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret'],
                $_G['cookie']['passport_weibo_token']);
            //$ms  = $c->home_timeline(); // done
            $uid_get = $c->get_uid();
            $uid = $uid_get['uid'];
            $me = $c->show_user_by_id($uid);//userinfo
            if(strtoupper($_G['charset']) != 'UTF-8') {
                $_G['loader']->lib('chinese', NULL, FALSE);
                $CHS = new ms_chinese('utf-8', $_G['charset']);
                foreach($me as $k => $v) {
                    if(is_string($v)) $me[$k] = $CHS->Convert($v);
                }
            }
            $username = $me['screen_name'];
            $passport_id = $me['id'];
            $passport = true;
            break;
        case 'qq':
            if(!$MOD['passport_login'] || $MOD['passport_qq']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
            $access_token = _T($_G['cookie']['passport_qq_token']);
            $expires_in = (int) $_G['cookie']['passport_qq_expires_in'];
            $c = new QQClientV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey'], 
                $_G['cookie']['passport_qq_token'], $_G['cookie']['passport_qq_openid']);
            $me = $c->get_user_info();
            if(strtoupper($_G['charset']) != 'UTF-8') {
                $_G['loader']->lib('chinese', NULL, FALSE);
                $CHS = new ms_chinese('utf-8', $_G['charset']);
                foreach($me as $k => $v) {
                    if(is_string($v)) $me[$k] = $CHS->Convert($v);
                }
            }
            $username = $me['nickname'];
            $passport_id = $_G['cookie']['passport_qq_openid'];
            $passport = true;
            break;
        case 'taobao':
            if(!$MOD['passport_login'] || $MOD['passport_taobao']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
            $access_token = _T($_G['cookie']['passport_weibo_token']);
            $expires_in = (int) $_G['cookie']['passport_weibo_expires_in'];
            $client = new TaobaoClient($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret'], 
                $_G['cookie']['passport_taobao_token']);
            if($me = $client->user_get()) {
                $passport_id = $me['user_id'];
                $username = $me['nick'];
                $email = $me['email'];
                $passport = true;
            } else {
                redirect('member_passport_getinfo_error',url("member/login"));
            }
            break;
    }

    if($passport) {
        $PT =& $_G['loader']->model('member:passport');
        if($uid = $PT->get_uid($passport_type, $passport_id)) {
            $sync = $user->login_passport($uid, 3600*24);
            //update
            $PT->update_access_token($uid, $psname, $access_token, $expires_in);
            redirect(lang('member_login_succeed') . $sync, url("member/index"));
        }
        $typename = lang('member_passport_type_'. $passport_type);
        $title = lang('member_passport_reg', array($typename, $username, $typename));
    }

    $subtitle = lang('member_reg_title');
    require_once template('member_reg');
        
}
?>