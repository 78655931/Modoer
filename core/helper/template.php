<?php
!defined('IN_MUDDER') && exit('Access Denied');

function parse_template($tplfile, $objfile) {
    global $_G;

    $nest = 5;

	if(!@$fp = fopen(MUDDER_ROOT . $tplfile, 'r')) {
		show_error(lang('global_template_no_access', $tplfile));
	}

	$template = fread($fp, filesize(MUDDER_ROOT . $tplfile));
	fclose($fp);

	$var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
	$const_regexp = "([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)";

    $template = preg_replace("/\<\!\-\-\{(.+?)\}\-\-\>/s", "{\\1}", $template);
    $template = preg_replace("/\{(\\\$[a-zA-Z0-9_\[\]\'\"\$\.\x7f-\xff]+)\}/s", "<?=\\1?>", $template);
    $template = preg_replace_callback("/\{datacall:([0-9]+)\}/i", array(&$_G['datacall'],'datacall'), $template);
    $template = preg_replace_callback("/\{datacallname:([^}]+)\}/i", array(&$_G['datacall'],'datacallname'), $template);
    $template = str_replace("{LF}", "<?=\"\\r\\n\"?>", $template);
    $template = preg_replace("/$var_regexp/es", "addquote('<?=\\1?>')", $template);
    $template = preg_replace("/\<\?\=\<\?\=$var_regexp\?\>\?\>/es", "addquote('<?=\\1?>')", $template);
    $template = preg_replace("/\<\?\=(.+?)\?\>(\->[a-zA-Z0-9_\x7f-\xff]*)/is", "<?=\\1\\2?>", $template);

    $template = "<? !defined('IN_MUDDER') && exit('Access Denied'); ?>\n$template";
    $template = preg_replace("/[\n\r\t]*\{lang\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? echo lang('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{template\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include template('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{template\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('\n<? include template(\\1); ?>\n','')", $template);
	$template = preg_replace("/[\n\r\t]*\{include\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('\n<? if(\$_incfile_=\\1)include_once(\$_incfile_);?>\n','')", $template);
	$template = preg_replace("/[\n\r\t]*\{spacestyle\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include spacestyle('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{itemstyle\s+([a-z0-9_]+)\}[\n\r\t]*/is", "\n<? include itemstyle('\\1'); ?>\n", $template);
	$template = preg_replace("/[\n\r\t]*\{elseif\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('\n<? } elseif(\\1) { ?>\n','')", $template);
	$template = preg_replace("/[\n\r\t]*\{else\}[\n\r\t]*/is", "\n<? } else { ?>\n", $template);
    $template = preg_replace("/[\n\r\t]*\{eval\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<?php \\1 ?>','')", $template);
    $template = preg_replace("/[\n\r\t]*\{print\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<?php echo \\1; ?>','')", $template);

    $template = preg_replace("/[\n\r\t]*\{print:(.+?)\s+(.+?)\((.*?)\)\}[\n\r\t]*/ies", "strip_print('\\1','\\2',strip_url('\\3',''))", $template);
    $template = preg_replace("/[\n\r\t]*\{get:(.+?)\s+([a-z0-9_]+)=(.+?)\((.*?)\)\}[\n\r\t]*/ies", "strip_get('\\1','\\2','\\3',strip_url('\\4',''))", $template);
    $template = preg_replace("/[\n\r\t]*\{getempty\((.*?)\)\}[\n\r\t]*/i", "<?php }if(empty(\$_QUERY['get_\\1'])){ ?>\r\n", $template);
    $template = preg_replace("/[\n\r\t]*\{\/get\}[\n\r\t]*/i", "<?php } ?>\r\n", $template);

    $template = preg_replace("/[\n\r\t]*\{sublen\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<?php echo trimmed_title(\\1); ?>','')", $template);
    $template = preg_replace("/[\n\r\t]*\{date\s+(.+?)\}[\n\r\t]*/ies", "stripvtags('<?php echo newdate(\\1); ?>','')", $template);
    $template = preg_replace("/[\n\r\t]*\{url\s+(.+?)\}[\n\r\t]*/ies", "strip_url('<?php echo url(\"\\1\"); ?>','')", $template);
    $template = preg_replace("/[\n\r\t]*\{url\(+(.+?)\)\}[\n\r\t]*/ies", "stripvtags('<?php echo url(\\1); ?>','')", $template);

	for($i = 0; $i < $nest; $i++) {
        $template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('\n<? if(is_array(\\1)) { foreach(\\1 as \\2) { ?>','\n\\3\n<? } } ?>\n')", $template);
        $template = preg_replace("/[\n\r\t]*\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}[\n\r\t]*(.+?)[\n\r\t]*\{\/loop\}[\n\r\t]*/ies", "stripvtags('\n<? if(is_array(\\1)) { foreach(\\1 as \\2 => \\3) { ?>','\n\\4\n<? } } ?>\n')", $template);
        $template = preg_replace("/[\n\r\t]*\{dbres\s+(\S+)\s+(\S+)\}[\n\r]*(.+?)[\n\r]*\{\/dbres\}[\n\r\t]*/ies", "stripvtags('\n<? if(\\1) { while(\\2 = \\1->fetch_array()) { ?>','\n\\3\n<? } } ?>\n')", $template);
        $template = preg_replace("/[\n\r\t]*\{if\s+(.+?)\}[\n\r]*(.+?)[\n\r]*\{\/if\}[\n\r\t]*/ies", "stripvtags('<? if(\\1) { ?>','\n\\2<? } ?>\n')", $template);
	}

	$template = preg_replace("/\{$const_regexp\}/s", "<?=\\1?>", $template);
	$template = preg_replace("/ \?\>[\n\r]*\<\? /s", " ", $template);

	if(!@$fp = fopen(MUDDER_ROOT . $objfile, 'w')) {
		show_error(lang('global_template_cache_no_access', $objfile));
	}

    $template = preg_replace("/\"(http)?[\w\.\/:]+\?[^\"]+?&[^\"]+?\"/e", "transamp('\\0')", $template);
	$template = preg_replace("/(\<script[^\>]*?src=\"(.+?)\".*?\>\s*\<\/script\>)/ise", "stripscriptamp('\\1','\\2')", $template);

	flock($fp, 2);
	fwrite($fp, $template);
	fclose($fp);
}

function transamp($str) {
	$str = str_replace('&', '&amp;', $str);
	$str = str_replace('&amp;amp;', '&amp;', $str);
	$str = str_replace('\"', '"', $str);
	return $str;
}

function addquote($var) {
	return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function stripvtags($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
	return $expr.$statement;
}

function stripscriptamp($script, $src) {
	$s = str_replace('&amp;', '&', $src);
    $search = array($src, '\"');
    $replace = array($s, '"');
	return str_replace($search, $replace, $script);
}

function strip_url($expr, $statement) {
	$expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "{\\1}", $expr));
	$statement = str_replace("\\\"", "\"", $statement);
    return $expr.$statement;
}

function strip_print($flag, $fun, $params) {
    if($flag = trim($flag)) {
        $modules = _G('modules');
        if(!isset($modules[$flag])) $flag = '';
    }
    $restr = "<?php echo template_print('$flag','$fun',".strip_params($params).");?>";
    return $restr;
}

function strip_get($flag, $var, $fun, $params) {
    if($flag = trim($flag)) {
        $modules = _G('modules');
        if(!isset($modules[$flag])) $flag = '';
    }
    $restr = "\r\n<?php \$_QUERY['get_$var']=\$_G['datacall']->datacall_get('$fun',".strip_params($params).",'$flag');\r\n";
    $restr .= "if(is_array(\$_QUERY['get_$var']))foreach(\$_QUERY['get_$var'] as \${$var}_k => \${$var}) { ?>\r\n";
    return $restr;
}

function strip_params($str) {
    $var_regexp = "((\\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(\[[a-zA-Z0-9_\-\.\"\'\[\]\$\x7f-\xff]+\])*)";
    if(preg_match("/^\{$var_regexp\}$/i", $str)) {
        $result = substr($str,1,-1);
        $is_var = true;
    } else {
        if(!$str) return 'array()';
        $str = str_replace(array('<?='), '?>', $str);
        $info = explode('/', $str);
        if(count($info)<2) return '';
        $result = 'array(';
        for($i=0; $i<count($info); $i++) {
            $tmp = $info[++$i];
			$is_var = false;
			if(preg_match("/^\{$var_regexp\}$/i", $tmp)) {
				$tmp = substr($tmp,1,-1);
				$is_var = true;
			}
            if($tmp == '') continue;
			$result .= "'".$info[$i-1]."'=>".($is_var?$tmp:(is_numeric($tmp)?$tmp:('"'.$tmp.'"'))).",";
        }
        $result .= ')';
    }
    return $result;
}
?>