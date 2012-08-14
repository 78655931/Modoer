<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('ucenter:member', FALSE);
class msm_ucenter_userbase extends msm_ucenter_member {
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