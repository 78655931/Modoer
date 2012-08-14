<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];
$tpltype = $_GET['type'] ? $_GET['type'] : 'main';
$subtitle = $subtitle ? $subtitle : lang('admincp_template_title');

$T =& $_G['loader']->model('template');
switch($op) {
case 'manage':
    $templates = $_G['loader']->variable('templates');
    $type = $tpltype == 'datacall' ? 'main' : $tpltype;
    foreach($templates[$type] as $k => $v) {
        if($v['templateid'] == $_GET['templateid']) {
            $directory = $v['directory'];
            break;
        }
    }
    $dir = $tpltype == 'datacall' ? 'main' : $tpltype;
    $dir = 'templates' . DS . $dir . DS . $directory . ($tpltype == 'datacall' ? (DS . 'datacall') : '');
    $files = get_template_files(MUDDER_ROOT . $dir);
    $template_des = read_cache(MUDDER_ROOT . $dir . DS . 'template.php');
    $admin->tplname = cptpl('template_manage');
    break;
case 'add':
    if(!$filedir = $_GET['filedir']) redirect(lang('global_dir_empty'));
    if(!is_dir(MUDDER_ROOT . $filedir)) redirect(lang('global_file_not_exist', $filedir));
    if(isset($_GET['filename'])) {
        if(!is_file(MUDDER_ROOT . $_GET['filename'])) redirect(lang('global_file_not_exist', $_GET['filename']));
        $contents = file_get_contents(MUDDER_ROOT . $_GET['filename']);
        $filename = basename($_GET['filename'],'.'.pathinfo($_GET['filename'], PATHINFO_EXTENSION)).'_copy.'.pathinfo($_GET['filename'], PATHINFO_EXTENSION);
    } else {
        $contents = '';
        $filename = 'new_'.$_G['timestamp'].$_G['cfg']['tplext'];
    }
    $filedir .= DS;
    $admin->tplname = cptpl('template_save');
    break;
case 'edit':
    $filename = $_GET['filename'];
    if(!is_file(MUDDER_ROOT . $filename)) redirect(lang('global_file_not_exist', $filename));
    $contents = file_get_contents(MUDDER_ROOT . $filename);
    $admin->tplname = cptpl('template_save');
    break;
case 'post':
    if(!$_G['modify_template']) redirect('admincp_template_modify_message');
    $T->post_file_content();
    redirect(lang('global_op_succeed'), get_forward('home',1));
    break;
case 'delete':
    $T->delete_files($_POST['files']);
    redirect(lang('global_op_succeed'), get_forward('home'));
    break;
case 'update':
    $T->manage($_POST['root_dir'], $_POST['fielnames']);
    redirect(lang('global_op_succeed'), get_forward('home'));
    break;
default:
    if($_POST['dosubmit']) {
        if($_POST['newtemplate']['name']) {
            $T->save($_POST['newtemplate']);
        }
        if($_POST['templateids']) {
            $T->delete($_POST['templateids']);
            foreach($_POST['templateids'] as $templateid) {
                unset($_POST['templates'][$templateid]);
            }
        }
        if(!empty($_POST['templates'])) $T->update($_POST['templates'], $tpltype);
        redirect('global_op_succeed', cpurl($module,$act,'',array('type'=>$tpltype)));
    } else {
        $list = $T->read_all($tpltype);
        $admin->tplname = cptpl('template');
    }
    break;
}

function get_template_files($dir, $ext = array('css','htm')) {
    if (is_dir($dir)) {
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if(in_array(pathinfo($file,PATHINFO_EXTENSION),$ext)) {
                    $files[] = $dir . DS . $file;
                }
            }
            closedir($dh);
        }
    }
    return $files;
}
?>