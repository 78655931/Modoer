<?php

define('IN_DISCUZ', TRUE);
define('IN_MUDDER', TRUE);
define('IN_UCENTER', TRUE);

define('UC_CLIENT_VERSION', '1.5.0');	//note UCenter 版本标识
define('UC_CLIENT_RELEASE', '20081031');

define('API_DELETEUSER', 1);		//note 用户删除 API 接口开关
define('API_RENAMEUSER', 1);		//note 用户改名 API 接口开关
define('API_GETTAG', 1);		//note 获取标签 API 接口开关
define('API_SYNLOGIN', 1);		//note 同步登录 API 接口开关
define('API_SYNLOGOUT', 1);		//note 同步登出 API 接口开关
define('API_UPDATEPW', 1);		//note 更改用户密码 开关
define('API_UPDATEBADWORDS', 1);	//note 更新关键字列表 开关
define('API_UPDATEHOSTS', 1);		//note 更新域名解析缓存 开关
define('API_UPDATEAPPS', 1);		//note 更新应用列表 开关
define('API_UPDATECLIENT', 1);		//note 更新客户端缓存 开关
define('API_UPDATECREDIT', 1);		//note 更新用户积分 开关
define('API_GETCREDITSETTINGS', 1);	//note 向 UCenter 提供积分设置 开关
define('API_GETCREDIT', 1);		//note 获取用户的某项积分 开关
define('API_UPDATECREDITSETTINGS', 1);	//note 更新应用积分设置 开关

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('DISCUZ_ROOT', '../');
define('MUDDER_ROOT', substr(dirname(__FILE__), 0, -3));

$_G = array();
require_once MUDDER_ROOT.'./data/config.php';
require_once MUDDER_ROOT.'./data/config_uc.php';
$_G['cfg'] = include MUDDER_ROOT.'./data/cachefiles/modoer_config.php';

//note 普通的 http 通知方式
if(!defined('IN_UC')) {

	error_reporting(0);
	set_magic_quotes_runtime(0);
	
	defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

	$_DCACHE = $get = $post = array();

	$code = @$_GET['code'];
	parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
	if(MAGIC_QUOTES_GPC) {
		$get = _stripslashes($get);
	}

	//mo_debug('debug0.txt',$get);

    //时间区间可能引起cookie失效和造成通信失败
	$timestamp = time();
	if($timestamp - $get['time'] > 3600) {
		exit('Authracation has expiried');
	}
	if(empty($get)) {
		exit('Invalid Request');
	}
	$action = $get['action'];

	require_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
	$post = xml_unserialize(file_get_contents('php://input'));

	if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {

        require_once MUDDER_ROOT . './core/lib/mysql.php';

        $_G['dns']['pconnect'] = 0;
        $GLOBALS['tablepre'] = $_G['dns']['dbpre'];
        $GLOBALS['db'] = new ms_mysql($_G['dns']);
        $GLOBALS['db']->connect();

		$uc_note = new uc_note();
		exit($uc_note->$get['action']($get, $post));
	} else {
		exit(API_RETURN_FAILED);
	}

//note include 通知方式
} else {

    require_once MUDDER_ROOT . './core/lib/mysql.php';

    $_G['dns']['pconnect'] = 0;
    $GLOBALS['tablepre'] = $_G['dns']['dbpre'];
    $GLOBALS['db'] = new ms_mysql($_G['dns']);
    $GLOBALS['db']->connect();
}

class uc_note {

	var $dbconfig = '';
	var $db = '';
	var $tablepre = '';
    var $dbpre = '';
	var $appdir = '';

	function _serialize($arr, $htmlon = 0) {
		if(!function_exists('xml_serialize')) {
			include_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
		}
		return xml_serialize($arr, $htmlon);
	}

	function uc_note() {
		$this->appdir = substr(dirname(__FILE__), 0, -3);
		$this->dbconfig = $this->appdir.'./data/config.php';
		$this->db = $GLOBALS['db'];
		$this->tablepre = $this->dbpre = $GLOBALS['tablepre'];
	}

	function test($get, $post) {
        return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
        $uids = $get['ids'];
        !API_DELETEUSER && exit(API_RETURN_FORBIDDEN);

        $this->db->exec("DELETE FROM {$this->dbpre}members WHERE uid IN ($uids)");
        $this->db->exec("DELETE FROM {$this->dbpre}spaces WHERE uid IN ($uids)");
        $this->db->exec("DELETE FROM {$this->dbpre}member_passport WHERE uid IN ($uids)");
        $this->db->exec("DELETE FROM {$this->dbpre}favorites WHERE uid IN ($uids)");

        return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		$uid = $get['uid'];
		$usernameold = $get['oldusername'];
		$usernamenew = $get['newusername'];
		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}

        $this->db->exec("UPDATE {$this->dbpre}members SET username='$usernamenew' WHERE uid='$uid'");

		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		$name = $get['id'];
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}
		
		$return = array();
		return $this->_serialize($return, 1);
	}

	function synlogin($get, $post) {
		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}

		$uid = $get['uid'];
		$username = $get['username'];

        header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        $cookietime = 31536000;
        $q = $this->db->query("SELECT uid,username FROM {$this->dbpre}members WHERE uid='$uid'");
        if($q) {
            $member = $q->fetch_array();
            $q->free_result();
        }
        if($member) {
            mo_setcookie('uid', $uid, $cookietime);
            mo_setcookie('hash', mo_formhash($uid, $username, ''), $cookietime);
        } else {
            mo_setcookie('username', $username, $cookietime);
            mo_setcookie('activationauth', base64_encode($uid."\t".$username), $cookietime);
        }
    }

	function synlogout($get, $post) {
		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}

		//note 同步登出 API 接口
		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

        mo_setcookie('uid', '', -86400 * 365);
        mo_setcookie('hash', '', -86400 * 365);
        mo_setcookie('username', '', -86400 * 365);
        mo_setcookie('activationauth', '', -86400 * 365);
	}

	function updatepw($get, $post) {
		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}

        $username = $get['username'];
        $password = md5($get['password']);
	    $this->db->exec("UPDATE {$this->dbpre}members SET password='$password' WHERE username='$username'");

		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}

		$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updateapps($get, $post) {
		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}
		$UC_API = $post['UC_API'];

		//note 写 app 缓存文件
		$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		//note 写配置文件
		if(is_writeable($this->dbconfig)) {
			$configfile = trim(file_get_contents($this->dbconfig));
			$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
			$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
			if($fp = @fopen($this->dbconfig, 'w')) {
				@fwrite($fp, trim($configfile));
				@fclose($fp);
			}
		}

		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}
		$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);
		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}

		//mo_debug('debug1.txt',$get);

		$credit = (int)$get['credit'];
		$amount = $get['amount'];
		$uid = (int)$get['uid'];
		$point = 'point' . $credit;
		$this->db->exec("UPDATE {$this->dbpre}members SET $point=$point+$amount WHERE uid=$uid");

		//mo_debug('debug2.txt',$get);

		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}

		$uid = $get['uid'];
		$credit = 'point' . $get['credit'];

		$q = $this->db->query("SELECT * FROM {$this->dbpre}members WHERE uid='$uid'");
        $r = $q->fatch_array();
        return (int) $r[$credit];
	}

	function getcreditsettings($get, $post) {
		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}

		$config = include MUDDER_ROOT.'./data/cachefiles/member_config.php';
		if(!$config) return API_RETURN_FORBIDDEN;

		$point_group = unserialize($config['point_group']);
		if(!$point_group) return API_RETURN_FORBIDDEN;

        $credits = array();
		foreach($point_group as $key=>$val) {
			if(!$val['enabled']) continue;
			$num = substr($key, 5);
			$credits[$num] = array($val['name'], $val['unit']);
		}

		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
        global $timestamp;
		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}

        $outextcredits = array();
        if($get['credit']) foreach($get['credit'] as $appid => $credititems) {
            if($appid == UC_APPID) {
                foreach($credititems as $value) {
                    $outextcredits[$value['appiddesc'].'|'.$value['creditdesc']] = array(
                        'creditsrc' => $value['creditsrc'],
                        'title' => $value['title'],
                        'unit' => $value['unit'],
                        'ratio' => $value['ratio']
                    );
                }
            }
        }

        $this->db->query("REPLACE INTO {$this->dbpre}config (variable, value, module) VALUES ('outextcredits', '".addslashes(serialize($outextcredits))."', 'ucenter');");

        $config = include MUDDER_ROOT.'./data/cachefiles/ucenter_config.php';
        $config['outextcredits'] = serialize($outextcredits);
        $cachedata = "return ".mo_arrayeval($config).";\r\n";

        if($fp = @fopen(MUDDER_ROOT.'./data/cachefiles/ucenter_config.php', 'wb')) {
            @fwrite($fp, "<?php\r\n//Modoer cache file\r\n//Created on ".date('Y-m-d H:i:s', $timestamp)."\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\n\r\n".$cachedata."\r\n?>");
            @fclose($fp);
            @chmod($cachefile, 0777);
        }

		return API_RETURN_SUCCEED;
	}
}

//note 使用该函数前需要 require_once $this->appdir.'./config.inc.php';
function _setcookie($var, $value, $life = 0, $prefix = 1) {
	global $_G, $timestamp, $_SERVER;
	setcookie(($prefix ? $_G['cookiepre'] : '').$var, $value,
		$life ? $timestamp + $life : 0, $_G['cookiepath'],
		$_G['cookiedomain'], $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

	$ckey_length = 4;

	$key = md5($key ? $key : UC_KEY);
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);

	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);

	$result = '';
	$box = range(0, 255);

	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}

	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}

}

function _stripslashes($string) {
	if(is_array($string)) {
		foreach($string as $key => $val) {
			$string[$key] = _stripslashes($val);
		}
	} else {
		$string = stripslashes($string);
	}
	return $string;
}

/**************** Modoer库函数 ********************/
function mo_setcookie($var, $value, $life = 0) {
	global $timestamp, $_G, $_SERVER;
	$life = $life ? ($timestamp + $life) : 0;
	$secure = $_SERVER['SERVER_PORT'] == '443' ? 1 : 0;
	$var = $_G['cookiepre'].$var;
	return setcookie($var, $value, $life, $_G['cookiepath'], $_G['cookiedomain'], $secure);
}

//生成表单序列
function mo_formhash($p1, $p2, $p3) {
    global $_G;
    $authkey = $_G['cfg']['authkey'];
    return substr(md5($authkey . $p1 . $p2 . $p3), 8, 8);
}

function mo_arrayeval($array, $level = 0) {

	if(!is_array($array)) {
		return "'".$array."'";
	}

	if(is_array($array) && function_exists('var_export')) {
		return var_export($array, true);
	}

	$space = '';
	for($i = 0; $i <= $level; $i++) {
		$space .= "\t";
	}
	$evaluate = "array (\n\r";
	$comma = $space;
	if(is_array($array)) {
		foreach($array as $key => $val) {
			$key = is_string($key) ? '\''.mo_addcslashes($key).'\'' : $key;
			$val = !is_array($val) && (!preg_match("/^\-?[0-9]\d*$/", $val) || strlen($val) > 12) ? '\''.mo_addcslashes($val, '\'\\').'\'' : $val;
			if(is_array($val)) {
				$evaluate .= "$comma$key => ".mo_addcslashes($val, $level + 1);
			} else {
				$evaluate .= "$comma$key => $val";
			}
			$comma = ",\n\r$space";
		}
	}
	$evaluate .= "\n\r$space)";
	return $evaluate;
}

function mo_addcslashes($string) {
    return $string ? addcslashes($string, '\'\\') : '';
}

function mo_debug($name, $array) {
    global $timestamp;
    $logfile = MUDDER_ROOT.'./api/api_'.$name.'.log';
    if(@$fp = fopen($logfile, 'a')) {
        @fwrite($fp, date('Y-m-d H:i:s', $timestamp)."\r\n");
        @fwrite($fp, (is_array($array) ? mo_arrayeval($array,TRUE) : $array) . "\r\n");
        @fwrite($fp, '--------------------------------------------'."\r\n");
        @fclose($fp);
    }
}