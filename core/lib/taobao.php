<?php

class ms_taobao {

    public $url = 'http://gw.api.taobao.com/router/rest?';
    public $charset = '';
	public $appKey = '';
    public $appSecret = '';
	public $appSession = '';

    private $params = array();

    public function __construct() {
        global $_G;
        $this->charset = $_G['charset'];
    }

    public function set_appkey($appKey,$appSecret,$sessionkey='') {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
		$this->appSession = $sessionkey;
    }

    public function set_method($method) {
        return $this->set_param('method', $method);
    }

    public function set_param($key,$val) {
        if($key) $this->params[$key] = $val;
        return $this;
    }

    public function set_params($params) {
        if($params) foreach($params as $k=>$v) {
            $this->set_param($k,$v);
        }
        return $this;
    }

    public function get_data() {
        $this->init_params();
        $sign = $this->createSign($this->params);
        $strParam = $this->createStrParam($this->params);
        $strParam .= 'sign=' . $sign;
        $url = $this->url . $strParam;
        $cnt=0;	
        while($cnt < 3 && ($result = $this->vita_get_url_content($url)) === FALSE) $cnt++;
        if($result) {
            $result = $this->getXmlData($result);
            $this->check_error($result);
        }
        $this->params = array();
        return $result;
    }

    private function check_error(&$data) {
        if($data['code'] && $data['msg']) {
            $result = array('code'=>$data['code'],'msg'=>$data['msg']);
            $msg = $param = '';
            foreach($data as $k=>$v) {
                $msg .= $k.' : '.$v."\r\n";
            }
            foreach($this->params as $k=>$v) {
                $param .= $k.' : '.$v."\r\n";
            }
            $this->log_result($msg,$param);
            return $result;
        }
        return false;
    }

    private function init_params() {
        $this->params = $this->charset_param($this->params);
        $this->params['timestamp'] = date('Y-m-d H:i:s');
        $this->params['format'] = 'xml';
        $this->params['app_key'] = $this->appKey;
        $this->params['session'] = $this->appSession;
        $this->params['v'] = '2.0';
        $this->params['sign_method'] = 'md5';
    }

	private function vita_get_url_content($url) {
        if(function_exists('curl_exec')) {
            $ch = curl_init();
            $timeout = 5;
            curl_setopt ($ch, CURLOPT_URL, $url);
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $file_contents = curl_exec($ch);
            $curl_error = curl_error($ch);
            curl_close($ch);
            if(!$file_contents && $curl_error) {
                echo "<h3>$curl_error</h3>";
                echo "<h5>$url</h5>";
                exit();
            }
        } elseif(ini_get("allow_url_fopen") == "1") {
			$file_contents = file_get_contents($url);
		} else {
            echo '<h3>PHP function (curl_exec) does not exist.</h3>';
            exit();
        }
	    return $file_contents;
	}
	
	private function createSign($paramArr) { 
	    $sign = $this->appSecret; 
	    ksort($paramArr); 
	    foreach ($paramArr as $key => $val) { 
	       if ($key !='' && $val !='') { 
	           $sign .= $key.$val; 
	       }
	    }
	    $sign = strtoupper(md5($sign.$this->appSecret));
	    return $sign; 
	}
 
	private function createStrParam ($paramArr) { 
	    $strParam = ''; 
	    foreach ($paramArr as $key => $val) { 
	       if ($key != '' && $val !='') { 
	           $strParam .= $key.'='.urlencode($val).'&'; 
	       }
	    }
	    return $strParam; 
	}

	private function getXmlData($strXml) {
        global $_G;
		$pos = strpos($strXml, 'xml');
		if ($pos) {
			$xmlCode=simplexml_load_string($strXml,'SimpleXMLElement', LIBXML_NOCDATA);
			$arrayCode=$this->get_object_vars_final($xmlCode);
			if(strtoupper($_G['charset']) != 'UTF-8') {
				$arrayCode = $this->get_object_vars_final_coding($arrayCode);
			}
			return $arrayCode ;
		} else {
			return '';
		}
	}

	private function get_object_vars_final($obj) {
		if(is_object($obj)) {
			$obj = get_object_vars($obj);
		}
		if(is_array($obj)){
			foreach ($obj as $key=>$value) {
				$obj[$key] = $this->get_object_vars_final($value);
			}
		}
		return $obj;
	}

    private function get_object_vars_final_coding ($obj){
		foreach($obj as $key => $value){
			if(is_array($value)){
				$obj[$key] = $this->get_object_vars_final_coding($value);
			} else {
				$obj[$key] = $this->charset_data($value);
			}
		}
        return $obj;
    }

    private function log_result($params, $word='') {
        if($fp = @fopen(MUDDER_ROOT . 'data' . DS . 'logs' . DS . "taobaoapi_log.php", "a")) {
            flock($fp, LOCK_EX) ;
            fwrite($fp,"<?php exit();?>\r\ndate:".strftime("%Y-%m-%d %H:%M:%S", time())."\r\n".$word."\r\n".$params."\r\n");
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    private function charset_data($data) {
        global $_G;

        $_G['loader']->lib('chinese', NULL, FALSE);
        $CHS = new ms_chinese('utf-8', $_G['charset']);

		if(strtoupper($_G['charset']) != 'UTF-8') {
			if(function_exists('mb_convert_encoding')) {
				$data = str_replace('utf-8', $_G['charset'], $data);
				$data = @mb_convert_encoding($data, $_G['charset'], 'UTF-8');
			} elseif(function_exists('iconv')) {
				$data = str_replace('utf-8', $_G['charset'], $data);
				$data = @iconv('UTF-8', $_G['charset'], $data);
			}
		}

		return $data;
    }

	private function charset_param($param){
		if(strtoupper($this->charset) != 'UTF-8'){
			if(function_exists('mb_convert_encoding')){
			    if(is_array($param)){
			        foreach($param as $key => $value){
				        $param[$key] = @mb_convert_encoding($value,'UTF-8',$this->charset);
			        }
			    }else{
				    $param = @mb_convert_encoding($param,'UTF-8',$this->charset);
			    }
			}elseif(function_exists('iconv')){
			    if(is_array($param)){
			        foreach($param as $key => $value){
				        $param[$key] = @iconv($this->charset,'UTF-8',$value);
			        }
			    }else{
				    $param = @iconv($this->charset,'UTF-8',$param);
			    }
			}
		}
		return $param;
	}
}

?>