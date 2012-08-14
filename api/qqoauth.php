<?php 

class QQOAuth {

    public $appid = '';
    public $appkey = '';

    public $_token = '';
    public $_token_secret = '';

    function __construct($appid, $appkey, $oauth_token=null, $oauth_token_secret=null) {
        $this->appid = $appid;
        $this->appkey = $appkey;
        if (!empty($oauth_token) && !empty($oauth_token_secret)) { 
            $this->_token = $oauth_token;
            $this->_token_secret = $oauth_token_secret;
        }
    }

    function getRequestToken() {
        global $_G;
        //������ʱtoken�Ľӿڵ�ַ
        $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token?";
        //����oauth_signatureǩ��ֵ
        $sigstr = "GET"."&".QQConnect_urlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token")."&";
        //��Ҫ����
        $params = array();
        $params["oauth_version"]          = "1.0";
        $params["oauth_signature_method"] = "HMAC-SHA1";
        $params["oauth_timestamp"]        = $_G['timestamp'];
        $params["oauth_nonce"]            = mt_rand();
        $params["oauth_consumer_key"]     = $this->appid;
        //�Բ���������ĸ���������л�
        $normalized_str = get_normalized_string($params);
        $sigstr        .= QQConnect_urlencode($normalized_str);
        //��2��������Կ
        $key = $this->appkey."&";
        //��3������oauth_signatureǩ��ֵ��������Ҫȷ��PHP�汾֧��hash_hmac����
        $signature = get_signature($sigstr, $key);
        //��������url
        $url      .= $normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);
        $request_token = file_get_contents($url);
        //��ȡtoken��
        if (strpos($request_token, "error_code") !== false) {
            echo '<html lang="zh-cn">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '</head>';
            echo '<body>';
            echo "<h3>url:</h3>$url</br>";
            echo "<h3>return:</h3>$request_token</br>";
            echo "<h3>time:</h3>".date('Y-m-d H:i:s',$_G['timestamp'])."</br>";
            echo '</body>';
            echo '</html>';
            exit;
        }
        //��������ֵ
        $result = array();
        parse_str($request_token, $result);
        return $result;
        /*
        $_SESSION["token"]        = $result["oauth_token"];
        $_SESSION["secret"]       = $result["oauth_token_secret"];
        */
    }

    function getAuthorizeURL($oauth_token, $callback) {
        //��ת��QQ��¼ҳ�Ľӿڵ�ַ, ��Ҫ����!!
        $redirect = "http://openapi.qzone.qq.com/oauth/qzoneoauth_authorize?oauth_consumer_key=".$this->appid."&";
        //��������URL
        $redirect .= "oauth_token=".$oauth_token."&oauth_callback=".QQConnect_urlencode($callback);
        return $redirect;
    }

    function getAccessToken($vericode) {
        global $_G, $QQsig;
        //�������Qzone����Ȩ�޵�access_token�Ľӿڵ�ַ, ��Ҫ����!!
        $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token?";
       
        //����oauth_signatureǩ��ֵ��ǩ��ֵ���ɷ��������http://wiki.opensns.qq.com/wiki/��QQ��¼��ǩ������oauth_signature��˵����
        //��1�� ��������ǩ��ֵ��Դ����HTTP����ʽ & urlencode(uri) & urlencode(a=x&b=y&...)��
        $sigstr = "GET"."&".QQConnect_urlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token")."&";

        //��Ҫ��������Ҫ������!!
        $params = array();
        $params["oauth_version"]          = "1.0";
        $params["oauth_signature_method"] = "HMAC-SHA1";
        $params["oauth_timestamp"]        = $_G['timestamp'];
        $params["oauth_nonce"]            = mt_rand();
        $params["oauth_consumer_key"]     = $this->appid;
        $params["oauth_token"]            = $this->_token;
        $params["oauth_vericode"]         = $vericode;

        //�Բ���������ĸ���������л�
        $normalized_str = get_normalized_string($params);
        $sigstr        .= QQConnect_urlencode($normalized_str);
        //echo "sigstr = $sigstr";
        //��2��������Կ
        $key = $this->appkey."&".$this->_token_secret;
        //��3������oauth_signatureǩ��ֵ��������Ҫȷ��PHP�汾֧��hash_hmac����
        $signature = get_signature($sigstr, $key);
        //��������url
        $url      .= $normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);
        //��ȡ
        $access_str = file_get_contents($url);
        //������
        if (strpos($access_str, "error_code") !== false) {
            echo '<html lang="zh-cn">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '</head>';
            echo '<body>';
            echo "<h3>signature url:</h3>$url</br>";
            echo "<h3>return:</h3>$access_str";
            echo "<h3>time:</h3>".date('Y-m-d H:i:s',$_G['timestamp'])."</br>";
            echo '</body>';
            echo '</html>';
            exit;
        }
        //�������ز���
        $result = array();
        parse_str($access_str, $result);
        //��access token��openid��������������
        /*
        $_SESSION["token"]   = $result["oauth_token"];
        $_SESSION["secret"]  = $result["oauth_token_secret"]; 
        $_SESSION["openid"]  = $result["openid"];
        
        if (!is_valid_openid($this->appid, $result["openid"], $_REQUEST["timestamp"], $_REQUEST["oauth_signature"])) {
            echo '<html lang="zh-cn">';
            echo '<head>';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
            echo '</head>';
            echo '<body>';
            echo "<h3>invalid openid</h3>";
            print_r($_REQUEST);
            echo "<h3>error signature:</h3>current:".$_REQUEST["oauth_signature"].'<br />right:'.$QQsig;
            echo "<h3>time:</h3>".date('Y-m-d H:i:s',$_G['timestamp'])."</br>";
            echo '</body>';
            echo '</html>';
            exit;
        }
        */
        return $result;
    }

}

class QQClient {

    public $appid = '';
    public $appkey = '';

    public $_token = '';
    public $_token_secret = '';
    public $_openid = '';

    function __construct($appid, $appkey, $oauth_token, $oauth_token_secret, $openid) {
        $this->appid = $appid;
        $this->appkey = $appkey;
        $this->token = $oauth_token;
        $this->token_secret = $oauth_token_secret;
        $this->openid = $openid;
    }

     /*
     * @brief ��ȡ�û���Ϣ.�����辭��URL���룬����ʱ����ѭ RFC 1738
     * 
     */
    function verify_credentials()
    {
        //��ȡ�û���Ϣ�Ľӿڵ�ַ, ��Ҫ����!!
        $url    = "http://openapi.qzone.qq.com/user/get_user_info";
        $info   = do_get($url, $this->appid, $this->appkey, $this->token, $this->token_secret, $this->openid);
        $arr = array();
        $arr = json_decode($info, true);
        return $arr;
    }

}

/**
 * @brief QQ��¼�ж�url��������ͳһ����
 * ����RFC 1738 ��URL���б���
 * ����-_.~֮������з���ĸ�����ַ��������滻�ɰٷֺ�(%)�����λʮ��������
 */
$QQhexchars = "0123456789ABCDEF";
$QQsig = '';
function QQConnect_urlencode($str)
{
    global $QQhexchars;
    $urlencode = "";
    $len = strlen($str);

    for($x = 0 ; $len--; $x++)
    {
        if (($str[$x] < '0' && $str[$x] != '-' && $str[$x] != '.') ||
            ($str[$x] < 'A' && $str[$x] > '9') ||
            ($str[$x] > 'Z' && $str[$x] < 'a' && $str[$x] != '_') ||
            ($str[$x] > 'z' && $str[$x] != '~')) 
        {
            $urlencode .= '%';
            $urlencode .= $QQhexchars[(ord($str[$x]) >> 4)];
            $urlencode .= $QQhexchars[(ord($str[$x]) & 15)];
        }
        else
        {
            $urlencode .= $str[$x];
        }
    }

    return $urlencode;
}

function QQConnect_urldecode($str)
{
    global $QQhexchars;
    $urldecode = "";
    $len = strlen($str);

    for ($x = 0; $x < $len; $x++)
    {
        if ($str[$x] == '%' && ($len - $x) > 2
            && (strpos($QQhexchars, $str[$x+1]) !== false) && (strpos($QQhexchars, $str[$x+2]) !== false))
        {
            $tmp = $str[$x+1].$str[$x+2];
            $urldecode .= chr(hexdec($tmp));
            $x += 2;
        }
        else
        {
            $urldecode .= $str[$x];
        } 
    }

    return $urldecode;
}

/**
 * @brief �Բ��������ֵ���������
 *
 * @param $params �����б�
 *
 * @return �������&���ӵ�key-value�ԣ�key1=value1&key2=value2...)
 */
function get_normalized_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
        $normalized[] = $key."=".$val;
    }

    return implode("&", $normalized);
}

/**
 * @brief ʹ��HMAC-SHA1�㷨����oauth_signatureǩ��ֵ 
 *
 * @param $key  ��Կ
 * @param $str  Դ��
 *
 * @return ǩ��ֵ
 */

function get_signature($str, $key)
{
    $signature = "";
    if (function_exists('hash_hmac'))
    {
        $signature = base64_encode(hash_hmac("sha1", $str, $key, true));
    }
    else
    {
        $blocksize	= 64;
        $hashfunc	= 'sha1';
        if (strlen($key) > $blocksize)
        {
            $key = pack('H*', $hashfunc($key));
        }
        $key	= str_pad($key,$blocksize,chr(0x00));
        $ipad	= str_repeat(chr(0x36),$blocksize);
        $opad	= str_repeat(chr(0x5c),$blocksize);
        $hmac 	= pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$str
                    )
                )
            )
        );
        $signature = base64_encode($hmac);
    }

    return $signature;
} 

/**
 * @brief ���ַ�������URL���룬��ѭrfc1738 urlencode
 *
 * @param $params
 *
 * @return URL�������ַ���
 */
function get_urlencode_string($params)
{
    ksort($params);
    $normalized = array();
    foreach($params as $key => $val)
    {
        $normalized[] = $key."=".QQConnect_urlencode($val);
    }

    return implode("&", $normalized);
}

/**
 * @brief ���openid�Ƿ�Ϸ�
 *
 * @param $appkey  appkey
 * @param $openid  ���û�QQ����һһ��Ӧ
 * @param $timestamp��ʱ���
 * @param $sig����ǩ��ֵ
 *
 * @return true or false
 */
function is_valid_openid($appkey, $openid, $timestamp, $sig) {
    global $QQsig;
    $str = $openid . $timestamp;
    $QQsig = get_signature($str, $appkey);
    return $sig == $QQsig; 
}

/**
 * @brief ����Get���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 * @return true or false
 */
function do_get($url, $appid, $appkey, $access_token, $access_token_secret, $openid) {
    global $_G;
    $sigstr = "GET"."&".QQConnect_urlencode($url)."&";

    //��Ҫ����, ��Ҫ������!!
    $params = $_GET;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //����������ĸ���������л�
    $normalized_str = get_normalized_string($params);
    $sigstr        .= QQConnect_urlencode($normalized_str);

    //ǩ��,ȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $url      .= "?".$normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);

    //echo "$url\n";
    return file_get_contents($url);
}

/**
 * @brief ����multi-part post ���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_multi_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid) {
    global $_G;
    //����ǩ����.Դ��:����[GET|POST]&uri&����������ĸ��������
    $sigstr = "POST"."&"."$url"."&";

    //��Ҫ����,��Ҫ������!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);


    //��ȡ�ϴ�ͼƬ��Ϣ
    foreach ($_FILES as $filename => $filevalue)
    {
        if ($filevalue["error"] != UPLOAD_ERR_OK)
        {
            //echo "upload file error $filevalue['error']\n";
            //exit;
        } 
        $params[$filename] = file_get_contents($filevalue["tmp_name"]);
    }

    //�Բ���������ĸ���������л�
    $sigstr .= get_normalized_string($params);

    //ǩ��,��Ҫȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $params["oauth_signature"] = $signature; 

    //�����ϴ�ͼƬ
    foreach ($_FILES as $filename => $filevalue)
    {
        $tmpfile = dirname($filevalue["tmp_name"])."/".$filevalue["name"];
        move_uploaded_file($filevalue["tmp_name"], $tmpfile);
        $params[$filename] = "@$tmpfile";
    }

    /*
    echo "len: ".strlen($sigstr)."\n";
    echo "sig: $sigstr\n";
    echo "key: $appkey&\n";
    */

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);
    //$httpinfo = curl_getinfo($ch);
    //print_r($httpinfo);

    curl_close($ch);
    //ɾ���ϴ���ʱ�ļ�
    unlink($tmpfile);
    return $ret;

}


/**
 * @brief ����post ���󶼿���ʹ���������
 *
 * @param $url
 * @param $appid
 * @param $appkey
 * @param $access_token
 * @param $access_token_secret
 * @param $openid
 *
 */
function do_post($url, $appid, $appkey, $access_token, $access_token_secret, $openid) {
    global $_G;
    //����ǩ����.Դ��:����[GET|POST]&uri&����������ĸ��������
    $sigstr = "POST"."&".QQConnect_urlencode($url)."&";

    //��Ҫ����,��Ҫ������!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //�Բ���������ĸ���������л�
    $sigstr .= QQConnect_urlencode(get_normalized_string($params));

    //ǩ��,��Ҫȷ��php�汾֧��hash_hmac����
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key); 
    $params["oauth_signature"] = $signature; 

    $postdata = get_urlencode_string($params);

    //echo "$sigstr******\n";
    //echo "$postdata\n";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
    curl_setopt($ch, CURLOPT_POST, TRUE); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata); 
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    curl_close($ch);
    return $ret;

}