<?php
require dirname(dirname(dirname(__FILE__))).'/core/init.php';
$memberconfig = $_G['loader']->variable('config','member');

$callback = _G('cfg','siteurl').'api/taobao/callback.php';

$url = 'https://oauth.taobao.com/authorize?response_type=code&client_id='.
	$memberconfig['passport_taobao_appkey'].'&redirect_uri='.
	$callback.'&state=1';

location($url);
?>