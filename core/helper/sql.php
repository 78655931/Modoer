<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/

function sql_get_create_table($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
}

function sql_run_query($sql) {
	global $_G;

	$sql = str_replace("\r", "\n", str_replace(' modoer_', ' '.$_G['dns']['dbpre'], $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE ([a-z0-9_]+) .*/is", "\\1", $query);
				//$create_text .= 'create'.$name.' ... <font color="#0000EE">succeed</font><br />';
				$_G['db']->exec(sql_get_create_table($query, $_G['dns']['dbcharset']));
				//$tablenum++;
			} else {
				$_G['db']->exec($query);
			}
		}
	}
}

function sql_create_table($tablename, $sql) {
    global $_G;
    $dbcharset = $_G['dns']['dbcharset'];
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $sql = "CREATE TABLE IF NOT EXISTS " . $tablename . " ($sql) ";
    if ($_G['db']->version() > '4.1') {
        $sql .= "ENGINE=MyISAM" . ($dbcharset ? " DEFAULT CHARSET=$dbcharset" : '');
    } else {
        $sql .= "TYPE=MyISAM";
    }
    $_G['db']->exec($sql);
}

function sql_exists_table($tablename) {
    global $_G;
    $row = $_G['db']->query("SHOW TABLES FROM `" . $_G['dns']['dbname'] . "` LIKE '" . $_G['dns']['dbpre'] . $tablename . "'");
    if(!$row) return FALSE;
    $result = $row->fetch_array();
    $row->free_result();

    return !empty($result);
}

function sql_exists_field($tablename, $feild) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $row = $_G['db']->query("SHOW COLUMNS FROM $tablename LIKE '$feild'");
    if(!$row) return FALSE;
    $rt = $row->fetch_array();
    $row->free_result();

    return $rt['Field'] == $feild;
}

function sql_delete_table($tablename) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $_G['db']->exec("DROP TABLE $tablename");
}

//"table","delflag","add","delflag tinyint(1) NOT NULL DEFAULT '0' AFTER new"
function sql_alter_field($tablename, $field, $act, $sql) {
    global $_G;
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $row = $_G['db']->query("SHOW COLUMNS FROM $tablename LIKE '$field'");
    if($row) {
        $rt = $row->fetch_array();
        $row->free_result();
    } else {
        $rt = array();
    }

    $lowersql = strtolower("ALTER TABLE $tablename $act $sql");
    if ((strpos($lowersql,' add ') !== false && $rt['Field']!=$field) || 
        ((strpos($lowersql,' drop ') !== false || 
        strpos($lowersql,' change ') !== false) && $rt['Field'] == $field)) {
        $_G['db']->exec($lowersql);
    }
}

//"shops", "ADD", "ownerid", "ownerid (ownerid,status,addtime)"
//"shops", "DROP", "cate", "cate"
function sql_alter_index($tablename, $alter, $iname, $iparam) {
    global $_G;
    $alter = strtoupper($alter);
    $tablename = $_G['dns']['dbpre'] . $tablename;
    $sql = "ALTER TABLE $tablename $alter INDEX $iparam";
    $rt = FALSE;
    $query = $_G['db']->query("SHOW INDEX FROM $tablename");
    if($query) {
		while($row = $query->fetch_array()) {
			if($row['Key_name'] == $iname) {
				$rt = TRUE;
				break;
			}
		}
		$query->free_result();
    }

    if(($rt && $alter == 'DROP') || (!$rt && $alter == 'ADD')) {
        $_G['db']->exec($sql);
    }
    //echo'<p>'.$sql.'</p>';
}

function sql_alter_pk($tablename, $alter, $iparam='') {
	global $_G;
    $alter = strtoupper($alter);
    $tablename = $_G['dns']['dbpre'] . $tablename;
	$sql = '';
	if($alter == 'ADD' && $iparam)
		$sql = "ALTER TABLE $tablename ADD ($iparam)";
	elseif($alter == 'DORP')
		$sql = "ALTER TABLE $tablename DORP PRIMARY KEY";
	if(!$sql) return;
	$_G['db']->exec($sql);
}
?>