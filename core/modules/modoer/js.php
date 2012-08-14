<?php
/**
* JS调用
* @author moufer<moufer@163.com>
* @copyright www.mudder.com.cn
*/
!defined('IN_MUDDER') && exit('Access Denied');

//在JS调用时，总是使用绝对路径
$_G['fullalways'] = TRUE;
$_G['in_js'] = 1;

if(!$_G['cfg']['jstransfer']) {
    exit("document.write('".lang('global_js_disabled')."');");
}

if ($_G['cfg']['jsaccess'] && !in_array($_SERVER['HTTP_HOST'], explode("\r\n", $_G['cfg']['jsaccess']))) {
	exit("document.write('".lang('global_js_host_invalid',$_SERVER['HTTP_HOST'])."');");
}

$callid = (int)_get('callid');
$callname = _T(_get('callname'));

if($callid>0 || $callname) {

    $D =& $_G['loader']->model('datacall');
    if($js_file = $D->js_file($callid>0?$callid:$callname, !$callid>0)) {
        include $js_file;
    }
    $output = ob_get_contents();
    ob_end_clean();
    $output = str_replace(array('"', "\r\n", "\n"), array('\"', '', ''), $output);
    $output = "document.write(\"$output\");";
    $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start();
    echo $output;

    unset($D);
    exit;

} else {

    echo "document.write('".lang('global_datacall_callid_empty')."');";
    exit;
}
?>
