<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return 'sldomain_item_route';

function sldomain_item_route() {
    $loader =& _G('loader');
    $modcfg = $loader->variable('config','item');
    if(!$modcfg['sldomain']) return false;
    if($modcfg['sldomain'] != 1 && $modcfg['sldomain'] != 3) return false;
    $domain = strtolower(_G('web','domain'));
    if(!$i = strpos($domain,'.')) return false;
    $eq_sldomain = substr($domain,$i+1);
    if($eq_sldomain != $modcfg['base_sldomain']) return;
    $slname = substr($domain,0,$i);
    if((!$_GET['m'] || $_GET['m'] == 'item') && !$_GET['act']) {
        $_GET['m'] = 'item';
        $_GET['act'] = 'detail';
        $_GET['name'] = $slname;
    }
    return TRUE;
}
?>