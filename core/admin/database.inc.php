<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op','backup');

switch($op) {
    case 'backup':
        $dosubmit = _input('dosubmit');
        if($dosubmit) {
            $backup = isset($_POST['backuplimit']) ? $_POST : $_GET;
            database_backup($backup);
        } else {
            $backfilename = date('Y-m-d', $_G['timestamp']) . '_' . random(10);
            $admin->tplname = cptpl('database_backup');
        }
        break;
    case 'reset':
        if(check_submit('dosubmit')) {
            if (!_post('backfiles') || !is_array(_post('backfiles'))) {
                redirect('global_op_unselect');
            }
            $backupdir = MUDDER_DATA .'backupdata' . DS; 
            foreach($_POST['backfiles'] as $val) @unlink($backupdir . $val);
            redirect('global_op_succeed', cpurl($module, $act, 'reset'));
        } else {
            switch($_GET['doreset']) {
            case 'confirm':
                if(!$_GET['filename']) redirect('global_op_unselect');
                if(database_sqlfile_check(_get('filename'))) {
                    location(cpurl($module, $act, 'reset', array(
                        'filename'=>_get('filename'),
                        'doreset'=>'yes',
                    )));
                }
                break;
            case 'yes':
                if(!_get('filename')) redirect('global_op_unselect');
                database_reset(_get('filename'));
                break;
            default:
                $list = database_loadsqlfile();
                $admin->tplname = cptpl('database_reset');
            }
        }
        break;
    case 'info':
        $query = $_G['db']->query("SHOW TABLE STATUS");
        $mudder_rows_total = $plugin_rows_total = $other_rows_total = $mudder_Index_length = $other_Index_length = $mudder_Data_free = $other_Data_free = $mudder_Data_length = $other_Data_length = 0;
        while($info = $query->fetch_array()) {
            $info['Index_length_unit'] += size_unit(@intval($info['Index_length']));
            $info['Data_free_unit'] += size_unit(@intval($info['Data_free']));
            $info['Data_length_unit'] += size_unit(@intval($info['Data_length']));
            if(substr($info['Name'],0,strlen($_G['dns']['dbpre'])) == $_G['dns']['dbpre']) {
                $mudder_table_info[] = $info;
                $mudder_rows_total += $info['Rows'];
                $mudder_Index_length += $info['Index_length'];
                $mudder_Data_free += $info['Data_free'];
                $mudder_Data_length += $info['Data_length'];
            } else {
                $other_table_info[] = $info;
                $other_rows_total += $info['Rows'];
                $other_Index_length += $info['Index_length'];
                $other_Data_free += $info['Data_free'];
                $other_Data_length += $info['Data_length'];
            }
        }
        $query->free_result();
        $admin->tplname = cptpl('database_info');
        break;
    case 'maintenance':
    if($_POST['dosubmit']) {
        database_maintenance();
        redirect('global_op_succeed',cpurl($module,$act,'info'));
    } else {
        $admin->tplname = cptpl('database_maintenance');
    }
    break;
}

function database_backup(& $backup) {
    global $_G;

    $volume = intval($_GET['volume']) + 1;
    $sqlfilename = $backupdir . $backup['filename'] . '_' . $volume . '.sql';

    if(!$sqlfilename || preg_match("/(\.)(exe|jsp|asp|asa|htr|stm|shtml|php3|aspx|cgi|fcgi|pl|php|bat)(\.|$)/i", $sqlfilename)) {
        redirect('admincp_db_bkup_invalid_name', cpurl($_GET['module'], $_GET['act'], 'backup'));
    }

    $idstring = '# Identify: '.base64_encode("$_G[timestamp],{$_G['modoer']['version']},$volume")."\n";

    $backup['sqlcompat'] = in_array($backup['sqlcompat'], array('MYSQL40', 'MYSQL41')) ? $backup['sqlcompat'] : '';
    $dumpcharset = $_G['dns']['dbcharset'];
    $setnames = intval($backup['addsetnames']) || ($_G['db']->version() > '4.1' && (!$backup['sqlcompat'] || $backup['sqlcompat'] == 'MYSQL41')) ? "SET character_set_connection=".$dumpcharset.", character_set_results=".$dumpcharset.", character_set_client=binary;\n\n" : '';

    if($_G['db']->version() > '4.1') {
        $_G['db']->query("SET character_set_connection=$dumpcharset, character_set_results=$dumpcharset, character_set_client=binary;");
        if($backup['sqlcompat'] == 'MYSQL40') {
            $_G['db']->query("SET SQL_MODE='MYSQL40'");
        }
    }

    $sqldump = '';
    $tableid = is_numeric($_GET['tableid']) ? $_GET['tableid'] - 1 : 0;
    $startfrom = intval($_GET['startfrom']);

    $tables = array();
    $query = $_G['db']->query("SHOW TABLE STATUS");
    $end_arrs = array(
        $_G['dns']['dbpre'] . 'admin', 
        $_G['dns']['dbpre'] . 'adminsessions',
    );
    while ($myval = $query->fetch_array()) {
        if(in_array($myval['Name'], $end_arrs)) continue;
        if($backup['backuplimit'] == 'all') {
            $tables[] = $myval['Name'];
        } elseif(substr($myval['Name'], 0, strlen($_G['dns']['dbpre'])) == $_G['dns']['dbpre']) {
            $tables[] = $myval['Name'];
        }
    }
    foreach ($end_arrs as $value) {
        $tables[] = $value;
    }
    $query->free_result();

    for($i = $tableid; $i < count($tables) && strlen($sqldump) < $backup['sizelimit'] * 1000; $i++) {
        $sqldump .= database_umptable($tables[$i], $startfrom, strlen($sqldump));
        $startfrom = 0;
    }
    $tableid = $i;

    $params = array();
    foreach($backup as $key => $val) {
        $params[$key] = $val;
    }
    $params['tableid'] = $tableid;
    $params['volume'] = $volume;
    $params['startfrom'] = $_G['startrow'];
    $params['dosubmit'] = 'yes';

    if(trim($sqldump)) {
        $sqldump = "$idstring".
            "# <?exit();?>\n".
            "# Modoer bakfile Multi-Volume Data Dump Vol.$volume\n".
            "# Version: {$_G['modoer']['version']}\n".
            "# Time: ".date('Y-m-d H:i',$_G['timestamp'])."\n".
            "# Website: http://www.modoer.com\n".
            "# --------------------------------------------------------\n\n\n".$setnames.$sqldump;
        $fp = fopen(MUDDER_DATA . 'backupdata' . DS . $sqlfilename, 'wb');
        flock($fp, 2);
        if(!fwrite($fp, $sqldump)) {
            fclose($fp);
            redirect('admincp_db_bkup_invalid_access', cpurl($_GET['module'], $_GET['act'], 'backup'));
        } else {
            if($i >= count($tables) && $_G['startrow'] == 0) {
                redirect('global_op_succeed', cpurl($_GET['module'], $_GET['act'], 'reset'));
            } else {
                redirect(lang('admincp_db_bkup_succeed_auto_next', $volume), cpurl($_GET['module'], $_GET['act'], $_GET['op'], $params));
            }
        }
    } else {
        redirect('global_op_succeed', cpurl($_GET['module'], $_GET['act'], 'reset'));
    }
}

function database_umptable($table, $startfrom = 0, $currsize = 0) {
	global $_G, $backup;

	$db =& $_G['db'];
	$dumpcharset = $_G['dns']['dbcharset'];

	$offset = 300;
	$tabledump = '';

	if(!$startfrom) {
		$tabledump = "DROP TABLE IF EXISTS $table;\n";
		$createtable = $db->query("SHOW CREATE TABLE $table");
		$create = $createtable->fetch_row();
		$tabledump .= $create[1];

		if($sqlcompat == 'MYSQL41' && $db->version() < '4.1') {
			$tabledump = preg_replace("/TYPE\=(.+)/", "ENGINE=\\1 DEFAULT CHARSET=".$dumpcharset, $tabledump);
		}
		if($db->version() > '4.1' && $dumpcharset) {
			$tabledump = preg_replace("/(DEFAULT)*\s*CHARSET=.+/", "DEFAULT CHARSET=".$dumpcharset, $tabledump);
		}

		$query = $db->query("SHOW TABLE STATUS LIKE '$table'");
		$tablestatus = $query->fetch_array();
		$tabledump .= ($tablestatus['Auto_increment'] ? " AUTO_INCREMENT=$tablestatus[Auto_increment]" : '').";\n\n";
		if($backup['sqlcompat'] == 'MYSQL40' && $db->version() >= '4.1') {
			if($tablestatus['Auto_increment'] <> '') {
				$temppos = strpos($tabledump, ',');
				$tabledump = substr($tabledump, 0, $temppos).' auto_increment'.substr($tabledump, $temppos);
			}
		}
	}

	$tabledumped = 0;
	$numrows = $offset;
	if($backup['extendins'] == '0') {
		while($currsize + strlen($tabledump) < $backup['sizelimit'] * 1000 && $numrows == $offset) {
			$tabledumped = 1;
			if($rows = $db->query("SELECT * FROM $table LIMIT $startfrom, $offset")) {
                $numfields = $rows->num_fields();
                $numrows = $rows->num_rows();
                while($row = $rows->fetch_row()) {
                    $comma = '';
                    $tabledump .= "INSERT INTO $table VALUES (";
                    for($i = 0; $i < $numfields; $i++) {
                        $tabledump .= $comma.'\''.mysql_escape_string($row[$i]).'\'';
                        $comma = ',';
                    }
                    $tabledump .= ");\n";
                }
                $startfrom += $offset;
            } else {
                $numrows = 0;
            }
		}
	} else {
		while($currsize + strlen($tabledump) < $backup['sizelimit'] * 1000 && $numrows == $offset) {
			$tabledumped = 1;
			if($rows = $db->query("SELECT * FROM $table LIMIT $startfrom, $offset")) {
                $numfields = $rows->num_fields();
                if($numrows = $rows->num_rows()) {
                    $tabledump .= "INSERT INTO $table VALUES";
                    $commas = '';
                    while($row = $rows->fetch_row()) {
                        $comma = '';
                        $tabledump .= $commas." (";
                        for($i = 0; $i < $numfields; $i++) {
                            $tabledump .= $comma.'\''.mysql_escape_string($row[$i]).'\'';
                            $comma = ',';
                        }
                        $tabledump .= ')';
                        $commas = ',';
                    }
                    $tabledump .= ";\n";
                }
                $startfrom += $offset;
            } else {
                $numrows = 0;
            }
		}
	}

	$_G['startrow'] = $startfrom;
	$tabledump .= "\n";
	return $tabledump;
}

function database_loadsqlfile() {
    global $_G;
    $backupdir = MUDDER_DATA . 'backupdata';
    if(is_dir($backupdir)) {
        $dirs = dir($backupdir);
        $dbfiles = array();
        $today = @date('Y-m-d', _G('timestamp'));
        while ($bkfile = $dirs->read()) {
            $filepath = $backupdir . DS . $bkfile;
            $pathinfo = pathinfo($bkfile);
            if(is_file($filepath) && $pathinfo['extension'] == 'sql') {
                $fp = fopen($filepath, 'rb');
                $identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 200))));
                fclose($fp);
                $moday = date('Y-m-d', filemtime($filepath)+$_G['timezone']*3600);
                $mtime = date('Y-m-d H:i', filemtime($filepath)+$_G['timezone']*3600);
                $dbfile = array(
                    'filesize' => size_unit(filesize($filepath)),
                    'mtime' => ($moday == $today) ? '<font color="#FF0000">'.$mtime.'</font>' : $mtime,
                    'bktime' => $identify[0]&&is_numeric($identify[0]) ? date('Y-m-d H:i',$identify[0]) : 'N/A',
                    'version' => $identify[1] ? $identify[1] : 'N/A',
                    'volume' => $identify[2] ? $identify[2] : 'N/A',
                    'filepath' => urlencode($bkfile),
                    'filename' => htmlspecialchars($bkfile),
                );
                $file_i++;
                $dbfiles[] = $dbfile;
            }
        }
        unset($dbfile);
        $dirs->close();
        return $dbfiles;
    } else {
        return false;
    }
}

function database_sqlfile_check($file) {
    global $_G;
    $backupdir = MUDDER_DATA . 'backupdata' . DS;
    $pathinfo = pathinfo($backupdir . $file);
    if(is_file($backupdir . $file) && strtolower($pathinfo['extension']) == 'sql') {
        $fp = fopen($backupdir . $file, 'rb');
        $identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", fgets($fp, 200))));
        fclose($fp);
        if (count($identify) != 3) {
            redirect('admincp_db_reset_eq_ver');
        }
        if ($identify[2] != 1) {
            redirect('admincp_db_reset_not_friest_vol');
        }
        if ($identify[1] != $_G['modoer']['version']) {
            redirect('admincp_db_reset_eq_ver2');
        }
        return TRUE;
    } else {
        redirect('admincp_db_reset_invalid_file');
    }
}

function database_reset($file) {
    global $_G;
    $backupdir = MUDDER_DATA . 'backupdata' . DS;
    $bkfile = $backupdir . $file;
    $path_parts = pathinfo($bkfile);
    if (strtolower($path_parts['extension']) != 'sql') {
        redirect('admincp_db_invalid_sql');
    }

    $db =& $_G['db'];

    if(is_file($bkfile) && $fp = fopen($bkfile, 'rb')) {
        $sqldump = fgets($fp, 256);
        $identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", $sqldump)));
        $sqldump .= fread($fp, filesize($bkfile));
        fclose($fp);
    } else {
        if($_GET['autoimport']) {
            tool_update_setting();
            redirect('admincp_db_reset_vol_succeed', cpurl($_GET['module'],$_GET['act'],'reset'));
        } else {
            redirect('admincp_db_reset_vol_lost', cpurl($_GET['module'],$_GET['act'],'reset'));
        }
    }
    if($identify[0] && $identify[1] == $_G['modoer']['version'] && $identify[2]) {
        $sqlquery = database_splitsql($sqldump);
        unset($sqldump);
        foreach($sqlquery as $sql) {
            if(trim($sql) != '') {
                $db->query($sql, 'SILENT');
            }
        }

        $file_next = basename($bkfile);
        $file_next = preg_replace("/_(".$identify[2].")(\..+)$/", "_".($identify[2]+1)."\\2", $file_next);

        if($identify[2] == 1) {
            redirect('admincp_db_reset_auto_next', cpurl($_GET['module'],$_GET['act'],'reset', array(
                    'filename' => $file_next,
                    'doreset'=>'yes',
                    'autoimport'=>'yes',
            )));
        } elseif($_GET['autoimport']) {
            redirect(sprintf(lang('admincp_db_reset_auto_next_format'), $identify[2]), cpurl($_GET['module'],$_GET['act'],'reset',array(
                'filename'=>$file_next,
                'doreset'=>'yes',
                'autoimport'=>'yes'
            )));
        } else {
            tool_update_setting();
            redirect('global_op_succeed', cpurl($_GET['module'],$_GET['act'],'reset'));
        }
    } else {
        redirect('admincp_db_invalid_sql');
    }
}

function database_splitsql($sql) {
	$sql = str_replace("\r", "\n", $sql);
	$ret = array();
	$num = 0;
	$queriesarray = explode(";\n", trim($sql));
	unset($sql);
	foreach($queriesarray as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == "#" ? NULL : $query;
		}
		$num++;
	}
	return $ret;
}

function database_maintenance() {
    global $_G;

    $maintenance =& $_POST['maintenance'];
    if(!$maintenance || !is_array($maintenance)) {
        redirect('global_op_unselect');
    }

    $goodresults = $tables = array();
    $query = $_G['db']->query("SHOW TABLE STATUS");
    while($v = $query->fetch_array()) {
        if(substr($v['Name'], 0, strlen($_G['dns']['dbpre'])) == $_G['dns']['dbpre']) {
            $tables[] = $v['Name'];
        }
    }

    $maintenance_arr = array('check', 'repair', 'analyze', 'optimize');
    foreach($maintenance as $val) {
        if(in_array($val,$maintenance_arr)) {
            foreach ($tables as $table) {
                $result = $_G['db']->query($val.' TABLE '.$table);
            }
        }
    }
}

function tool_update_setting() {
    global $_G;
    foreach($_G['modules'] as $key => $val) {
        $file = MUDDER_CORE . $_G['modules'][$key]['directory'] . DS . 'inc' . DS . 'cache.php';
        if(is_file($file)) require $file;
    }
}
?>