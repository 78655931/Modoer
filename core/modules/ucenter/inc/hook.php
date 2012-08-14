<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

require_once MUDDER_DATA . 'config_uc.php';
class hook_ucenter extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function init() {
        $ucfg = $this->loader->variable('config','ucenter');
        if($ucfg['uc_enable']) {
            require_once MUDDER_ROOT . 'uc_client' . DS . 'client.php';
            //model mapping
            $this->loader->add_mapping(array(
                    ':member'=>'ucenter:member',
                    'member:user'=>'ucenter:user',
                    'member:friend'=>'ucenter:friend',
                    'member:message'=>'ucenter:message',
                    'member:feed' => 'ucenter:feed',
                )
            );
            $this->global['menu_mapping'][] = array('src'=>'member/index/ac/pm','dst'=>'ucenter/member/ac/pm');
        }
    }

}
?>