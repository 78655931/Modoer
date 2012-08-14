<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

lang('admincp_menu_home'); //引导加载admincp语言文件
$menu_setting = $_G['lng']['admincp']['admincp_menu_arrry_setting'];
$menu_website = $_G['lng']['admincp']['admincp_menu_arrry_website'];

$tab_arr = array(
    'home' => 'modoer',
    'setting' => 'modoer',
    'website' => 'modoer',
    'member' => 'member',
    'item' => 'item',
    'product' => 'product',
    'review' => 'review',
	'article' => 'article',
    'module' => 'modoer',
    'plugin' => 'modoer',
    'help' => 'modoer',
);

$tab = _T($_GET['tab']);
$tabmenu = empty($tab) ? 'menu_home' : 'menu_' . $tab;

if($flag = $tab_arr[$tab]) {
    if($flag == 'modoer') {
        if($tab=='home') {
            $$tabmenu = load_home_menu();
        } elseif($tab=='module') {
            $$tabmenu = load_modules_menu();
        } elseif($tab=='plugin') {
            $$tabmenu = load_plugin_menu();
        } elseif($tab=='help') {
            $$tabmenu = load_help_menu();
        }
    } else {
        $$tabmenu = load_module_menu($flag, TRUE);
    }
} else {
    show_error('admincp_unkown_menu');
}

$showmenu = '';
$items = 0;
foreach($$tabmenu as $menuvalue) {
    //$closeli = $items > 4 ? ' class="closed"' : '';
    $showmenu .= "<li{$closeli}>\r\n";
    foreach($menuvalue as $k => $y) {
        $m_action = $m_caption = $m_file = $m_op = '';
        if($k === 'module') continue;
        if($k === 'title') {
            $items++;
            $showmenu .= "\t<span class=\"folder\">$y</span>\r\n";
            $showmenu .= "\t\t<ul>\r\n";
        } else {
            if(is_array($y)) {
                $showmenu .= "\t\t\t".'<li><span class="file"><a href="'.$y['url'].'" target="main">'.$y['title'].'</a></span></li>'."\r\n";
            } else {
                list($m_module, $m_caption, $m_act, $m_op) = explode('|', $y);
                $params = array('module='.$m_module);
                $m_act && $params[] = 'act='.$m_act;
                $m_op && $params[] = 'op='.$m_op;
                $showmenu .= "\t\t\t".'<li><span class="file"><a href="'.SELF.'?'.implode('&amp;',$params).'" target="main">'.$m_caption.'</a></span></li>'."\r\n";
                unset($params);
            }
        }
    }
    $showmenu .= "\t\t</ul>\r\n";
    $showmenu .= "</li>\r\n";
}
unset($closeli, $items, $menuvalue, $m_module, $m_caption, $m_act, $m_op, $k, $y);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html;charset=<?=$_G['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css" />
<link rel="stylesheet" type="text/css" href="./static/images/admin/treeview.css" />
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<script type="text/javascript" src="./static/javascript/admin.js"></script>
<script type="text/javascript" src="./static/javascript/treeview.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#left_menu").treeview();
        $(document).keydown(resetEscAndF5);
    });
</script>
</head>
<body>
<div class="tvborder">
<ul id="left_menu" class="filetree">
    <?=$showmenu?>
</ul>
</div>
</body>
</html>
<?php
function parse_menu($str) {
    list($m_module, $m_caption, $m_act, $m_op) = explode('|', $str);
    $params = array('module=' . $m_module);
    $m_act && $params[] = 'act=' . $m_act;
    $m_op && $params[] = 'op=' . $m_op;
    return array(
        'name' => $m_caption,
        'url' => SELF . '?' . implode('&amp;', $params)
    );
}

function load_home_menu() {
    global $_G;

    $result = array();
    if(!$_G['admin']->is_founder) {
        $result[] = $_G['lng']['admincp']['admincp_menu_quick_links'];
        return $result;
    }

    if(!$console_menuid = $_G['cfg']['console_menuid']) {
        $console_menuid = 3;
    }
    $menu = $_G['loader']->variable('menu_'.$console_menuid);

    if(!$menu) return array();
    $result = array('title' => 'Quick Links');
    foreach($menu as $val) {
        $result[] = array('title'=>$val['title'], 'url'=>$val['url']);
    }
    return array($result);
}

function load_modules_menu() {
    global $_G;

    $result = array();
    if($_G['admin']->check_access('modoer')) {
        $result[] = $_G['lng']['admincp']['admincp_menu_arrry_module'];
    }

    $c_flags = array('member','item','product','review','article');
    foreach($_G['modules'] as $key => $val) {
        if(!$_G['admin']->check_access($key)) continue;
        if(in_array($key, $c_flags)) continue;
        if($r = load_module_menu($key)) {
            $result[] = load_module_menu($key);
        }
    }

    return $result;
}

function & load_module_menu($flag, $use_title = FALSE) {
    global $_G;
    if(!isset($_G['modules'][$flag])) {
        return;
        show_error(lang('global_not_found_module', $flag));
    }
    //$path = (empty($_G['modules'][$flag]['directory']) ? ('modules'.DS.$flag) : $_G['modules'][$flag]['directory']);
    $path = 'modules' . DS . $flag;
    $file = $path . DS . 'admin' . DS . 'menus.inc.php';
    if(!is_file(MUDDER_CORE . $file)) {
        return;
        show_error(lang('global_file_not_exist', MUDDER_CORE . $file));
    }
    include MUDDER_CORE . $file;
    if(empty($modmenus) || !is_array($modmenus)) show_error('admincp_module_menu_empty');
    if($use_title) return $modmenus;
    $result = array('title' => $_G['modules'][$flag]['name']);
    foreach($modmenus as $key => $val) {
        if(is_string($val)) {
            $result[] = $val;
        } elseif(is_array($val)) {
            foreach($val as $_key => $_val) {
                if($_key=='title') continue;
                if(is_string($_val)) {
                    $result[] = $_val;
                }
            }
        }
    }
    return $result;
}

function load_plugin_menu() {
    global $_G;

    $result = array();
    $result[] = $_G['lng']['admincp']['admincp_menu_arrry_plugin'];

    return $result;
}

function load_help_menu() {
    global $_G;

    $result = array();
    $result[] = $_G['lng']['admincp']['admincp_menu_arrry_help'];

    return $result;
}
?>