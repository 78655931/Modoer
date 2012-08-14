<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

class msm_datacall extends ms_model {

	//public:
	var $table = 'dbpre_datacall';
    var $key = 'callid';

    var $cachedir = '';

    var $cacheuplifetime = FALSE;

	//public:
	function __construct() {
		parent::__construct();
		$this->cachedir = str_replace(array('./','/'),array('',DS),$this->global['cfg']['datacall_dir']) . DS;
	}

    function msm_datacall() {
        $this->__construct();
    }

    function datacallname($callname) {
        is_array($callname) && $callname = $callname[1];
        if(!$callid = $this->get_callid($callname)) return '';
        return $this->datacall($callid);
    }

    function datacall($key,$is_name = FALSE) {
        is_array($key) && $key = $key[1];
        if(!$callid = $is_name ? $this->get_callid($key) : (int) $key) return '';
        if(!$datacalls = $this->loader->variable('datacall')) {
            redirect('global_datacall_empty_cache');
        }
        if(!$query = $datacalls[$callid]) return '';
        if(!check_module($query['module'])) return '';
        if(empty($query['var'])) return '';
        $fun = $query['calltype'] == 'sql' ? 'datacall_sql' : 'datacall_fun';
        if($this->in_ajax && _input('do')=='datacall') {
            global $_G,$_CFG,$_MODULES;
            if($_QUERY[$query['var']] = $this->$fun($callid)) {
                include template($query['tplname'], 'datacall');
            } else {
                include template($query['empty_tplname'], 'datacall');
            }
        } else {
            $st = "\$_G['modoer_datacall'][$callid]";
            $restr = "{eval \$_G['loader']->variable('datacall');\r\n";
            $restr .= "\$_QUERY[{$st}['var']] = \$_G['datacall']->$fun($callid);\r\n";
            $restr .= "}\r\n";
            $restr .= "{if empty(\$_QUERY[{$st}['var']])}\r\n";
            $restr .= "{template {$st}['empty_tplname'],'datacall'}\r\n";
            $restr .= "{else}\r\n";
            $restr .= "{template {$st}['tplname'],'datacall'}\r\n";
            $restr .= "{/if}\r\n";
            return $restr;
        }
    }

    function js_file($key, $is_name = false) {
        if($is_name) {
            $callid = $this->get_callid($key);
        } else {
            $callid = (int) $key;
            $datacalls = $this->loader->variable('datacall');
            if(!$datacalls[$callid]) $callid = 0;
        }
        if(!$callid) {
            echo "document.write('".lang('global_datacall_callid_empty')."');";
            exit;
        }

        $file = 'datacall_' . $callid;
        $ext = empty($this->global['cfg']['tplext']) ? '.htm' : $this->global['cfg']['tplext'];
        $tplfile = 'data' . DS . 'templates' . DS . $file . $ext;
        if(!is_file(MUDDER_ROOT . $tplfile)) {
            $template = $this->datacall($callid);
            if(!file_put_contents(MUDDER_ROOT . $tplfile, $template)) {
                echo "document.write('".lang('global_file_not_exist',$tplfile)."')";
                exit;
            }
        }
        $cachefile = 'data' . DS . 'templates' . DS . 'js_' . $file . '.tpl.php';
        if(!file_exists(MUDDER_ROOT . $cachefile) || (@filemtime(MUDDER_ROOT . $tplfile) > @filemtime(MUDDER_ROOT . $cachefile))) {
            $this->loader->helper('template');
            parse_template($tplfile, $cachefile);
        }
        return $cachefile;
    }

    //运行函数调用
    function datacall_fun($callid) {
        $datacalls = $this->variable('datacall');
        $params = $this->parse_global($datacalls[$callid]['expression']);

        if($datacalls[$callid]['cachetime'] > 0) {
            $identifier = create_identifier($datacalls[$callid]['module']) . '_' . create_identifier($params);
            if($params['rand']) {
                $randcount = $params['rows'];
                $params['rows'] = round($randcount * 2);
            }
            if($result = $this->get_datacache($callid, $identifier, $datacalls[$callid]['cachetime'])) {
                //随机数据
                if($params['rand'] && (count($result) > $randcount)) {
                    $result = $this->_get_rand_data($result, $randcount);
                }
                return $result;
            }
        }

        $classname = 'query_' . $datacalls[$callid]['module'];
        $fun = $datacalls[$callid]['fun'];
        $this->loader->helper('query', $datacalls[$callid]['module']);
        $result = call_user_func(array($classname, $fun), $params);
        
        if($datacalls[$callid]['cachetime'] > 0 && !empty($result)) {
            $this->write_datacache($callid, $identifier, $result);
            //随机数据
            if($params['rand'] && (count($result) > $randcount)) {
                $result = $this->_get_rand_data($result, $randcount);
            }
        }
        return $result;
    }

    //运行SQL调用
    function datacall_sql($callid) {
        $datacalls = $this->variable('datacall');
        $params = $this->parse_global($datacalls[$callid]['expression']);

        if(isset($params['cachetime'])) unset($params['cachetime']);
        if($datacalls[$callid]['cachetime'] > 0) {
            $identifier = create_identifier($datacalls[$callid]['module']) . '_' . create_identifier($params);
            if($result = $this->get_datacache($callid, $identifier, $datacalls[$callid]['cachetime'])) return $result;
        }

        $result = array();
        $params['from'] = str_replace("{dbpre}", $this->global['dns']['dbpre'], $params['from']);
        $params['where'] = !trim($params['where']) ? '1=1' : $params['where'];
        $params['orderby'] = !trim($params['orderby']) ? '' : " ORDER BY ".$params['orderby'];
        $sql = "SELECT " . $params['select'] . " FROM " . $params['from'] . " WHERE " . $params['where'] . " " . $params['other'] . $params['orderby'] . " LIMIT " . $params['limit'];

        if(!$r = $this->db->query($sql)) return $result;
        while($row = $r->fetch_array()) {
            $result[] = $row;
        }
        $r->free_result();

        if($datacalls[$callid]['cachetime'] > 0 && !empty($result)) {
            $this->write_datacache($callid, $identifier, $result);
        }
        return $result;
    }

    //直接在模板里调用
    function datacall_get($fun, $params, $module = '') {
        $params = query_default($params);

        if(isset($params['cachetime'])) {
            $cachetime = (int) $params['cachetime'];
            unset($params['cachetime']);
            if($params['rand']) {
                $randcount = $params['rows'];
                $params['rows'] = round($randcount * 2);
            }
		}
		$classname = (!$module || $module == 'modoer') ? 'query' : ('query_' . $module);
        if($cachetime > 0) {
            $identifier = create_identifier($module) . '_' . create_identifier($params);
            if($result = $this->get_datacache(0, $identifier, $cachetime)) {
                //随机数据
                if($params['rand'] && (count($result) > $randcount)) {
                    $result = $this->_get_rand_data($result, $randcount);
                }
                return $result;
            }
        }

        $this->loader->helper('query', $module);
        $result = call_user_func(array($classname, $fun), $params);

        if($cachetime > 0 && !empty($result)) {
            $this->write_datacache(0, $identifier, $result);
            //随机数据
            if($params['rand'] && (count($result) > $randcount)) {
                $result = $this->_get_rand_data($result, $randcount);
            }
            //$this->cacheuplifetime[$identifier] = $this->global['timestamp'];
        }
        return $result;
    }

    function parse_global(& $params) {
        if(!is_array($params)||count($params)==0) return $params;
        $gbls = array();
        foreach($params as $key => $val) {
            $match = array();
            if(preg_match_all('/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]+)\}/i', $val, $match)) {
                foreach($match[1] as $_val) {
                    if($_val == 'dbpre') {
                        $params[$key] = str_replace('{'.$_val.'}', _G('dns','dbpre'), $params[$key]);
                        continue;
                    }
                    if(!isset($gbls[$_val])) {
                        if(defined('IN_AJAX')) {
                            $$_val = _input($_val,null);
                        } else {
                            global $$_val;
                        }
                        $gbls[] = $_val;
                        $params[$key] = str_replace('{'.$_val.'}', $$_val, $params[$key]);
                    }
                }
            }
        }
        unset($match,$gbls,$val,$key);
        return $params;
    }

    function cache_path($cachename) {
        $dirname = substr($cachename,-2);
        //$i = strpos($cachename, '_');
        //$dirname = substr($cachename, $i + 1, 1);
        $cachedir = $this->cachedir . $dirname;
        if(!is_dir(MUDDER_ROOT . $cachedir)) {
            if(!mkdir(MUDDER_ROOT . $cachedir, 0777)) {
                exit("Current picture path '$cachedir' have no access!");
            }
        }
        return MUDDER_ROOT . $cachedir . DS . 'cache_' . $cachename . '.php';
    }

    function read_all($module=null, $start=0,$offset=0) {
        $result = false;

        $this->db->clear();
        if($module) $this->db->where('module', $module);
        $this->db->from($this->table);

        $result = array($this->db->count());
        if(!$result[0]) return $result;

        $this->db->sql_roll_back('from,where');
        $this->db->limit($start, $offset);
        $this->db->order_by('callid', 'DESC');
        $row = $this->db->get();
        while($value=$row->fetch_array()) {
            $value['expression'] = unserialize($value['expression']);
            $split = '';
            if(is_array($value['expression'])) foreach($value['expression'] as $key => $val) {
                if(empty($val)) continue;
                if(in_array($key, array('row', 'order'))) continue;
                $value['expression']['params'] .= $split . $key . '='.$val;
                $split = "\r\n";
            }
            $result[1][] = $value;
        }
        return $result;
    }

    /*
	function & find($where, $orderby, $start=0, $offset=0) {
		$result = array(0,array());
		$total = $this->db->get_value("SELECT COUNT(*) FROM $this->table WHERE $where");
		if($total) {
			$result[0] = $total;
			$limit =  !$start && !$offset ? "" : "LIMIT $start, $offset";
			$result[1] = $this->db->get_all("SELECT * FROM $this->table WHERE $where ORDER BY callid DESC $limit");
		}
		return $result;
	}
    */

	function read($callid) {
        if(!$result = parent::read($callid)) {
            redirect('global_op_nothing');
        }

        $result['expression'] = unserialize($result['expression']);
        if($result['calltype'] == 'fun') {
            $result['expression']['params'] = '';
            if(is_array($result['expression'])) foreach($result['expression'] as $key => $val) {
                if(empty($val)) continue;
                if(in_array($key, array('row','order'))) continue;
                $datacall['expression']['params'] .= $split.$key.'='.$val;
                $split = "\r\n";
            }
        }
		return $result;
	}

    function save($post, $callid=null) {
        $post['name'] = trim($post['name']);
		empty($post['name']) and redirect('admincp_datacall_empty_name');

        $this->db->where('name', $post['name']);
        if($callid) $this->db->where_not_equal('callid', $callid);
        $this->db->from($this->table);
        if($this->db->count()) redirect('admincp_datacall_exists_name');
        strpos($post['name'], ' ') and redirect('admincp_datacall_space_name');

		$callid = $this->_create_datacall($post,$callid);
        $this->write_cache();
		return $callid;
    }
	
	function refresh($callids) {
		$this->delete_datacall_cache($callids);
	}
	
	function delete($callids) {
        if(empty($callids)) return;
        parent::delete($callids, 0);
		$this->delete_datacall_cache($callids);
		$this->write_cache();
	}

    function import() {
        $this->loader->lib('upload_file',null,0);
        $UP = new ms_upload_file('importfile','txt');
        $UP->_check();
        if(!$contents = file_get_contents($UP->_file['tmp_name'])) {
            redirect('admincp_datacall_export_invalid_file');
        }
        $count = $this->_import_sql($contents);
        @unlink($UP->delete_tmpfile());
    }
	
	function export() {
		$content = "MODOER DATACALL EXPORT FILE\n";
		$content .= "VERSION:{$this->global['modoer']['version']}\n";
		$content .= base64_encode($this->_export_sql());
		ob_end_clean();
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Encoding: none');
		header('Content-Length: '.strlen($content));
        $filename = 'modoer_datacall_'. date('Y-m-d', $this->global[timestamp]) .'.txt';
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 
			'application/octet-stream'));
		echo $content;
		exit();
	}

    // 计划删除数据缓存
    function plan_delete() {
        $lockname = 'plan_datacall_delete.php';
        $lockfile = MUDDER_CACHE . $lockname;

        $interval = $this->global['cfg']['datacall_clearinterval'] * 3600;
        if($interval <= 0) return;
        $plan_delete_time = $this->global['cfg']['datacall_cleartime'] * 3600;

        if(check_cache($lockfile, $interval)) {
            return;
        }

        $this->_delete_datacall_cache('_delete_callback_plan', $plan_delete_time);

        //$data = "\$_datacall_delete_timestamp = $this->global[timestamp];";
        @file_put_contents($lockfile,'<?=exit();?>');
    }

    // 删除指定callid的数据缓存
    function delete_datacall_cache($callids) {
		if(!is_array($callids) && !is_numeric($callids)) return;
		if(is_numeric($callids)) $callids = array($callids);

        $this->_delete_datacall_cache('_delete_callback_callid', $callids);
    }

    //删除全部数据缓存
    function delete_datacall_cache_all() {
        $this->_delete_datacall_cache('_delete_callback_callid', false);
    }

	//删除某个模块的数据缓存
	function delete_datacall_cache_module($module) {
		$this->_delete_datacall_cache('_delete_callback_module', $module);
	}

	function write_cache() {
        $result = array();

        $this->db->from($this->table);
        $this->db->where('closed', 0);
        $this->db->order_by('callid');

        if($row = $this->db->get()) {
            while($value=$row->fetch_array()) {
	            $value['expression'] = empty($value['expression'])?'':unserialize($value['expression']);
                $value['expression']['cachetime'] = $value['cachetime'];
	            $result[$value['callid']] = $value;
            }
            $row->free_result();
        }

		write_cache('datacall', arrayeval($result), $this->model_flag);
	}

    //从名称获取id
    function get_callid($callname) {
        $callid = 0;
        $callname = !is_array($callname) ? $callname : $callname[1];
        if($datacalls = $this->loader->variable('datacall')) {
            foreach($datacalls as $val) {
                if($val['name'] == $callname) {
                    $callid = $val['callid'];
                    break;
                }
            }
        }
        return $callid;
    }

    // 获取调用缓存
    function get_datacache($callid, $identifier, $cachetime) {
        $callid = (int) $callid;
        $cachename = sprintf("%d_%s", $callid, $identifier);
        $cachefile = $this->cache_path($cachename);
        if(check_cache($cachefile, $cachetime)) {
            return read_cache($cachefile);
        }
        return FALSE;
    }

    //写入更新生命首期的问题
    function cachelife() {
        if(!$this->cacheuplifetime) return;
        $filepath = MUDDER_ROOT . 'data' . DS . 'cachefiles' . DS . 'datalifetime.php';
        if(!$list=read_cache($filepath)) {
            $list = array();
        }
        foreach ($this->cacheuplifetime as $key => $value) {
            $list[$key] = $value;
        }
        write_cache('datalifetime',arrayeval($list),'modoer');
    }

    // 写入调用缓存
    function write_datacache($callid, $identifier, &$data) {
        if(empty($data)) return; //无数据不保存
        $callid = (int) $callid;
        $cachename = sprintf("%d_%s", $callid, $identifier);
        $cachefile = $this->cache_path($cachename);

        $name = str_replace('cache_', '', basename($cachefile,'.php'));
        $dir = str_replace(MUDDER_ROOT, '', dirname($cachefile));

        write_cache($name, arrayeval($data), 'cache', 'return', $dir);
    }

	//private:
	function _create_datacall($post, $callid = 0) {
	    if($post['calltype'] == 'sql') {
	        $post['fun'] = 'sql';
	        foreach($post['expression'] as $key => $val) {
	            $post['expression'][$key] = str_replace("\'","'",$val);
	        }
	    } elseif($post['calltype'] == 'fun') {
	        $params = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $post['expression']['params']));
	        foreach($params as $val) {
	            list($key, $value) = explode('=', $val);
	            $post['expression'][$key] = str_replace("\'","'",$value);
	        }
	        unset($params, $post['expression']['params']);
	        //$post['hash'] = cacheIdentifier($post['expression']);
	    } else {
	        redirect('admincp_datacall_empty_idtype');
	    }
	    $post['expression'] = serialize($post['expression']);
	    //$post['hash'] = cacheIdentifier($post['expression']);

        $this->db->from($this->table);
        $this->db->set($post);
	    if($callid) {
            $this->db->where('callid', $callid);
            $this->db->update();
	        $this->delete_datacall_cache($callid);
	    } else {
	        $this->db->insert();
	        $callid = $this->db->insert_id();
	    }
	    return $callid;
	}

	function _import_sql($content) {
		global $_G;
		if(!$content) return ;
		$content = str_replace("\r\n", "\r", $content);
		$list = explode("\n", $content);
		if($list[0] != 'MODOER DATACALL EXPORT FILE') {
			redirect('admincp_datacall_export_invalid_file');
		} elseif($list[1] != "VERSION:".$_G['modoer']['version']) {
			redirect('admincp_datacall_export_version_differ');
		}elseif(empty($list[2])) {
			$this->redirect('admincp_datacall_export_empty_data');
		}
		$list = explode("\n", base64_decode($list[2]));
		if(empty($list) || count($list)<=1) redirect('admincp_datacall_export_file_incomplete');

        $table = str_replace('dbpre_', $this->db->dns['dbpre'], $this->table);

		$sql = "INSERT INTO $table ($list[0]) VALUES (%s)";
		unset($list[0]);

		$this->db->query("TRUNCATE TABLE $table");
		foreach ($list as $val) {
			if(trim($val)=='') continue;
			$this->db->query(sprintf($sql, $val));
		}

        $this->delete_datacall_cache_all();
		$this->write_cache();

		return count($list);
	}

	function _export_sql() {
		$content = '';

		$row = $this->db->query("SHOW COLUMNS FROM ". str_replace('dbpre_', $this->db->dns['dbpre'],$this->table));
		$split = '';
        while($val=$row->fetch_array()) {
            $content .= $split . $val['Field'];
			$split = ',';
        }
		$content .= "\n";

        $this->db->from($this->table);
        $this->db->order_by("callid");
        $row = $this->db->get();
		while ($value = $row->fetch_array()) {
			$split = '';
			foreach ($value as $_key => $_val) {
				$content .= $split . "'".addslashes($_val)."'";
				$split = ',';
			}
			$content .= "\n";
		}
		$content = str_replace("\\\"","\"",$content);

		return $content;
	}

    // 物理删除数据调用缓存文件
    function _delete_datacall_cache($callback, $param) {
        $directory = MUDDER_ROOT . $this->cachedir;
        $dirs = glob($directory  . "*");
        $timestamp = $this->global['timestamp'];
        $deletefiles = array();
        foreach($dirs as $key) {
            $subdir = str_replace('\\\\', DS, $key) . DS;
            if(is_dir($subdir) === TRUE) {
                if(!$files = glob($subdir . "*.php")) continue;
                foreach($files as $file) {
                    if($this->$callback($file, $param)) $deletefiles[] = $file;
                }
            }
        }
        if($deletefiles) foreach($deletefiles as $file) {
            @unlink($file);
        }
    }

    function _delete_callback_plan($file, $time) {
        return preg_match("/^[0-9a-z_]+\.php$/i", basename($file)) && $this->global['timestamp'] - @filemtime($file) > $time;
    }

    function _delete_callback_callid($file, $callids) {
        if(preg_match("/^cache_([0-9]+)_[a-z0-9_]+\.php$/i",basename($file), $match)) {
            return empty($callids) || in_array($match[1], $callids);
        }
    }

	function _delete_callback_module($file, $module) {
		$hash = create_identifier($module);
        if(preg_match("/^cache_[0-9]+_([a-z0-9]+)_[a-z0-9]+\.php$/i",basename($file), $match)) {
            return $match[1] == $hash;
        }
    }
	
    //获取随机数组
    function _get_rand_data($data, $num=1) {
        $randres = array();
        $rands = array_rand($data, $num);
        if(is_array($rands)) {
            foreach($rands as $k) {
                $randres[] = $data[$k];
            }
        } else {
            $randres[] = $result[$rands];
        }
        return $randres;
    }
}
?>