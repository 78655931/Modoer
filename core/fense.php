<?php
!defined('IN_MUDDER') && exit('Access Denied');

if($_G['attackevasive'] == 1 || $_G['attackevasive'] == 3) {
	if ($_C['lastrequest']) {
		list($lastrequest, $lastpath) = explode("\t", $_C['lastrequest']);
		$onlinetime = $_G['timestamp'] - $lastrequest;
	} else {
		$lastrequest = $lastpath = '';
	}
	$REQUEST_URI  = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	if ($REQUEST_URI == $lastpath && $onlinetime < 2) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_G['charset'];?>" />
<meta http-equiv="Refresh" content="2;url=<?php echo $REQUEST_URI;?>">
<title>Refresh Limitation Enabled</title>
</head>
<body style="table-layout:fixed; word-break:break-all">
<center>
<div style="margin-top:100px;background-color:#f1f1f1;text-align:center;width:600px;padding:20px;margin-right: auto;margin-left: auto;font-family: Verdana, Tahoma; color: #666666; font-size: 12px">
  <p><b>Refresh Limitation Enabled</b></p>
  <p>The time between your two requests is smaller than 2 seconds, please do NOT refresh and wait for automatical forwarding ...</p>
</div>
</center>
</body>
</html>
<?php
    exit();
	}
	set_cookie('lastrequest', $_G['timestamp'] . "\t" . $REQUEST_URI);
}

if(($_G['attackevasive'] == 2 || $_G['attackevasive'] == 3) && ($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'])) {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php $charset;?>" />
<title>Proxy Connection Denied</title>
</head>
<body style="table-layout:fixed; word-break:break-all">
<center>
<div style="margin-top:100px;background-color:#f1f1f1;text-align:center;width:600px;padding:20px;margin-right: auto;margin-left: auto;font-family: Verdana, Tahoma; color: #666666; font-size: 12px">
  <p><b>Proxy Connection Denied</b></p>
  <p>Your request was forbidden due to the administrator has set to deny all proxy connection.</p>
</div>
</center>
</body>
</html>
<?php
	exit;
}
?>