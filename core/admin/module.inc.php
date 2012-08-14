<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
/*ENCODE*/

(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$MM =& $_G['loader']->model('module');
$op = _input('op');

switch($op) {
    case 'cache':
        $MM->write_cache();
        $C =& $_G['loader']->model('config');
        if(is_array($_POST['moduleflags'])) {
            foreach($_POST['moduleflags'] as $val) {
                $C->write_cache($val);
            }
            redirect('global_op_succeed', cpurl($module, $act, 'manage'));
        } else {
            redirect('admincp_module_unselect');
        }
        break;
    case 'list_update':
        $MM->list_update($_POST['modules']);
        redirect('global_op_succeed', cpurl($module, $act, 'manage'));
        break;
    case 'info':
        if(!$_POST['dosubmit']) {
            $moduleid = (int)$_GET['moduleid'];
            $moduleinfo = $MM->read($moduleid);
            unset($moduleinfo['config']);
            $admin->tplname = cptpl('module_info');
        } else {
            $moduleinfo = $_POST['moduleinfo'];
            $moduleinfo['moduleid'] = (int) $moduleinfo['moduleid'];

            if(!$moduleinfo['name']) redirect('admincp_module_empty_name');
            if(!$moduleinfo['moduleid']) redirect('admincp_module_emoty_id');

            $MM->update(array('name'=>$moduleinfo['name']), $moduleinfo['moduleid']);
            redirect('global_op_succeed', cpurl($module, $act));
        }
        break;
    case 'enable':
    case 'disable':
        $moduleid = (int) $_GET['moduleid'];
        $disable = $op=='enable' ? 0 : 1;
        $MM->update(array('disable'=>$disable), $moduleid);
        redirect('global_op_succeed', cpurl($module, $act));
        break;
    case 'versioncheck':
        $moduleid = (int) $_GET['moduleid'];
        if(!$newversion = $MM->versioncheck($moduleid)) {
            redirect('admincp_module_versioncheck_err');
        }
        $admin->tplname = cptpl('module_checkup');
        break;
    case 'versionupdate':
        $moduleid = _input('moduleid',null,MF_INT_KEY);
        module_version_update($moduleid);
        break;
    case 'install':
        if(!$admin->is_founder) redirect('global_op_access');
        $step = empty($_POST['step']) ? 1 : $_POST['step'];
        switch($step) {
            case 1:
                $directory = _input('directory');
                $newmodule = $MM->install_check($directory);
                $readonly = array('flag', 'reliant', 'author', 'siteurl', 'email', 'copyright');
                break;
            case 2:
                $newmodule = $_POST['newmodule'];
                empty($newmodule['flag']) and redirect('admincp_module_install_empty_dir');
                $dir = MUDDER_MODULE . $newmodule['flag'];
                if(is_file($checkfile = $dir . DS . 'install' . DS . 'install_check.php')) {
                    include $checkfile;
                }
                unset($checkfile);

                if(is_file($sqlfile = $dir . DS . 'install' . DS . 'module_install.sql')) {
                    $fp = fopen($sqlfile, 'rb');
                    $modulesql = fread($fp, 2048000);
                    fclose($fp);
                }

                if($modulesql) {
                    $_G['loader']->helper('sql');
                    sql_run_query($modulesql);
                }

                if(is_file($installfile = $dir . DS . 'install' . DS . 'install.php')) {
                    include $installfile;
                }
                unset($installfile);

                $cfgfile = $dir . DS . 'install' . DS . 'config.php';
                $MM->install($cfgfile, $newmodule);

                redirect(sprintf(lang('admincp_module_install_succeed'), $newmodule['name']), cpurl($module, $act, 'manage'));
        }
        $admin->tplname = cptpl('module_install');
        break;
    case 'unstall':
        $moduleid = (int) $_GET['moduleid'];
        $moduleinfo = $MM->read($moduleid);

        if($moduleinfo['iscore']) {
            redirect('admincp_module_uninstall_core');
        }
        
        if($MM->reliant_check($moduleinfo['flag'],1)) {
            redirect('admincp_module_uninstall_exist_reliant');
        }

        $dir = MUDDER_MODULE . $moduleinfo['flag'];

        if(is_file($sqlfile = $dir . DS . 'install' . DS . 'module_uninstall.sql')) {
            $fp = fopen($sqlfile, 'rb');
            $modulesql = fread($fp, 2048000);
            fclose($fp);
        }
        $_G['loader']->helper('sql');
        $modulesql && sql_run_query($modulesql);

        if(is_file($uninstallfile = $dir . DS . 'install' . DS . 'uninstall.php')) {
            @include($uninstallfile);
        }
        unset($uninstallfile);
        $MM->uninstall($moduleinfo);

        redirect(sprintf(lang('admincp_module_uninstall_succeed'), $moduleinfo['name']), cpurl($module, $act, 'manage'));
        break;
    default:
        $op = 'manage';
        $list = $MM->read_all();
        $local_modues = load_local_modules();
        $admin->tplname = cptpl('module');
}

function module_version_update($moduleid, $setp=null) {
    global $MM;
    $module = $MM->read($moduleid);
    if(!$module) redirect(lang('global_not_found_module', $moduleid), cpurl('modoer','module','manage'));
    $flag = $module['flag'];
    $info = MUDDER_MODULE . $flag . DS . 'install' . DS . 'info.php';
    if(!is_file($info)) redirect('admincp_module_update_info_empty', cpurl('modoer','module','manage'));
    $newmodule = array();
    include $info;
    $new_version = $newmodule['version'];
    $old_version = $module['version'];
    if($old_version >= $new_version) redirect(lang('admincp_module_update_isnewversion',$module['name']), cpurl('modoer','module','manage'));
    $upfile = MUDDER_MODULE . $flag . DS . 'install' . DS . 'update.php';
    if(!is_file($upfile)) redirect(lang('admincp_module_update_file_empty', $newmodule['name']), cpurl('modoer','module','manage'));
    include $upfile;
    $UP = new model_update($moduleid, $old_version);
    if(!$UP->updating()->completed()) {
        $url = cpurl('modoer','module','versionupdate', $UP->params);
        redirect(lang('admincp_module_update_staring',array($UP->progress*100,"$UP->step - $UP->start - $UP->index")), $url);
    } else {
        $MM->db->from('dbpre_modules');
        $MM->db->where('flag',$flag);
        foreach(array('reliant','introduce','version','releasetime','checkurl') as $key) {
            if(isset($newmodule[$key])) $MM->db->set($key,$newmodule[$key]);
        }
        $MM->db->update();
        redirect('admincp_module_update_completed', cpurl('modoer','module','manage'));
    }
}

function load_local_modules() {
    $dirs = array();
    if (is_dir(MUDDER_MODULE)) {
        if ($dh = opendir(MUDDER_MODULE)) {
            while (($file = readdir($dh)) !== false) {
                if($file=='.'||$file=='..'||$file=='modoer'||$file=='system') continue;
                if(filetype(MUDDER_MODULE . $file) == 'dir') $dirs[] = $file;
            }
            closedir($dh);
        }
    }
    if(!$dirs) return;
    $modules = array();
    foreach($dirs as $dn) {
        $ifile = MUDDER_MODULE . $dn . DS . 'install' . DS . 'info.php';
        if(!is_file($ifile)) continue;
        $newmodule = array();
        include $ifile;
        if(!$newmodule) continue;
        foreach(array('flag','name','version','support_mc') as $key) {
            if(!$newmodule[$key]) continue;
        }
        $newmodule['directory'] = $dn;
        $modules[$newmodule[flag]] = $newmodule;
    }
    return $modules;
}
?>