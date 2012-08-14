<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
function cpurl($module='modoer',$act='',$op='',$param=null) {
    $url = SELF . '?module=' . $module . '&act=' . $act;
    if($op) {
        $url .= '&op=' . $op;
    }
    if($param) foreach($param as $k=>$v) {
        if(in_array($k,array('module','act','op'))) unset($param[$k]);
    }
    if(is_array($param) && $param) {
        $url .= '&' . url_implode($param);
    } elseif(is_string($param && $param)) {
        $url .= '&' . _T($param);
    }
    return $url;
}

function cptpl($file, $module=NULL) {
    global $_G;
    if($module && isset($_G['modules'][$module])) {
        $path = 'modules' . DS . $module . DS . 'admin' . DS;
    } elseif($module) {
        lang('global_not_found_module', $module);
    } else {
	    $path = 'admin' . DS;
    }
    $filename = $path . 'templates' . DS . $file . '.tpl.php';
    return $filename;
}

function cpmsg($msg, $url = 'javascript:history.go(-1);', $min='3') {
    global $_G;
    $message = trim($msg);
	if(is_array($url)) {
		$navs = $url;
		$url_forward = str_replace('&amp;', '&', trim($url[0]['url']));
	} else {
		$url_forward = str_replace('&amp;', '&', trim($url));
	}
    $min = (int) $min;
    cpheader(0);
    include MUDDER_CORE . cptpl('cpmsg');
    cpfooter(0);
    exit;
}

function cpheader($js=1) {
	global $_G;
	include MUDDER_CORE . cptpl('cpheader');
}

function cpfooter($info=1) {
    global $_G, $_CFG;
    $mtime = explode(' ', microtime());
    $totaltime = number_format(($mtime[1] + $mtime[0] - $_G['starttime']), 6);
    $gzip = $_CFG['gzipcompress'] ? 'enabled' : 'disabled';
    $sitedebug = 'Processed in '.$totaltime.' second(s), '.$_G['db']->query_num.' queries, Gzip '.$gzip;
    $version = $_G['modoer']['version'];
    $DEBUG = '';
    if(DEBUG) {
	    $DEBUG .= $_G['db']->debug_print();
        $DEBUG .= $_G['loader']->debug_print();
    }
    include MUDDER_CORE . cptpl('cpfooter');
}
?>