<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_tasktype extends ms_model {

	var $table = 'dbpre_tasktype';
    var $key = 'ttid';

    var $modcfg = null;
    var $cache_name = 'tasktype';
    var $tasks = array();

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
    }

    function install() {
        if(!$flag = _input('flag', null, MF_TEXT)) redirect('member_tasktype_flag_empty');
        if($this->_check_exists($flag)) redirect('member_tasktype_flag_exitst');
        $tsk =& $this->instantiate($flag);
        method_exists($tsk, 'install') && $tsk->install();
        $this->db->from($this->table);
        $this->db->set($this->_get_tasktype_info($tsk));
        $this->db->insert();
        $this->write_cache();
        return $this->db->insert_id();
    }

    function unstall() {
        if(!$ttid = _input('ttid', null, MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid','ttid'));
        if(!$detail = parent::read($ttid)) redirect('member_tasktype_empty');
        $flag = $detail['flag'];
        $T =& $this->loader->model('member:task');
        $T->delete_taskflag($flag);
        $tsk =& $this->instantiate($flag);
        method_exists($tsk, 'uninstall') && $tsk->uninstall();
        parent::delete($ttid);
    }

    function load() {
        $this->db->from($this->table);
        $this->db->order_by('flag');
        return $this->db->get();
    }

    function load_local() {
        $local_tasks = array();
        $tasktypes = $this->_load_list();
        foreach($this->global['modules'] as $module) {
            $dir = MUDDER_MODULE . $module['flag'] . DS . 'inc' . DS;
            foreach (glob($dir."*_task.php") as $filename) {
                if(preg_match('%[\\\\|/]{1}([0-9a-z_\-]+)_task\.php$%i', $filename, $match)) {
                    $keyname = $module['flag'].':'.$match[1];
                    include $filename;
                    $classname = $this->_get_classname($keyname);
                    $local_tasks[$keyname] = new $classname();
                    if($local_tasks[$keyname]->install = isset($tasktypes[$keyname])) {
                        $local_tasks[$keyname]->info = $tasktypes[$keyname];
                        $local_tasks[$keyname]->ttid = $tasktypes[$keyname]['ttid'];
                    }
                }
            }
        }
        $local_tasks && krsort($local_tasks);
        return $local_tasks;
    }

    function create_form($flag, $detail=null) {
        $content = '';
        $tsk = $this->instantiate($flag);
        if(method_exists($tsk, 'form')) {
            $detail['config'] && $detail['config'] = unserialize($detail['config']);
            $elements = $tsk->form($detail['config']);
        }
        if($elements) foreach($elements as $item) {
            $content .= "<tr><td class=\"altbg1\"><strong>{$item[title]}:</strong>$item[des]</td>";
            $content .= "<td>$item[content]</td>";
        }
        return $content;
    }

    function & instantiate($flag) {
        list($m, $n) = explode(':', $flag);
        $file = MUDDER_MODULE . $m . DS . 'inc' . DS . $n . '_task.php';
        if(!is_file($file)) redirect(lang('global_file_not_exist', str_replace(MUDDER_MODULE,'',$file)));
        $classname = $this->_get_classname($flag);
        if(!class_exists($classname,FALSE)) include $file;
        return new $classname();
    }

    function write_cache() {
        write_cache($this->cache_name, arrayeval($this->_load_list()), $this->model_flag);
    }

    function _load_list() {
        $result = array();
        if($q = $this->load()) {
            while($v=$q->fetch_array()) {
                $result[$v['flag']] = $v;
            }
            $q->free_result();
        }
        return $result;
    }

    function _check_exists($flag) {
        $this->db->from($this->table);
        $this->db->where('flag', $flag);
        return $this->db->count() > 0;
    }

    function _get_classname($flag) {
        list($m, $n) = explode(':',$flag);
        return 'task_' . $m . '_' . $n;
    }

    function _get_tasktype_info(&$tsk) {
        return array(
            'flag' => $tsk->flag,
            'title' => $tsk->title,
            'copyright' => $tsk->copyright,
            'version' => $tsk->version,
        );
    }

}