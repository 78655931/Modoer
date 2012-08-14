<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
class msm_tools extends ms_base implements Iterator {

    private $tools = array();
    private $valid = FALSE;

    public function __construct() {
        parent::__construct();
        $this->load_tools();
    }

    public function factory($tool) {
        if(!preg_match("/^[a-z0-9_\-]+$/i", $tool)) return false;
        $filename = MUDDER_ROOT . 'core' . DS . 'tools' . DS . $tool . '.php';
        if(!file_exists($filename)) return false;
        $classname = 'msm_tool_' . $tool;
        if(!class_exists($classname)) include_once $filename;
        if(get_parent_class($classname) == 'msm_tool') {
            return new $classname();
        }
        return false;
    }

    public function next() {
        $this->valid = (next($this->tools) === FALSE) ? FALSE : TRUE;
    }

    public function rewind() {
        $this->valid = (reset($this->tools) === FALSE) ? FALSE : TRUE;
    }

    public function valid() {
        return $this->valid;
    }

    public function current() {
        return current($this->tools);
    }

    public function key() {
        return key($this->tools);
    }

    private function load_tools() {
        $this->tools = array();
        $directory = MUDDER_ROOT . 'core' . DS . 'tools' . DS;
        $iterator = new DirectoryIterator($directory);
        foreach ($iterator as $fileinfo) {
            if ($fileinfo->isFile() && pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION) == 'php') {
                $apiname = $fileinfo->getBasename('.php');
                $classname = 'msm_tool_' . $apiname;
                if(!class_exists($classname)) include_once $fileinfo->getRealPath();
                if(get_parent_class($classname)=='msm_tool') {
                    $this->tools[$apiname] = new $classname();
                }
            }
        }
    }

}