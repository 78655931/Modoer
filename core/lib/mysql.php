<?php
/**
* 数据库操作
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_mysql {

    var $dns = '';
	var $query_num = 0;
	var $link = '';

    var $sqls = '';

	function ms_mysql(& $dns) {
		$this->__construct($dns);
	}
	
	function __construct(& $dns) {
        $this->dns = $dns;
        if(DEBUG) $this->sqls = array();
    }

	function connect() {
        $func = $this->dns['pconnect'] ? "mysql_pconnect" : "mysql_connect";
        if(!$this->link = @$func($this->dns['dbhost'], $this->dns['dbuser'], $this->dns['dbpw'], 1)) {
        	$this->_halt("Can not connect to MySQL server");
        }
        if($this->version() > '4.1' && $this->dns['dbcharset']) {
			mysql_query("SET NAMES '" . $this->dns['dbcharset'] . "'", $this->link);
		}
        if($this->version() > '5.0.1') {
			mysql_query("SET sql_mode=''", $this->link);
        }
		if($this->dns['dbname']) {
			if (!@mysql_select_db($this->dns['dbname'], $this->link)) $this->_halt('Cannot use database '.$this->dns['dbname']);
        }
	}

	/**
	 * 选择一个数据库
	 *
	 * @param string $dbname 数据库名
	 */
	function select_db($dbname) {
		$this->dns['dbname'] = $dbname;
		if (!@mysql_select_db($dbname, $this->link)) 
			$this->_halt('Cannot use database '.$dbname);
	}

	/**
	 * 查询数据库版本信息
	 *
	 * @return string
	 */
	function version() {
		return mysql_get_server_info();
	}

	/**
	 * Ping数据库，如果无法ping通，就重连数据库
	 *
	 * @return string
	 */
    function ping() {
        if(!mysql_ping($this->link)) {
            $this->close(); //注意：一定要先执行数据库关闭，这是关键
            $this->connect();
        }
    }

	/**
	 * 发送一条 MySQL 查询
	 *
	 * @param string $SQL SQL语法 
     * @param string $method 查询方式 [空=自动获取并缓存结果集] [unbuffer=并不获取和缓存结果的行]
	 * @return ms_mysql_result 资源标识符
	 */
	function & query($SQL, $method = '') {
        if(DEBUG) {
			$mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];
            $this->sqls[] = 'Query' . ($this->query_num+1) . ':' . $SQL;
        }
        //$this->safecheck($SQL);
        if($this->dns['ping']) $this->ping();
        if($method == 'unbuffer' && function_exists('mysql_unbuffered_query')) {
			$query = mysql_unbuffered_query($SQL, $this->link);
		} else {
			$query = mysql_query($SQL, $this->link);
        }
		if (!$query && $method != 'SILENT') {
            $this->_halt('MySQL Query Error: ' . $SQL);
        }
        $this->query_num++;
        if(DEBUG) {
            $mtime = explode(' ', microtime());
            $querytime = number_format(($mtime[1] + $mtime[0] - $starttime), 6) ;
            $this->sqls[] = 'Time:'.$querytime;
			if(strtolower(substr($SQL,0,7)) == 'select ' && $this->link) {
                if($result = mysql_query('EXPLAIN '.$SQL, $this->link)) {
                    $explain = mysql_fetch_assoc($result);
                    $table = '';
                    if($explain) {
                        $table = '<table border="1" cellspacing="1" cellpadding="1"><tr>';
                        foreach(array_keys($explain) as $key) {
                            $table .= "<td>$key</td>";
                        }
                        $table  .= '</tr><tr>';
                        foreach($explain as $key=>$val) {
                            $table .= "<td>$val</td>";
                        }
                        $table  .= '</tr></table>';
                    }
                }
			}
            $this->sqls[] = $table;
        }
        if(!is_resource($query)) return;
        if(mysql_num_rows($query)) {
            $result = new ms_mysql_result($query);
        } else {
            $result = FALSE;
        }
        return $result;
	}

	/**
	 * 执行一条 MySQL 查询
	 *
	 * @param string $SQL SQL语法 
	 * @param string $method 查询方式 [空=自动获取并缓存结果集] [unbuffer=并不获取和缓存结果的行]
	 * @return resource 资源标识符
	 */
    function exec($SQL, $method = '') {
        if(DEBUG) {
            $mtime = explode(' ', microtime());
			$starttime = $mtime[1] + $mtime[0];
            $this->sqls[] = 'Exec ' . ($this->query_num+1) . ':' . $SQL;
        }
        $this->safecheck($SQL);
        if($this->dns['ping']) $this->ping();
        if($method == 'unbuffer' && function_exists('mysql_unbuffered_query')) {
			$query = mysql_unbuffered_query($SQL, $this->link);
		} else {
			$query = mysql_query($SQL, $this->link);
        }
		if (!$query && $method != 'SILENT') {
            $this->_halt('MySQL Query Error: ' . $SQL);
        }
        $this->query_num++;
        if(DEBUG) {
            $mtime = explode(' ', microtime());
            $querytime = number_format(($mtime[1] + $mtime[0] - $starttime), 6) ;
            $this->sqls[] = 'Time:'.$querytime;
        }
        return $query;
    }

    /**
     * 返回上一次执行SQL后，被影响修改的条(行)数
     *
     * @return int
     */
	function affected_rows() {
		return mysql_affected_rows($this->link);
	}

	/**
	 * 取得上一步 INSERT 操作产生的 ID 
	 *
	 * @return int
	 */
	function insert_id() {
		return ($id = mysql_insert_id($this->link)) >= 0 ? $id : $this->result($this->query("SELECT last_insert_id()"), 0);
	}

	/**
	 * 关闭 MySQL 连
	 *
	 * @return bool
	 */
	function close() {
		return mysql_close($this->link);
	}

	/**
	 * 返回上一个 MySQL 操作产生的文本错误信息
	 *
	 * @return string
	 */
    function error() {
        return (($this->link) ? mysql_error($this->link) : mysql_error());
    }

    /**
     * 返回上一个 MySQL 操作中的错误信息的数字编码 
     *
     * @return integer
     */
    function errno() {
        return intval(($this->link) ? mysql_errno($this->link) : mysql_errno());
    }

    /**
     * 对字符串处理加入引号 
     *
     * @param string $str
     * @return string
     */
	function _escape_str($str) {
		if (function_exists('mysql_escape_string')) {
			return mysql_escape_string($str);
		} else {
			return addslashes($str);
		}
	}

    /**
     * 过滤字段名 
     *
     * @param string $field
     * @return string
     */
    function _ck_field($field) {
        if(preg_match("/[\'\\\"\<\>]+/", $field)) {
            show_error(lang('global_sql_invalid_field', $field));
        }
        return $field;
    }

    /**
     * 显示错误信息 
     *
     * @param string $msg
     */
	function _halt($msg = '') {
        $charset = $this->global['charset'];
		$message = "<html>\n<head>\n";
		$message .= "<meta content=\"text/html; charset=$charset\" http-equiv=\"Content-Type\">\n";
		$message .= "<link rel='stylesheet' type='text/css' href=".URLROOT."'/static/images/error.css'>";
		$message .= "</head>\n<body>\n";

        $msg = str_replace($this->dns['dbpre'], '[dbpre]', _T($msg));
        $msg = preg_replace("/[a-zA-Z0-9]{32}/i", '[md5str]', $msg);
        $error = str_replace($this->dns['dbpre'], '[dbpre]', $this->error());
        $error = preg_replace("/[a-zA-Z0-9]{32}/", '[md5str]', $error);

        $message .= "<div class='error'><h3>MySQL Error:</h3>";
        $message .= "<div class='error'>";
        $message .= "<pre>".htmlspecialchars($msg)."</pre>\n";
        $message .= "<b>Error description</b>: ".htmlspecialchars($error)."\n<br />";
        $message .= "<b>Error number</b>: ".$this->errno()."\n<br />";
        $message .= "<b>Date</b>: ".date("Y-m-d @ H:i")."\n<br />";
        $message .= "<b>Script</b>: http://".$_SERVER['HTTP_HOST'].getenv("REQUEST_URI")."\n";
        $message .= "</div>\n</div>";

        $trace = debug_backtrace();
        $message .= "<div class='debug'>\n<h3>Debug backtrace:</h3>";
        $message .= "<table border='0' cellspacing='0' cellpadding='0' class='trace'>";
        $message .= "<tr>\n<th width='400' align='left'>File</th>";
        $message .= "<th width=\"50\">Line</th>";
        $message .= "<th align=\"right\">Function</th>\n</tr>";
        foreach($trace as $k=>$t) {
            //if(!$k) continue;
            $message .= "<tr><td>".str_replace(MUDDER_ROOT,'',$t['file'])."</td>";
            $message .= "<td align='center'>$t[line]</td>";
            $message .= "<td align='right'>".($t['class']?("{$t['class']}->"):'')."$t[function]()</td></tr>";
        }
        $message .= "</table>\n</div>";
		$message .= "</body>\n</html>";

        log_write('sqlerror', 
            htmlspecialchars($msg) . 
            "\nDescription:" . htmlspecialchars($error) . 
            "\nNumber:" . $this->errno() .
            "\nDate:" . date("Y-m-d @ H:i:s") .
            "\nScript:http://" . $_SERVER['HTTP_HOST'] . getenv("REQUEST_URI") . 
            "\nIP:" . $this->global['ip'] .
            "\n========================================================="
        );

		echo $message;
		exit;
	}

    //SQL可以语句检测
    function safecheck($sql) {
        $result = $this->full_count_words($sql);
        if($result['select'] >=4 AND $result['from'] >=3) {
            log_write('sqlcheck', 
                htmlspecialchars($sql) . 
                "\nDate:" . date("Y-m-d @ H:i:s") .
                "\nScript:http://" . $_SERVER['HTTP_HOST'] . getenv("REQUEST_URI") . 
                "\nIP:" . $this->global['ip'] .
                "\n========================================================="
            );
        }
    }

    function full_count_words($str) { 
        $words = str_word_count($str,1); 
        $result = array(); 
        foreach ($words as $w) { 
            $lw = strtolower($w); 
            if (!(isset($result[$lw]))) { 
                $result[$lw] = 1; 
            } else { 
                $result[$lw]++; 
            }
        }
        return $result; 
    }

    /**
     * 显示SQL查询记录 
     *
     */
	function debug_print() {
		global $_G;
        if(!$this->sqls) return;
		$style = 'margin:5px auto;width:98%;line-height:18px;font-family:Courier New;text-align:left;background:#eee;border-width:1px; border-style:solid;border-color:#CCC;';
		$content ='<div style="'.$style.'">';
        $content .='<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;"><a href="javascript:;" onclick="$(\'#debug_sql_history\').toggle();">SQL History</a> ('.count($this->sqls).')</h3>';
		$content .= '<ul style="margin:0;padding:0 0 5px;list-style:none;display:none;" id="debug_sql_history">';
		foreach($this->sqls as $val) {
			$content .= '<li style="padding:1px 8px;font-size:12px;">' . $val . '</li>';
		}
		$content .= '</ul></div>';

		return $content;
	}
}

class ms_mysql_result {

    var $res = null;

	function __construct($res) {
        $this->res = $res;
    }

	function ms_mysql_result($res) {
		$this->__construct($res);
	}

    function data_seek($row_number) {
        return mysql_data_seek($this->res, row_number);
    }

    function mysql_db_name($row) {
        return mysql_db_name($this->res, $row);
    }

    function result($row) {
        return mysql_result($this->res, $row);
    }

    function fetch_array($result_type = MYSQL_ASSOC) {
        //MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH
        return mysql_fetch_array($this->res, $result_type);
    }

    //获取数据，简化函数
    function fetch($result_type = MYSQL_ASSOC) {
        //MYSQL_ASSOC，MYSQL_NUM 和 MYSQL_BOTH
        return mysql_fetch_array($this->res, $result_type);
    }

    function fetch_row() {
        return mysql_fetch_row($this->res);
    }

    function fetch_assoc() {
        return mysql_fetch_assoc($this->res);
    }

    function fetch_field($field_offset=null) {
        if(!$field_offset) {
            return mysql_fetch_field($this->res);
        } else {
            return mysql_fetch_field($this->res, field_offset);
        }
    }

    function fetch_lengths() {
        return mysql_fetch_lengths($this->res);
    }

    function fetch_object() {
        return mysql_fetch_object($this->res);
    }

    function field_len($field_offset) {
        return mysql_field_len($this->res, $field_offset);
    }

    function field_name($field_offset) {
        return mysql_field_name($this->res, $field_offset);
    }

    function field_seek($field_offset) {
        return mysql_field_seek($this->res, $field_offset);
    }

    function field_table($field_offset) {
        return mysql_field_table($this->res, $field_offset);
    }

    function field_type($field_offset) {
        return mysql_field_type($this->res, $field_offset);
    }

    function num_fields() {
        return mysql_num_fields($this->res);
    }

    function num_rows() {
        return mysql_num_rows($this->res);
    }

    function free_result() {
        return mysql_free_result($this->res);
    }

    //释放SQL资源，简化函数
    function free() {
        return mysql_free_result($this->res);
    }

}
