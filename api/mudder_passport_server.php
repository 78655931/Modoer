<?php
/**
* Mudder通行证反响接口--服务端
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
define('IN_PASSPORT', TRUE);
require_once dirname(dirname(__FILE__)) . '/core/init.php';

$mud_authkey = $_G['cfg']['authkey'];
$mud_cookiepre = $_G['cookiepre'];

$keys = array('username', 'email', 'action', 'signhash');
foreach($keys as $val) {
    $$val = '';
}

$senddata = base64_decode($_GET['senddata']);
$datas = explode('&', $senddata);
foreach($datas as $val) {
    $param = explode('=', $val);
    if(in_array($param[0], $keys) && isset($param[0])) {
        ${$param[0]} = urldecode($param[1]);
    }
}

if($action != 'loginout') {

    if(!$username) {
        echo 'MODOER_ERROR:003'; //用户名为空。
        exit;
    } elseif(!is_safeusername($username)) {
        echo 'MODOER_ERROR:004'; //用户名存在非法字符串。
        exit;
    } elseif(strlen($username) > 16) {
        echo 'MODOER_ERROR:005'; //用户名长度不能超过16位。
        exit;
    }
    $tmphash = substr(md5($username . $mud_authkey), 0, 24);
    if($tmphash != $signhash) {
        echo 'MODOER_ERROR:006'; //通行证验证失败。
        exit;
    }

}

if($action == 'login') {

    if($member = $user->read($username, TRUE)) {
        $_G['db']->from($_G['dns']['dbpre'] . 'members');
        $_G['db']->set('logintime', $_G[timestamp]);
        $_G['db']->set('loginip', $_G[ip]);
        $_G['db']->set_add('logincount', 1);
        $_G['db']->where('uid', $member[uid]);
        $_G['db']->update();
        $uid = $member['uid'];
    } else {
        $uid = CreateMember($username, $email);
        if(!is_numeric($uid) || !$uid) {
            echo $error;
        }
    }

    if(!$error) {
        LoginMudder($uid, $username, 3600 * 24 * 30);
        echo 'mudder_ok_' . $uid;
    }
    exit;

} elseif($action == 'reg') {

    $uid = CreateMember($username, $email);
    if(!is_numeric($uid) || !$uid) {
        echo $error;
    }
    if(!$error) {
        LoginMudder($uid, $username, 3600 * 24 * 30);
        echo 'mudder_ok_' . $uid;
    }
    exit;

} elseif($action == 'logout') {

    $user->logout();
    echo 'mudder_ok_0';
    exit;

} else {

    echo 'MODOER_ERROR:007'; //未定义操作。
    exit;

}

function is_safeusername($name) {
    $guestexp = '\xA1\xA1|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8';
    return !preg_match("/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&]|$guestexp/is", $name);
}

function LoginMudder($uid, $username, $life) {
    header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
    $hash = create_formhash($uid, '', '');
    set_cookie('uid', $uid, $life);
    set_cookie('hash', $hash, $life);
}

function CreateMember($username, $email) {
    global $_G;

    $post = array(
        'username'      => $username,
        'password'      => md5(random(10)),
        'email'         => $email,
        'regdate'       => $_G['timestamp'],
        'logintime'     => $_G['timestamp'],
        'loginip'       => $_G['ip'],
        'logincount'    => 1,
        'groupid'       => 10,
    );

    $uid = $_G['user']->register($post);
    return $uid;
}
?>
