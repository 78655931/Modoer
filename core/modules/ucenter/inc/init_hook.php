<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

require_once MUDDER_DATA . 'config_uc.php';
$ucfg = array();
$ucfg = $_G['loader']->variable('config','ucenter');
if($ucfg['uc_enable']) {
    require_once MUDDER_ROOT . 'uc_client' . DS . 'client.php';
    //model mapping
    $_G['loader']->add_mapping(array(
            ':member'=>'ucenter:member',
            'member:user'=>'ucenter:user',
            'member:friend'=>'ucenter:friend',
            'member:message'=>'ucenter:message',
            'member:feed' => 'ucenter:feed',
        )
    );
    $_G['menu_mapping'][] = array('src'=>'member/index/ac/pm','dst'=>'ucenter/member/ac/pm');
}
unset($ucfg);
?>