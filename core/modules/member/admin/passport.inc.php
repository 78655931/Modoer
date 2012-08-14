<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');

if(check_submit('dosubmit')) {

    $passport['enable'] = intval($_POST['passport']['enable']);
    $passport['systemname'] = _T($_POST['passport']['systemname']);
    $passport['index_url'] = trim($_POST['passport']['index_url']);
    $passport['reg_url'] = trim($_POST['passport']['reg_url']);
    $passport['login_url'] = trim($_POST['passport']['login_url']);
    $passport['logout_url'] = trim($_POST['passport']['logout_url']);
    $passport['cpwd_url'] = trim($_POST['passport']['cpwd_url']);

    if(!$passport['systemname']) redirect('membercp_passport_systemname_empty');
    if(!$passport['index_url']) redirect('membercp_passport_index_url_empty');
    if(!$passport['reg_url']) redirect('membercp_passport_reg_url_empty');
    if(!$passport['login_url']) redirect('membercp_passport_login_url_empty');
    if(!$passport['logout_url']) redirect('membercp_passport_logout_url_empty');
    if(!$passport['cpwd_url']) redirect('membercp_passport_cpwd_url_empty');

    $post = array();
    $post['passport'] = serialize($passport);

    $C->save($post, MOD_FLAG);

    $clientfile = MUDDER_ROOT . 'api/mudder_passport_client.php';
    if(!is_file($clientfile)||!is_writable($clientfile)) redirect(lang('global_file_not_exist','api/mudder_passport_client.php'));

    $mud_server_url = $_G['cfg']['siteurl'] . 'api/mudder_passport_server.php';

    if(!$filecontent = file_get_contents($clientfile)) redirect(lang('global_file_invalid','api/mudder_passport_client.php'));
    $filecontent = preg_replace("/[$]mud_server_url\s*\=\s*[\"'].*?[\"']/is", "\$mud_server_url = '$mud_server_url'", $filecontent);
    $filecontent = preg_replace("/[$]login_forward\s*\=\s*[\"'].*?[\"']/is", "\$login_forward = '{$_G['cfg']['siteurl']}'", $filecontent);
    $filecontent = preg_replace("/[$]reg_forward\s*\=\s*[\"'].*?[\"']/is", "\$reg_forward = '{$_G['cfg']['siteurl']}'", $filecontent);
    $filecontent = preg_replace("/[$]logout_forward\s*\=\s*[\"'].*?[\"']/is", "\$logout_forward = '{$_G['cfg']['siteurl']}'", $filecontent);
    $filecontent = preg_replace("/[$]mud_authkey\s*\=\s*[\"'].*?[\"']/is", "\$mud_authkey = '{$_G['cfg']['authkey']}'", $filecontent);
    $filecontent = preg_replace("/[$]mud_cookiepre\s*\=\s*[\"'].*?[\"']/is", "\$mud_cookiepre = '{$_G['cookiepre']}'", $filecontent);
    $filecontent = preg_replace("/[$]mud_charset\s*\=\s*[\"'].*?[\"']/is", "\$mud_charset = '{$_G['charset']}'", $filecontent);

    if(!@file_put_contents($clientfile, $filecontent)) {
        redirect(lang('global_file_not_exist','api/mudder_passport_client.php'));
    }

    redirect('membercp_passport_copy', cpurl($module,$act));

} else {

    $passport = $C->read('passport', MOD_FLAG);
    $passport = unserialize($passport['value']);
    $admin->tplname = cptpl('passport', MOD_FLAG);

}
?>