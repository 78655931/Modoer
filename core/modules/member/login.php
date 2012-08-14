<?php
/**
* user login
* @author moufer<moufer@163.com>
* @package modoer
* @copyright Moufer Studio(www.modoer.com)
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'login');

$op = _get('op');
$forward = $_GET['forward'] ? _T(base64_decode(rawurldecode($_GET['forward']))) : get_forward(url('member/index'));
if(strposex($forward,'logout') || strposex($forward,'login') || !strposex($forward, $_G['web']['domain'])) {
    $forward = $_G['cfg']['siteurl'];
}

switch($op) {
case 'forget':
    if($user->isLogin) redirect('member_login_logined');
    if(check_submit('dosubmit')) {
        $user->forget($_POST['username'], $_POST['email']);
        redirect('member_forget_mail_succeed', url('member/login'));
    } else {
        require_once template('member_forget');
    }
    break;
case 'verify':
    $getpwid = _get('id', null, MF_INT_KEY);
    $secode = _get('sec', null, MF_TEXT);
    if($user->verify($getpwid, $secode)) redirect('member_verify_succeed', url('member/index'));
    break;
case 'updatepw':
    if($user->isLogin) redirect('member_login_logined');
    if(check_submit('dosubmit')) {
        $user->update_password((int)$_POST['getpwid'], _T($_POST['secode']), trim($_POST['newpassword']), trim($_POST['newpassword2']));
        redirect('member_getpassword_succeed', url('member/login'));
    } else {
        $getpwid = _get('id', null, MF_INT_KEY);
        $secode = _get('sec', null, MF_TEXT);
        $uid = $user->check_getpassword($getpwid, $secode);
        if(!$member = $user->read($uid, false, 'uid,username,groupid')) redirect('member_getpassword_username_empty');
        require_once template('member_forget');
    }
    break;
case 'logout':
    $sync = $user->logout();
    redirect(lang('global_op_succeed') . $sync, $forward);
    break;
case 'check':
    if(defined('IN_AJAX')) {
        $search = array('"',"\r\n","\r","\n","\n\r");
        $replace = array('\\"',"{LF}","{LF}","{LF}","{LF}");
        if($user->isLogin) {
            //task
            $Tsk =& $_G['loader']->model('member:task');
            $Tsk->mytask(0);
            echo '{ type:"login",username:' . '"' . str_replace($search, $replace, $user->username) . '",
            notice:"' . $_G['loader']->model('member:notice')->get_count().'",
            task:"' . $Tsk->task_done_count().'",
            newmsg:"' . $user->newmsg.'",
            point:"' . $user->point.'",
            group:"' . display('member:group',"groupid/$user->groupid").'" }';
        } elseif($_G['cookie']['activationauth'] && $_G['cookie']['username']) {
            echo '{ type:"activationauth",username:' . '"' . str_replace($search, $replace, $_G['cookie']['username']) . '" }';
        } else {
            echo '';
        }
        output();
    }
    break;
case 'ajax_login':
    if($user->isLogin) redirect('member_login_logined');
    if($user->passport['enable']) location($user->passport['login_url']);
    if($_POST['dosubmit']) {
        if($MOD['seccode_login']) check_seccode($_POST['seccode']);
        if(!$sync = $user->login($_POST['username'], $_POST['password'], $_POST['life'])) {
            redirect('member_login_lost');
        }
        $url = get_forward(url('member/index'),1);
        if(strpos($url,'reg')||strpos($url,'login')) $url = url('member/index');
        $sync .= "<script type=\"text/javascript\">alert('".lang('member_login_succeed')."');</script>";
        echo fetch_iframe($sync);
        output();
        //redirect(lang('member_login_succeed') . $sync, $url);
    } else {
        require_once template('member_login_ajax');
    }
    break;
case 'login':
    if($user->isLogin) location(url('member/index'));//redirect('member_login_logined');
    if($user->passport['enable']) location($user->passport['login_url']);
    if(!$_POST['onsubmit']) location(url('member/login'));
    if($MOD['seccode_login']) check_seccode($_POST['seccode']);
    if(!$sync = $user->login($_POST['username'], $_POST['password'], $_POST['life'])) {
        redirect('member_login_lost');
    }
    //$url = get_forward(url('member/index'),1);
    //if(strpos($url,'reg')||strpos($url,'login')) $url = url('member/index');
    redirect(lang('member_login_succeed') . $sync, $forward);
    break;
case 'passport_login':
    switch(_get('type',null,MF_TEXT)) {
        case 'weibo':
            if(!$MOD['passport_login'] || $MOD['passport_weibo']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
            $o = new SaeTOAuthV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret']);
            $callbackurl = str_replace('&amp;','&',url("member/login/op/passport_callback/type/weibo",'',1,1));
            $aurl = $o->getAuthorizeURL( $callbackurl );
            location( $aurl );
            break;
        case 'qq':
            if(!$MOD['passport_login'] || $MOD['passport_qq']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
            $o = new QQOAuthV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey']);
            $callbackurl = str_replace('&amp;','&',url("member/login/op/passport_callback/type/qq",'',1,1));
            $aurl = $o->getAuthorizeURL( $callbackurl );
            location( $aurl );
            break;
        case 'taobao':
            if(!$MOD['passport_login'] || $MOD['passport_taobao']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
            $o = new TaobaoOAuth($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret']);
            $callbackurl = str_replace('&amp;','&',url("member/login/op/passport_callback/type/taobao",'',1,1));
            $aurl = $o->getAuthorizeURL( $callbackurl );
            location($aurl);
            break;
        default:
            redirect('global_op_unkown');
    }
case 'passport_callback':
    $access_token = '';
    $expires_in = 0;
    $psname = _get('type');
    switch($psname) {
        case 'weibo':
            if(!$MOD['passport_login'] || $MOD['passport_weibo']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
            $o = new SaeTOAuthV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret']);
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = str_replace('&amp;','&',url("member/login/op/passport_callback/type/weibo",'',1,1));
            try {
                $token = $o->getAccessToken( 'code', $keys );
            } catch (OAuthException $e) {}
            if($token) {
                $access_token = $token['access_token'];
                $expires_in = $_G['timestamp'] + $token['remind_in']; //ÊÚÈ¨Ê£ÓàÊ±¼ä
                set_cookie('passport_weibo_token', $access_token, 3600*24);
                set_cookie('passport_weibo_expires_in', $expires_in, 3600*24);
                $c = new SaeTClientV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret'], $access_token);
                //$ms = $c->home_timeline(); // done
                $uid_get = $c->get_uid();
                $uid = $uid_get['uid'];
                $me = $c->show_user_by_id($uid); // userinfo
                $passport_id = $me['id'];
            } else {
                redirect('member_passport_lost');
            }
            break;
        case 'qq':
            if(!$MOD['passport_login'] || $MOD['passport_qq']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
            $o = new QQOAuthV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey']);
            $callbackurl = str_replace('&amp;','&',url("member/login/op/passport_callback/type/qq",'',1,1));
            $token = $o->getAccessToken( $_REQUEST['code'] , $callbackurl);
            $access_token = $token['access_token'];
            $expires_in = $_G['timestamp'] + $token['expires_in'];
            $passport_id = $o->getOpenid($access_token);
            set_cookie('passport_qq_token', $access_token, 3600*24);
            set_cookie('passport_qq_expires_in', $expires_in, 3600*24);
            set_cookie('passport_qq_openid', $passport_id, 3600*24);
            break;
        case 'taobao':
            if(!$MOD['passport_login'] || $MOD['passport_taobao']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
            $o = new TaobaoOAuth($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret']);
            $callbackurl = str_replace('&amp;','&',url("member/login/op/passport_callback/type/taobao",'',1,1));
            $token = $o->getAccessToken( $_REQUEST['code'] , $callbackurl);
            $access_token = $token->access_token;
            $expires_in = $_G['timestamp'] + $token->expires_in;
            set_cookie('passport_taobao_token', $access_token, 3600*24);
            set_cookie('passport_taobao_expires_in', $expires_in, 3600*24);
            $passport_id = $token->taobao_user_id;
            break;
        default:
            redirect('global_op_unkown');
    }
    $message = lang('member_passport_succeed');
    $PT =& $_G['loader']->model('member:passport');
    if(!$passport_id) {
        $message = lang('member_passport_test_access');
    } elseif($uid = $PT->get_uid($psname, $passport_id)) {
        $url = url("member/index");
        $sync = $user->login_passport($uid, 3600*24);
        //update
        $PT->update_access_token($uid, $psname, $access_token, $expires_in);
    } else {
        $url = url("member/reg/passport/$psname", '', 1);
    }
    redirect($message . $sync, $url);
    break;
case 'passport_bind':
    $passport_type = _get('passport',null,MF_TEXT);
    $MP =& $_G['loader']->model('member:profile');
    switch($passport_type) {
        case 'weibo':
            if(!$MOD['passport_login'] || $MOD['passport_weibo']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
            $c = new SaeTClientV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret'],
                $_G['cookie']['passport_weibo_token']);
            //$ms  = $c->home_timeline(); // done
            $uid_get = $c->get_uid();
            $me = $c->show_user_by_id($uid_get['uid']); // userinfo
            $username = $me['screen_name'];
            $passport_id = $me['id'];
            break;
        case 'qq':
            if(!$MOD['passport_login'] || $MOD['passport_qq']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
            $c = new QQClientV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey'], 
                $_G['cookie']['passport_qq_token'], $_G['cookie']['passport_qq_openid']);
            $me = $c->get_user_info();
            $username = $me['nickname'];
            $passport_id = $_G['cookie']['passport_qq_openid'];
            break;
        case 'taobao':
            if(!$MOD['passport_login'] || $MOD['passport_taobao']) redirect('member_passport_enable');
            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
            $client = new TaobaoClient($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret'], 
                $_G['cookie']['passport_taobao_token']);
            if($me = $client->user_get()) {
                $passport_id = $me['user_id'];
                $username = $me['nick'];
            }
            break;
        default:
            redirect('global_op_unkown');
    }
    if(!$username||!$passport_id) redirect('member_passport_bind_invalid');

    if(strtoupper($_G['charset']) != 'UTF-8') {
        $username = charset_convert($username, 'utf-8', $_G['charset']);
    }

    $PT =& $_G['loader']->model('member:passport');
    if($uid = $PT->get_uid($passport_type, $passport_id)) redirect('member_passport_bind');

    $typename = lang('member_passport_type_'. $passport_type);
    $title = lang('member_passport_login', array($typename, $username, $_G['sitename']));

    $passport = true;
    $subtitle = lang('member_login_title');
    require_once template('member_login');
    break;
default:
    if($user->isLogin) redirect('member_login_logined');
    if($user->passport['enable']) location($user->passport['login_url']);
    $subtitle = lang('member_login_title');
    require_once template('member_login');
}
?>