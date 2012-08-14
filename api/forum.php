<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
define('API_ROOT', substr(dirname(__FILE__),0,-3));

require substr(dirname(__FILE__),0,-3) . 'core' . DIRECTORY_SEPARATOR . 'init.php';

function get_forum_list() {
	$openurl = get_open_url('forum','list');
	if(!$data = file_get_contents($openurl)) return;
	if(!pares_error($data)) {
		$data = unserialize($data);
	}
	return $data;
}

function get_open_url($mod,$act,$params=null) {
	global $_G;
	$forumurl = $_G['cfg']['forum_url'] . '/plugin.php?id=modcenter:mcenter';
	$openurl = $forumurl . '&mod=' . $mod . '&act=' . $act;
	return $openurl;
}

function pares_error(&$data) {
	if(substr($data,0,6) != 'ERROR_') return;
}
?>
