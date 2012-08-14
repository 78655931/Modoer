<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model(':member', FALSE);
class msm_member_userbase extends msm_member {
    public $uniq = '';
    public function __construct() {
        parent::__construct();
        $this->uniq = $this->_create_uniq();
    }

    private function _create_uniq() {
        $unstr = $_SERVER['HTTP_USER_AGENT'] . $this->global['ip'];
        return substr(md5($unstr),16);
    }
}
?>