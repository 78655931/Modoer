<?php
/**
* URL改写类
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
class ms_urlrewriter {
    
    var $list = array();
    var $domain_mod = 0;
    var $mod = 'html'; //html and pathinfo
    var $hide_index = false;

    var $config_file = '';

    function __construct() {
        $this->_load_config('html');
        $this->_load_config('pathinfo');
    }

    function set_mod($mod) {
        if(!$mod) $mod = 'html';
        $this->mod = $mod;
    }

    function set_domain_mod($mod) {
        if(!$mod) $mod = 0;
        $this->domain_mod = $mod;
    }

    function _load_config($type,$reload = FALSE) {
        if($this->list[$type] && !$reload) return;
        $config_file = MUDDER_DATA . 'rewrite_'.$type.'.inc';
        if(!is_file($config_file)) return;
        $content = @file_get_contents($config_file);
        $content = explode("\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\n", $content));
        foreach($content as $k => $v) {
            if(trim($v)=='') continue;
            $tmp = explode(" ",$v);
            $tmp2 = array();
            foreach($tmp as $kk => $vv) {
                if($vv) $tmp2[] = $vv;
            }
            if(!$tmp2) continue;
            $this->list[$type][] = $tmp2;
        }
    }

    //解析伪静态URL
    function html_recover($rewrite) {
        if(!$rewrite) return;
        if(!$url = $this->preg_recover($rewrite,'html')) {
            $url = $this->_html_recover($rewrite);
        } else {
            $output = parse_url($url);
            $_GET['m'] = basename($output['path'], '.php');
            $url = $output['query'];
        }
        parse_str($url, $output);
        foreach($output as $k => $v) {
            $_GET[$k] = $v;
        }
        return $this->_get_url_tag($_GET['m'], $output);
    }

    //解析目录形式URL
    function pathinfo_recover($pathinfo) {
        if(!$pathinfo) return;
        if(substr($paramstr,-1)!='/') $pathinfo.='/';
        if($this->domain_mod == '2') {
            list($domain,) = explode('/',$pathinfo);
            $ismodule = $domain=='index' || check_module($domain);
            if(!$ismodule) {
                if($city = get_city_for_doman($domain)) {
                    $replace = true;
                    $_GET['city_domain'] = $domain;
                    $pathinfo = str_replace($domain.'/', '', $pathinfo);
                } elseif(!check_module($domain)) {
                    $replace = true;
                }
            }
            //if($replace) $pathinfo = str_replace($domain.'/', '', $pathinfo);
        } elseif($this->domain_mod == '1') {
            $_GET['city_domain'] = get_sl_domain();
        }
        if(!$url = $this->preg_recover($pathinfo,'pathinfo')) {
            $url = $this->_pathinfo_recover($pathinfo);
        } else {
            $output = parse_url($url);
            $_GET['m'] = basename($output['path'], '.php');
            $url = $output['query'];
        }
        parse_str($url, $output);
        $_GET['real_rewrite_url'] = $_GET['m'];
        foreach($output as $k => $v) {
            $_GET[$k] = $v;
            $str = $k!='act' ? ('/' . $k . '/' . $v) : ('/' . $v);
            $_GET['real_rewrite_url'] .= $str;
        }
        return $this->_get_url_tag($_GET['m'], $output);
    }

    //解析index.php模拟
    function index_recover() {
        // 获取请求的URI
        foreach (array('REQUEST_URI', 'HTTP_X_REWRITE_URL', 'argv') as $var) {
            if ($uri = $_SERVER[$var]) {
                if ($var == 'argv') {
                    $uri = $uri[0];
                }
                break;
            }
        }
        // 去除//情况
        $uri = str_replace('//', '/', $uri);
        if (strpos($uri, 'index.php') !== false) {
            $uri = explode('index.php', $uri, 2);
        }
        // 如果没有请求的字符串返回
        if (!isset($uri[1])) {
            $_GET['m'] = '';
            $_GET['act'] = '';
            return;
        }
        // 解析
        if($uri[1]{0} == '/') $uri[1] = substr($uri[1], 1);
        if(strposex($uri[1], '.html')) {
            $this->html_recover($uri[1]);
        } else {
            $this->pathinfo_recover($uri[1]);
        }
    }

    function preg_parse($paramstr, $domain) {
        global $_CITY;
        !$domain && $domain = $_CITY['domain'];
        $urlpre = $this->hide_index ? '' : 'index.php/';
        if($this->domain_mod == 2 && $this->mod=='pathinfo') {
            $newdomain = $domain == '{GLOBAL}' ? '' : ($domain.'/');
            $urlpre = $urlpre . $newdomain;
        } elseif($this->domain_mod == 1) {
            $newdomain = $domain == '{GLOBAL}' ? _G('cfg','siteurl') : ('http://' . $domain.'.'.get_fl_domain().'/');
            if($domain == $_GET['city_domain'] && $domain != '{GLOBAL}') $newdomain = '';
            $urlpre = $newdomain . $urlpre;
        }
        if($this->list[$this->mod]) foreach($this->list[$this->mod] as $k => $val) {
            if(preg_match("/^$val[0]$/", $paramstr)) {
                if(!isset($val[1])) $val[1] = '';
                return $urlpre . preg_replace("/^$val[0]$/", "$val[1]", $paramstr);
            }
        }
        $fun = $this->mod == 'pathinfo' ? '_pathinfo_parse' : '_html_parse';
        return $this->$fun($paramstr,$domain);
    }

    function preg_recover($paramstr,$mod) {
        if(!$this->list[$mod]||!is_array($this->list[$mod])) return FALSE;
        if(substr($paramstr,-1)=='/') $paramstr = substr($paramstr,0,-1);
        foreach($this->list[$mod] as $k => $val) {
            if(preg_match("/^$val[0]$/", $paramstr)) {
                return preg_replace("/^$val[0]$/", "$val[1]", $paramstr);
            }
        }
        return FALSE;
    }

    function _html_recover($rewrite) {
        if(!$rewrite) return;
        $rewrite = basename($rewrite, '.html');
        $arr_param = explode('-', $rewrite);
        $_GET['m'] = $arr_param[0];
        $url = $split = '';
        foreach($arr_param as $k => $v) {
            $v = str_replace('_f_','-',$v);
            if($k > 0) {
                $url .= $split . $v;
            }
            if($k == 0) {
                $split = 'act=';
            } elseif($k%2 == 1) {
                $split = '&';
            } elseif($k%2 == 0) {
                $split = '=';
            }
        }
        return $url;
    }

    function _pathinfo_recover($pathinfo) {
        if(!$pathinfo) return;
        $arr_param = explode('/', $pathinfo);
        $_GET['m'] = $arr_param[0];
        $url = $split = '';
        $params = explode('-', $arr_param[1]);
        if($params) foreach($params as $k => $v) {
            $v = str_replace('_f_','-',$v);
            if($k == 0) {
                $split = 'act=';
            } elseif($k%2 == 1) {
                $split = '&';
            } elseif($k%2 == 0) {
                $split = '=';
            }
            $url .= $split . $v;
        }
        return $url;
    }

    function _html_parse($paramstr, $domain) {
        global $_CITY;
        !$domain && $domain = $_CITY['domain'];
        $urlpre = $this->hide_index ? '' : 'index.php/';
        if($this->domain_mod == 2 && $this->mod=='pathinfo') {
            $newdomain = $domain == '{GLOBAL}' ? '' : ($domain.'/');
            $urlpre = $urlpre . $newdomain;
        } elseif($this->domain_mod == 1) {
            $newdomain = $domain == '{GLOBAL}' ? _G('cfg','siteurl') : ('http://' . $domain.'.'.get_fl_domain().'/');
            if($domain == $_GET['city_domain'] && $domain != '{GLOBAL}') $newdomain = '';
            $urlpre = $newdomain . $urlpre;
        }
        if(preg_match("/^([a-z0-9_]+)\.php$/i", $paramstr, $match)) {
            return $urlpre . $match[1].'.html';
        } else {
            return $urlpre . str_replace(array('.php?act=','&amp;','=', '&'),'-', $paramstr) . '.html';
        }
    }

    function _pathinfo_parse($paramstr,$domain) {
        global $_CITY;
        !$domain && $domain = $_CITY['domain'];
        $urlpre = $this->hide_index ? '' : 'index.php/';
        if($this->domain_mod == 2) {
            $newdomain = $domain == '{GLOBAL}' ? '' : ($domain.'/');
            $urlpre = $urlpre . $newdomain;
        } elseif($this->domain_mod == 1) {
            $newdomain = $domain == '{GLOBAL}' ? _G('cfg','siteurl') : ('http://' . $domain.'.'.get_fl_domain().'/');
            if($domain == $_GET['city_domain']) $newdomain = '';
            $urlpre = $newdomain . $urlpre;
        }

        if(preg_match("/^([a-z0-9_]+)\.php$/i", $paramstr, $match)) {
            return $urlpre . $match[1];
        } else {
            return $urlpre . str_replace(array('.php?act=','&amp;','=', '&'),array('/','-','-','-'), $paramstr);
        }
    }

    function _get_url_tag($m,$params) {
        if(!$m || !preg_match("/^[a-z0-9]+$/i",$m)) return;
        $content = $m;
        if($params) foreach($params as $k=>$v) {
            if(!$k||!$v) continue;
            $k=($k=='act') ? '' : ($k . '/');
            $content .= '/' . $k . $v;
        }
        return $content;
    }

}
?>