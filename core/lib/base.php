<?php
/**
* Modoer��ܻ���
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_base {

    var $errmsg = '';
    var $timestamp = 0;
    var $loader = null;
    var $global = null;
    var $in_admin = false;
    var $in_ajax = false;
    var $in_js = false;

    function __construct() {
        global $_G;
        $this->global =& $_G; //Ӧ��ȫ�ֱ���
        $this->timestamp = $_G['timestamp']; //��ǰʱ��
        $this->in_admin = defined('IN_ADMIN');
        $this->in_ajax = isset($_G['in_ajax']) && $_G['in_ajax'];
        $this->in_js = isset($_G['in_js']) && $_G['in_js'];
        $this->loader =& $_G['loader']; //loader������
    }

    function ms_base() {
        $this->__construct();
    }

}

