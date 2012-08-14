<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/

class msm_sms_collection extends ms_base implements Iterator {

    private $modcfg = array();
    private $apis = array();
    private $valid = FALSE;

    public function __construct() {
        parent::__construct();
        $this->modcfg = $this->loader->variable('config','sms');
        $this->loader->model(':sms', false);
        $this->load_apis();
    }

    private function load_apis() {
        $directory = MUDDER_MODULE . 'sms' . DS . 'api' . DS;
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && pathinfo($fileinfo->getFilename(),PATHINFO_EXTENSION) == 'php') {
                $apiname = $fileinfo->getBasename('.php');
                $classname = 'msm_sms_' . $apiname;
                if(!class_exists($classname)) include_once $fileinfo->getRealPath();
                if(get_parent_class($classname)=='msm_sms') {
                    $this->apis[$apiname] = new $classname($this->modcfg);
                }
            }
        }
    }

    public function next() {
        $this->valid = (next($this->apis) === FALSE) ? FALSE : TRUE;
    }

    public function rewind() {
        $this->valid = (reset($this->apis) === FALSE) ? FALSE : TRUE;
    }

    public function valid() {
        return $this->valid;
    }

    public function current() {
        return current($this->apis);
    }

    public function key() {
        return key($this->apis);
    }
}
?>