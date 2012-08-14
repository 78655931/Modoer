<?php
require dirname(dirname(dirname(__FILE__))).'/core/init.php';
$memberconfig = $_G['loader']->variable('config','member');

$callback = _G('cfg','siteurl').'api/taobao/callback.php';

$params = array();
if($_GET['code']) {
	$params['grant_type'] = 'authorization_code';
	$params["code"] = $_GET['code'];
} else {
	$params['grant_type'] = 'refresh_token';
	$params["refresh_token"] = $_GET['top_session'];
}
$params["client_id"] = $memberconfig['passport_taobao_appkey'];
$params["client_secret"] = $memberconfig['passport_taobao_appsecret'];
$params["redirect_uri"] = $callback;
$url = 'https://oauth.taobao.com/token';

$token = json_decode(do_post($url, $params));
if($token->access_token) {

	set_cookie('passport_taobao_token', $token->access_token, $token->expires_in);
	$passport_id = $token->taobao_user_id;

    $message = lang('member_passport_succeed');
    $PT =& $_G['loader']->model('member:passport');
    if(!$passport_id) {
        $message = lang('member_passport_test_access');
    } elseif($uid = $PT->get_uid('taobao', $passport_id)) {
        $url = url("member/index",'',1);
        $sync = $user->login_passport($uid);
    } else {
        $url = url("member/reg/passport/taobao",'',1);
    }
    redirect($message . $sync, $url);

} else {
	echo '<h3>error:',$token->error,'</h3>';
	echo '<h3>descroption:',$token->error_description,'</h3>';
	exit;
}

function do_post($url,$params) {
	if(!function_exists('curl_exec')) {
		echo '<h3>PHP function (curl_exec) does not exist.</h3>';
		exit;
	}
	$ch = curl_init ($url."?");
	curl_setopt ($ch, CURLOPT_HEADER, 0);
	curl_setopt ($ch, CURLOPT_POST, 1);
	foreach($params as $key=>$value){
		$fields_string .="{$key}={$value}&";
	}
	rtrim($fields_string,'&');
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $fields_string);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);     
	$result = curl_exec ($ch);
	curl_close ($ch);  
	return $result;
}
?>