<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class ms_loader {

    var $modules = null;
    var $load_files = null;

    var $model_mapping = array(); //ģ��ӳ���

    function __construct() {
        global $_G;
        $this->modules =& $_G['modules'];
    }

    function ms_loader() {
        $this->__construct();
    }

    //���ӳ��
    function add_mapping($src,$dest=null) {
        if(is_array($src)) {
            foreach($src as $k => $v) {
                $this->add_mapping($k,$v);
            }
        } else {
            if(!$dest) return;
            $this->model_mapping[$src] = $dest;
        }
    }

    function & lib($classname, $module=NULL, $instance=TRUE, $param=NULL) {
        global $_G;
        static $instances = array();

        $path = '';
        if($module && isset($this->modules[$module])) {
            $edpty_dir = true;
            $path = ($edpty_dir ? ('modules'. DS . $module) : ($this->modules[$module]['directory'])) . DS . 'lib' . DS;
        } elseif($module) {
            show_error(lang('global_not_found_module', $module));
        } else {
            $path = 'lib' . DS;
        }

        $filename = MUDDER_CORE . $path . $classname . '.php';
        $fullclassname = 'ms_' . $classname;
        if(!class_exists($fullclassname)) {
            if(!is_file($filename)) {
                show_error(sprintf(lang('global_file_not_exist'), $path . $classname . '.php'));
            }
            if(DEBUG) $this->load_files[] = $filename;
            include $filename;
        }

        if($instance) {
            if(!isset($instances[$fullclassname])) {
                if($param) {
                    $instances[$fullclassname] = new $fullclassname($param);
                } else {
                    $instances[$fullclassname] = new $fullclassname();
                }
            }
        }

        return $instances[$fullclassname];
    }

    //����ģ���࣬classname��3�ַ�ʽ��module����ʾmodoer��ܵ�module�࣬��item:subject����ʾitemģ���subject�࣬��:article����ʾarticleģ���article��
    function & model($flag, $instance=TRUE, $param=NULL, $use_mapping = TRUE) {
        global $_G;
        static $instances = array();

        //����ģ��ʱ���������ģ����ӳ������ȡӳ����������滻��
        if($use_mapping && isset($this->model_mapping[$flag])) {
            $flag = $this->model_mapping[$flag];
        }

        list($module,$classpre) = $this->pares_flag($flag);
        $class = 'msm_' . ($module && $classpre != $module ? ($module . '_') : '') . $classpre;
        $path = ($module ? ('modules'.DS.$module) : '') . DS . 'model' . DS;
        $filename = $path . $classpre . '_class.php';

        if(!class_exists($class)) {
            if(!is_file(MUDDER_CORE . $filename)) {
                show_error(lang('global_file_not_exist', $filename));
            }
            if(DEBUG) $this->load_files[] = $filename;
            include_once MUDDER_CORE . $filename;
        }

        if($instance) {
            if(!isset($instances[$class])) {
                if($param) {
                    $instances[$class] = new $class($param);
                } else {
                    $instances[$class] = new $class();
                }
            }
        }

        return $instances[$class];
    }

    function helper($filename, $module=NULL) {
        global $_G;

        if(strpos($filename,',')) {
            $filenames = explode(',', $filename);
            if($filenames) {
                foreach($filenames as $val) {
                    $this->helper($val,$module);
                }
                return;
            }
        } elseif(strpos($module,',')) {
            $modules = explode(',', $module);
            if($modules) {
                foreach($modules as $val) {
                    $this->helper($filename,$val);
                }
                return;
            }
        }

        static $instances = array();

        $path = '';
        if($module && isset($this->modules[$module])) {
            $edpty_dir = TRUE;
            $path = ($edpty_dir ? ('modules'. DS . $module) : ($this->modules[$module]['directory'])) . DS . 'helper' . DS;
        } elseif($module) {
            show_error(lang('global_not_found_module', $module));
        } else {
            $path = 'helper' . DS;
        }

        $file = MUDDER_CORE . $path . $filename . '.php';
        if(!in_array($file, $instances)) {
            if(!is_file($file)) {
                show_error(lang('global_file_not_exist', $path . $filename . '.php'));
            }
            $instances[] = $file;

            if(DEBUG) $this->load_files[] = $file;
            include $file;
        }
    }

    function & cookie() {
        $prelen = strlen(_G('cookiepre'));
        $result = array();
        foreach($_COOKIE as $key => $value) {
            if(substr($key, 0, $prelen) == _G('cookiepre')) {
                $var = substr($key, $prelen);
                $result[$var] = $value;
            }
        }
        return $result;
    }

    function & cache($filename, $module=NULL, $show_error=TRUE) {
        $result = '';
        $filename = ($module ? $module : 'modoer') . '_' . $filename . '.php';
        if(!is_file(MUDDER_CACHE . $filename)) {
            $show_error && show_error(lang('global_cachefile_not_exist', str_replace(MUDDER_ROOT,'',MUDDER_CACHE) . $filename));
        } else {
			$result = @include MUDDER_CACHE . $filename;
		}
        if(DEBUG) $this->load_files[] = MUDDER_CACHE . $filename;
        return $result;
    }

    function & variable($keyname, $module=NULL, $show_error=TRUE) {
        global $_G;
        $module == NULL && $module = 'modoer';
        $key = ($module ? $module . '_' : '') . $keyname;
        if(!isset($_G[$key])) {
            if(!$_G[$key] = $this->cache($keyname, $module, $show_error)) {
                unset($_G[$key]);
				return FALSE;
			}
        }
        return $_G[$key];
    }

    /**
     * ��ʾ�ļ������¼ 
     *
     */
	function debug_print() {
		global $_G;
        if(!$this->load_files) return;
		$style = 'margin:5px auto;width:98%;line-height:18px;font-family:Courier New;text-align:left;background:#eee;border-width:1px; border-style:solid;border-color:#CCC;';
		$content ='<div style="'.$style.'">';
		$content .='<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;"><a href="javascript:;" onclick="$(\'#debug_load_files\').toggle();">Load Files</a> ('.count($this->load_files).')</h3>';
		$content .= '<ul style="margin:0;padding:0 0 5px;list-style:none;display:none;" id="debug_load_files">';
		foreach($this->load_files as $val) {
			$content .= '<li style="padding:1px 8px;font-size:12px;">' . str_replace(MUDDER_ROOT, '', $val) . '</li>';
		}
		$content .= '</ul></div>';

		return $content;
	}

    //���������ļ����ࣩ���ʽ����������(ģ�������ļ���ǰ׺)array(module,classpre)
    function pares_flag($str) {
        $arrs = explode(':',$str);
        if(count($arrs)==1) {
            $module = null;
            $classpre = $arrs[0];
        } elseif($arrs[0]=='') {
            $module = $arrs[1];
            $classpre = $arrs[1];
        } else {
            $module = $arrs[0];
            $classpre = $arrs[1];
        }
        if($module && !check_module($module)) show_error(lang('global_not_found_module', $arrs[0]));
        return array($module,$classpre);
    }
}

