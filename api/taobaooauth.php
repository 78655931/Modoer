<?php
class TaobaoOAuth {

	private $authorize_url = 'https://oauth.taobao.com/authorize';
	private $token_url = 'https://oauth.taobao.com/token';
	private $appkey = '';
	private $appsecret = '';
	
	function __construct($appkey, $appsecret) {
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
	}

	public function getAuthorizeURL($callbackurl,$state='') {
		$url = $this->authorize_url . '?response_type=code&client_id='.
			$this->appkey . '&redirect_uri=' . urlencode($callbackurl) . '&state=' . $state;
		return $url;
	}

	public function getAccessToken($code, $callbackurl) {
		$params = array();
		$params['grant_type'] = 'authorization_code';
		$params["code"] = $code;
		$params["client_id"] = $this->appkey;
		$params["client_secret"] = $this->appsecret;
		$params["redirect_uri"] = $callbackurl;
		$token = json_decode($this->do_post($this->token_url, $params));
		if(!$token->access_token) {
			echo '<h3>error:',$token->error,'</h3>';
			echo '<h3>descroption:',$token->error_description,'</h3>';
			exit;
		}
		return $token;
	}

	private function do_post($url,$params) {
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
}

class TaobaoClient {

	private $sdk = null;

	private $appkey = '';
	private $appsecret = '';
	private $token = '';
	
	function __construct($appkey, $appsecret, $token) {
		$this->appkey = $appkey;
		$this->appsecret = $appsecret;
		$this->token = $token;
		$this->sdk = _G('loader')->lib('taobao');
	}

	private function init_key() {
		$this->sdk->set_appkey($this->appkey , $this->appsecret , $this->token );
	}

	public function user_get($fields='user_id,nick,email') {
		$this->init_key();
        $Data = $this->sdk->set_method('taobao.user.get')
            ->set_param('fields', $fields)
            ->get_data();
        return $Data['user'];
	}
}