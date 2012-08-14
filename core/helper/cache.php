<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

// check cache file
function check_cache($cachefile, $life = -1) {
    if(is_file($cachefile)) {
        $time = filemtime($cachefile) + _G('timezone') * 3600;
        return $life < 0 || _G('timestamp') - $time < $life;
    } else {
        return false;
    }
}

function read_cache($cachefile, $mode = 'i') {
    global $_G;
    if(!is_file($cachefile)) return false;
    if(DEBUG) $_G['cachefile'][] = $cachefile;
    return $mode == 'i' ? @include $cachefile : @file_get_contents($cachefile);
}

function write_cache($name, &$data, $model=NULL, $type='return', $custom_dir = '') {
    $cachedir = $custom_dir ? MUDDER_ROOT . ($custom_dir.DS) : MUDDER_CACHE;
    if(!$model) $model = 'modoer';
    $filename = $type == 'js' ? ($name . '.js') : ($model . '_' . $name . '.php');

    if(!is_dir($cachedir)) {
        if(@mkdir($cachedir, 0777)) {
            show_error(sprintf(lang('global_mkdir_no_access'), str_replace(MUDDER_ROOT,'./',$cachedir)));
        }
    }

    if($type=='js') {
        $content = "//Modoer cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp')) . "\r\n\r\n" . $data . "\r\n";
    } elseif($type == 'return') {
        $content = "<?php\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\nreturn " . $data . "; \r\n?>";
    } else {
        $content = "<?php\r\n//Modoer cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp'))."\r\n\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\n\r\n" . $data . "\r\n\r\n?>";
    }

    $file = $cachedir . $filename;
    if(!$x = file_put_contents($file, $content)) {
        show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, '.'.DS, $file)));
    }
    @chmod($file, 0777);
}

// 去除代码中的空白和注释
function strip_whitespace($content) {
    $stripStr = '';
    //分析php源码
    $tokens = token_get_all($content);
    $last_space = false;
    for ($i = 0, $j = count($tokens); $i < $j; $i++) {
        if (is_string($tokens[$i])) {
            $last_space = false;
            $stripStr .= $tokens[$i];
        } else {
            switch ($tokens[$i][0]) {
                //过滤各种PHP注释
                case T_COMMENT:
                case T_DOC_COMMENT:
                    break;
                //过滤空格
                case T_WHITESPACE:
                    if (!$last_space) {
                        $stripStr .= ' ';
                        $last_space = true;
                    }
                    break;
                default:
                    $last_space = false;
                    $stripStr .= $tokens[$i][1];
            }
        }
    }
    return $stripStr;
}


function write_cache2($cachename, $cachedata = '', $extra = '', $mod='', $cachedir = '') {
    global $_G;

    $cachedir = $cachedir ? MUDDER_ROOT . $cachedir : MUDDER_CACHE;
    if(!$extra) {
        $filename = 'cache_' . $cachename . '.php';
    } elseif($extra == 'js') {
        $filename = $cachename . '.js';
    }
    if(!is_dir($cachedir)) {
        @mkdir($cachedir, 0777);
    }
    $cachefile = $cachedir . $filename;
    if($fp = @fopen($cachefile, 'wb')) {
        if(!$extra && !$mod) {
            @fwrite($fp, "<?php\r\n//Mudder cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp'))."\r\n\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\n\r\n".$cachedata."\r\n\r\n?>");
        } elseif($extra == 'js') {
            @fwrite($fp, "//Mudder cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp'))."\r\n\r\n".$cachedata."\r\n");
        } elseif($mod == 'return') {
            @fwrite($fp, "<?php \r\n!defined('IN_MUDDER') && exit('Access Denied');\r\nreturn $cachedata; \r\n?>");
        }
        @fclose($fp);
        @chmod($cachefile, 0777);
    } else {
        echo 'Can not write to '.$cachename.' cache files, please check directory ./data/cachefiles/ .';
        exit;
    }
}

function print_cache_loadfiles() {
    global $_G;
    if(!$_G['cachefile']) return;
    $style = 'margin:5px auto;width:98%;line-height:18px;font-family:Courier New;text-align:left;background:#eee;border-width:1px; border-style:solid;border-color:#CCC;';
    $content ='<div style="'.$style.'">';
    $content .='<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;"><a href="javascript:;" onclick="$(\'#debug_load_cachefiles\').toggle();">Load Cache Files</a> ('.count($_G['cachefile']).')</h3>';
    $content .= '<ul style="margin:0;padding:0 0 5px;list-style:none;display:none;" id="debug_load_cachefiles">';
    foreach($_G['cachefile'] as $val) {
        $content .= '<li style="padding:1px 8px;font-size:12px;">' . str_replace(MUDDER_ROOT, '', $val) . '</li>';
    }
    $content .= '</ul></div>';

    return $content;
}
?>