<?php
/**
* Mudderͨ��֤����ӿ�--�ͻ��� (��װ������ϵͳ��Ŀ¼)
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
// ͨ��֤�ӿ���֤ - ������URL
$mud_server_url = 'http://www.28mc.com/api/mudder_passport_server.php';
// ��¼����תҳ��
$login_forward = 'http://www.28mc.com/';
// ע�����תҳ��
$reg_forward = 'http://www.28mc.com/';
// �ǳ�����תҳ��
$logout_forward = 'http://www.28mc.com/';
// ͨ��֤������
$mud_authkey = 'M14T3L39U46611';
// modoer cookie ǰ׺
$mud_cookiepre = 'JUDR4_';
// �������
$mud_charset = 'gb2312';

function SyncMudder($username, $email, $action, $cookielife) {
    global $mud_authkey, $mud_server_url;

    $keys = array('username', 'email', 'action');
    $param = '';
    foreach($keys as $val) {
        if(!empty($$val)) $param .= $val . '=' . urlencode($$val) . '&';
    }
    $signhash = substr(md5($username . $mud_authkey), 0, 24);
    $param .= 'signhash=' . $signhash;
    $server_url = $mud_server_url . '?senddata=' . base64_encode($param);

    if(function_exists('file_get_contents')) {
        if(!$recvdata = @file_get_contents($server_url)) {
            die('MODOER_ERROR:001');
        }
    } else {
        if($fp = @fopen($server_url, 'r')) {
            $recvdata = $fp ? @fread($fp, 200) : '';
            @fclose($fp);
        }
        if(!$recvdata) {
            die('MODOER_ERROR:002');
        }
    }

    if(substr($recvdata, 0, 10) == 'mudder_ok_') {
        $uid = str_replace('mudder_ok_', '', $recvdata);
        if($uid != '') {
            return $uid;
        } else {
            return $recvdata;
        }
    } else {
        return $recvdata;
    }
}

function SyncLogin($username, $logout = FALSE, $reutrn_url = FALSE) {
    global $mud_authkey, $mud_server_url;

    $keys = array('username', 'email', 'action');
    $param = '';
    $email = '-';
    $action = $logout ? 'logout' : 'login';
    foreach($keys as $val) {
        if(!empty($$val)) $param .= $val . '=' . urlencode($$val) . '&';
    }
    $signhash = substr(md5($username . $mud_authkey), 0, 24);
    $param .= 'signhash=' . $signhash;
    $server_url = $mud_server_url . '?senddata=' . base64_encode($param);
    if($reutrn_url) {
        return base64_encode($server_url);
    } else {
        return '<script type="text/javascript" src="' . $server_url . '" reload="1"></script>';
        //return '<iframe src="' . $server_url . '"></iframe>';
    }
}

function LoginMudder($uid, $username, $life) {
    global $mud_cookiepre, $mud_authkey, $cpath, $domain;

    $timestamp = time();
    $hash = modoer_formhash($uid, '', '');
    setcookie($mud_cookiepre . 'uid', $uid, $timestamp + $life, $cpath, $domain);
    setcookie($mud_cookiepre . 'myauth', $hash, $timestamp + $life, $cpath, $domain);
}

function modoer_formhash($uid, $username, $password) {
    global $mud_authkey;
    return substr(md5($mud_authkey . $uid . $username . $password), 8, 8);
}
?>
