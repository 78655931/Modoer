<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$PT = $_G['loader']->model('member:passport');
$callbackurl = str_replace('&amp;','&',url("member/index/ac/passport/op/__OP__/psname/__PSNAME__",'',1,1));
$passportlist = $MOD['passport_list'] ? explode(',', $MOD['passport_list']) : '';

$op = _input('op', null, MF_TEXT);
switch ($op) {
	case 'get_token':
		$psname = _get('psname', null, MF_TEXT);
		$nop = _get('nop', 'bind', MF_TEXT);
		$callbackurl = str_replace(array('__OP__','__PSNAME__'), array($nop,$psname), $callbackurl);
		switch ($psname) {
	        case 'weibo':
	            if(!$MOD['passport_login'] || $MOD['passport_weibo']) redirect('member_passport_enable');
	            include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
	            $o = new SaeTOAuthV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret']);
	            $aurl = $o->getAuthorizeURL( $callbackurl );
	            break;
	        case 'qq':
	            if(!$MOD['passport_login'] || $MOD['passport_qq']) redirect('member_passport_enable');
	            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
	            $o = new QQOAuthV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey']);
	            $aurl = $o->getAuthorizeURL( $callbackurl );
	            break;
	        case 'taobao':
	            if(!$MOD['passport_login'] || $MOD['passport_taobao']) redirect('member_passport_enable');
	            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
	            $o = new TaobaoOAuth($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret']);
	            $aurl = $o->getAuthorizeURL( $callbackurl );
	            break;
	        default:
	            redirect('global_op_unkown');
		}
		location($aurl);
		break;
	case 'bind':
	case 'token':
		$psname = _input('psname', null, MF_TEXT);
		$callbackurl = str_replace('__PSNAME__', $psname, $callbackurl);
		switch ($psname) {
			case 'weibo':
				include_once MUDDER_ROOT . 'api' . DS . 'saetv2.ex.class.php';
	            $o = new SaeTOAuthV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret']);
	            $keys = array();
	            $keys['code'] = $_REQUEST['code'];
	            $keys['redirect_uri'] = $callbackurl;
	            try {
	                $token = $o->getAccessToken( 'code', $keys );
	            } catch (OAuthException $e) {}
	            if($token) {
	                $access_token = $token['access_token'];
	                $expires_in = $_G['timestamp'] + $token['remind_in']; //lifetime
	                $c = new SaeTClientV2($MOD['passport_weibo_appkey'], $MOD['passport_weibo_appsecret'], $access_token);
	                $uid_get = $c->get_uid();
	                $me = $c->show_user_by_id($uid_get['uid']); //userinfo
	                $psuid = $me['id'];
	            } else {
	                redirect('member_passport_lost');
	            }
				break;
			case 'qq':
	            include_once MUDDER_ROOT . 'api' . DS . 'qqoauth2.php';
	            $o = new QQOAuthV2($MOD['passport_qq_appid'], $MOD['passport_qq_appkey']);
	            $token = $o->getAccessToken( $_REQUEST['code'] , $callbackurl);
	            $access_token = $token['access_token'];
	            $expires_in = $_G['timestamp'] + $token['expires_in'];
	            $psuid = $o->getOpenid($access_token);
				break;
			case 'taobao':
	            include_once MUDDER_ROOT . 'api' . DS . 'taobaooauth.php';
	            $o = new TaobaoOAuth($MOD['passport_taobao_appkey'], $MOD['passport_taobao_appsecret']);
	            $token = $o->getAccessToken( $_REQUEST['code'] , $callbackurl);
	            $access_token = $token->access_token;
	            $expires_in = $_G['timestamp'] + $token->expires_in;
	            $psuid = $token->taobao_user_id;
				break;
			default:
				redirect('global_op_unkown');
				break;
		}
		if(!$psuid) redirect('member_passport_lost');
		if($op == 'bind') {
			if($PT->bind_exists($psname, $psuid)) redirect('member_passport_bind_exists');
		}
		$PT->bind($user->uid, $psname, $psuid, $access_token, $expires_in);
		redirect('global_op_succeed', url('member/index/ac/passport'));
		break;
	case 'unbind':
		$psname = _input('psname', null, MF_TEXT);
		$PT->unbind($user->uid, $psname);
		redirect('global_op_succeed', url('member/index/ac/passport'));
		break;
	default:
		$passport = $PT->get_list($user->uid);
		$tplname = 'passport';
		break;
}
?>