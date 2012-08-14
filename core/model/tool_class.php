<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
interface ITOOL {
    public function run();
}

class msm_tool extends ms_base implements ITOOL {

    protected $name = '';
    protected $descrption = '';
    protected $completed = false;
    protected $lost = false;
    protected $message = '';
    protected $params = array();

    public function get_name() {
        return $this->name;
    }

    public function get_descrption() {
        return $this->descrption;
    }

    public function get_param() {
        $n = str_replace('msm_tool_','',get_class($this));
        $params = array_merge(array('tool'=>$n),$this->params);
        return $params;
    }

    public function get_message() {
        return $this->message;
    }

    public function completed() {
        return $this->completed;
    }

    public function lost() {
        return $this->lost;
    }

    public function create_form() {
        return null;
    }

    public function run() {}
}