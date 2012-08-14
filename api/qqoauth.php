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
        //请求临时token的接口地址
        $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token?";
        //生成oauth_signature签名值
        $sigstr = "GET"."&".QQConnect_urlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_request_token")."&";
        //必要参数
        $params = array();
        $params["oauth_version"]          = "1.0";
        $params["oauth_signature_method"] = "HMAC-SHA1";
        $params["oauth_timestamp"]        = $_G['timestamp'];
        $params["oauth_nonce"]            = mt_rand();
        $params["oauth_consumer_key"]     = $this->appid;
        //对参数按照字母升序做序列化
        $normalized_str = get_normalized_string($params);
        $sigstr        .= QQConnect_urlencode($normalized_str);
        //（2）构造密钥
        $key = $this->appkey."&";
        //（3）生成oauth_signature签名值。这里需要确保PHP版本支持hash_hmac函数
        $signature = get_signature($sigstr, $key);
        //构造请求url
        $url      .= $normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);
        $request_token = file_get_contents($url);
        //获取token串
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
        //解析返回值
        $result = array();
        parse_str($request_token, $result);
        return $result;
        /*
        $_SESSION["token"]        = $result["oauth_token"];
        $_SESSION["secret"]       = $result["oauth_token_secret"];
        */
    }

    function getAuthorizeURL($oauth_token, $callback) {
        //跳转到QQ登录页的接口地址, 不要更改!!
        $redirect = "http://openapi.qzone.qq.com/oauth/qzoneoauth_authorize?oauth_consumer_key=".$this->appid."&";
        //构造请求URL
        $redirect .= "oauth_token=".$oauth_token."&oauth_callback=".QQConnect_urlencode($callback);
        return $redirect;
    }

    function getAccessToken($vericode) {
        global $_G, $QQsig;
        //请求具有Qzone访问权限的access_token的接口地址, 不要更改!!
        $url    = "http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token?";
       
        //生成oauth_signature签名值。签名值生成方法详见（http://wiki.opensns.qq.com/wiki/【QQ登录】签名参数oauth_signature的说明）
        //（1） 构造生成签名值的源串（HTTP请求方式 & urlencode(uri) & urlencode(a=x&b=y&...)）
        $sigstr = "GET"."&".QQConnect_urlencode("http://openapi.qzone.qq.com/oauth/qzoneoauth_access_token")."&";

        //必要参数，不要随便更改!!
        $params = array();
        $params["oauth_version"]          = "1.0";
        $params["oauth_signature_method"] = "HMAC-SHA1";
        $params["oauth_timestamp"]        = $_G['timestamp'];
        $params["oauth_nonce"]            = mt_rand();
        $params["oauth_consumer_key"]     = $this->appid;
        $params["oauth_token"]            = $this->_token;
        $params["oauth_vericode"]         = $vericode;

        //对参数按照字母升序做序列化
        $normalized_str = get_normalized_string($params);
        $sigstr        .= QQConnect_urlencode($normalized_str);
        //echo "sigstr = $sigstr";
        //（2）构造密钥
        $key = $this->appkey."&".$this->_token_secret;
        //（3）生成oauth_signature签名值。这里需要确保PHP版本支持hash_hmac函数
        $signature = get_signature($sigstr, $key);
        //构造请求url
        $url      .= $normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);
        //获取
        $access_str = file_get_contents($url);
        //错误处理
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
        //解析返回参数
        $result = array();
        parse_str($access_str, $result);
        //将access token，openid保存起来！！！
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
     * @brief 获取用户信息.请求需经过URL编码，编码时请遵循 RFC 1738
     * 
     */
    function verify_credentials()
    {
        //获取用户信息的接口地址, 不要更改!!
        $url    = "http://openapi.qzone.qq.com/user/get_user_info";
        $info   = do_get($url, $this->appid, $this->appkey, $this->token, $this->token_secret, $this->openid);
        $arr = array();
        $arr = json_decode($info, true);
        return $arr;
    }

}

/**
 * @brief QQ登录中对url做编解码的统一函数
 * 按照RFC 1738 对URL进行编码
 * 除了-_.~之外的所有非字母数字字符都将被替换成百分号(%)后跟两位十六进制数
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
 * @brief 对参数进行字典升序排序
 *
 * @param $params 参数列表
 *
 * @return 排序后用&链接的key-value对（key1=value1&key2=value2...)
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
 * @brief 使用HMAC-SHA1算法生成oauth_signature签名值 
 *
 * @param $key  密钥
 * @param $str  源串
 *
 * @return 签名值
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
 * @brief 对字符串进行URL编码，遵循rfc1738 urlencode
 *
 * @param $params
 *
 * @return URL编码后的字符串
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
 * @brief 检查openid是否合法
 *
 * @param $appkey  appkey
 * @param $openid  与用户QQ号码一一对应
 * @param $timestamp　时间戳
 * @param $sig　　签名值
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
 * @brief 所有Get请求都可以使用这个方法
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

    //必要参数, 不要随便更改!!
    $params = $_GET;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //参数按照字母升序做序列化
    $normalized_str = get_normalized_string($params);
    $sigstr        .= QQConnect_urlencode($normalized_str);

    //签名,确保php版本支持hash_hmac函数
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $url      .= "?".$normalized_str."&"."oauth_signature=".QQConnect_urlencode($signature);

    //echo "$url\n";
    return file_get_contents($url);
}

/**
 * @brief 所有multi-part post 请求都可以使用这个方法
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
    //构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
    $sigstr = "POST"."&"."$url"."&";

    //必要参数,不要随便更改!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);


    //获取上传图片信息
    foreach ($_FILES as $filename => $filevalue)
    {
        if ($filevalue["error"] != UPLOAD_ERR_OK)
        {
            //echo "upload file error $filevalue['error']\n";
            //exit;
        } 
        $params[$filename] = file_get_contents($filevalue["tmp_name"]);
    }

    //对参数按照字母升序做序列化
    $sigstr .= get_normalized_string($params);

    //签名,需要确保php版本支持hash_hmac函数
    $key = $appkey."&".$access_token_secret;
    $signature = get_signature($sigstr, $key);
    $params["oauth_signature"] = $signature; 

    //处理上传图片
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
    //删除上传临时文件
    unlink($tmpfile);
    return $ret;

}


/**
 * @brief 所有post 请求都可以使用这个方法
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
    //构造签名串.源串:方法[GET|POST]&uri&参数按照字母升序排列
    $sigstr = "POST"."&".QQConnect_urlencode($url)."&";

    //必要参数,不要随便更改!!
    $params = $_POST;
    $params["oauth_version"]          = "1.0";
    $params["oauth_signature_method"] = "HMAC-SHA1";
    $params["oauth_timestamp"]        = $_G['timestamp'];
    $params["oauth_nonce"]            = mt_rand();
    $params["oauth_consumer_key"]     = $appid;
    $params["oauth_token"]            = $access_token;
    $params["openid"]                 = $openid;
    unset($params["oauth_signature"]);

    //对参数按照字母升序做序列化
    $sigstr .= QQConnect_urlencode(get_normalized_string($params));

    //签名,需要确保php版本支持hash_hmac函数
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