<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

class msac_config extends ms_admin_controller {

    var $model_flag = 'ucenter';
    var $uc = null;

    function __construct() {
        $this->model =& $this->loader->model('config');
    }

    function msac_config() {
        $this->__construct();
    }

    function on_default() {
        list($ucdbname, $uctablepre) = explode('.', str_replace('`', '', UC_DBTABLEPRE));

        $this->assign('ucdbname', $ucdbname);
        $this->assign('uctablepre', $uctablepre);
        $this->assign('ucdbpw', '********');
        $this->assign('disabled', $uc->is_write() ? '' : 'disabled ');
        $this->assign('modcfg', $this->model->read_all($this->model_flag));
        $this->display('config');
    }

    function on_submit() {
        $uc->config(_post('modcfg'), _post('uc'));
        $this->model->save(_post('modcfg'), $this->model_flag);
        redirect('global_op_succeed', cpurl($module, 'config'));
    }
}