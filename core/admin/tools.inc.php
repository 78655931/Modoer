<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
switch($op) {
case 'phpinfo':
    if(!function_exists('phpinfo')) {
        redirect('admincp_tool_phpinfo_not_exists');
    }
    $in_ajax = 1;
    phpinfo();
    break;
case 'cache':
    if($_POST['dosubmit']) {
        if(!$_POST['cache']) redirect('global_op_unselect');
        foreach ($_POST['cache'] as $key => $value) {
            $fun = 'tool_update_' . $value;
            $fun();
        }
        redirect('global_op_succeed', cpurl($module, $act, $op));
    } else {
        $admin->tplname = cptpl('tool_cache');
    }
    break;
case 'create_form':
    $tools = $_G['loader']->model('tools');
    $tool = $tools->factory(_input('tool', null, MF_TEXT));
    if(empty($tool)) redirect('admincp_tool_object_empty');
    $elements = $tool->create_form();
    if(!$elements) {
        echo 'RUN';
    } else {
        $content = '';
        foreach($elements as $item) {
            $content .= "<tr><td><h3>{$item[title]}:</h3><div>$item[content]<span class='font_2'>$item[des]</span></div></td></tr>";
        }
        echo $content;
    }
    exit;
    break;
case 'run':
    $tools = $_G['loader']->model('tools');
    $tool = $tools->factory(_input('tool', null, MF_TEXT));
    if(empty($tool)) redirect('admincp_tool_object_empty');
    $tool->run();
    if($tool->completed()) {
        redirect(lang('global_op_succeed') . $tool->get_message(), cpurl($module, $act));
    } elseif($tool->lost()) {
        redirect(lang('global_op_lost') . $tool->get_message(), cpurl($module, $act));
    } else {
        redirect($tool->get_message(), cpurl($module,$act,$op,$tool->get_param(_get('tool', null))));
    }
    break;
default:
    $tools = $_G['loader']->model('tools');
    $admin->tplname = cptpl('tool_list');
    break;
}

function tool_update_setting() {
    $db =& _G('db');
    $db->from('dbpre_modules');
    $db->where('disable', 0);
    $row = $db->get();
    //clear_site_setting_cache();
    include MUDDER_MODULE . 'modoer' . DS . 'inc' . DS . 'cache.php';
    while ($value = $row->fetch_array()) {
        $file = MUDDER_MODULE . $value['flag'] . DS . 'inc' . DS . 'cache.php';
        if(is_file($file)) {
            include $file;
        }
    }
}

function tool_update_template() {
    $files = array();
    $cachedir = MUDDER_DATA . 'templates';
    if ($handle = @opendir($cachedir)) {
        while (false !== ($file = @readdir($handle))) {
            if (preg_match('/^.+\.tpl\.php$/i', $file)) {
                $files[] = $file;
            }
        }
        closedir($handle);
    }
    if($files) foreach($files as $filename) {
        @unlink($cachedir . DS . $filename);
    }
}

function tool_update_datacall() {
    $loader =& _G('loader');
    $D =& $loader->model('datacall');
    $D->delete_datacall_cache_all();
}
?>