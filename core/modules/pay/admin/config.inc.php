<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$_G['loader']->helper('form', MOD_FLAG);

if($_POST['dosubmit']) {

    if($_POST['modcfg']['alipay_key'] == '*********') unset($_POST['modcfg']['alipay_key']);
    if($_POST['modcfg']['spkey'] == '*********') unset($_POST['modcfg']['spkey']);
    if($_POST['modcfg']['cb_key'] == '*********') unset($_POST['modcfg']['cb_key']);
    $_POST['modcfg']['cz_type']  = serialize($_POST['modcfg']['cz_type']);
    $C->save($_POST['modcfg'], MOD_FLAG);
    redirect('global_op_succeed', cpurl($module, 'config'));

} else {

    $modcfg = $C->read_all(MOD_FLAG);
    $modcfg['cz_type'] = $modcfg['cz_type']?unserialize($modcfg['cz_type']):array();

    if($modcfg['alipay_key']) $modcfg['alipay_key'] = '*********';
    if($modcfg['spkey']) $modcfg['spkey'] = '*********';
    if($modcfg['cb_key']) $modcfg['cb_key'] = '*********';
    
	$mcfg = $_G['loader']->variable('config','member');
	$pointgroups = unserialize($mcfg['point_group']);

    $admin->tplname = cptpl('config', MOD_FLAG);
}

/*
if($dosubmit) {
    
    if($modcfg['ratio'] <= 0) cpmsg('兑换比率必须大于0，请返回修改。');
    if(strlen($modcfg['card_prefix']) > 10) cpmsg('对不起，卡号前缀不能大于10个字符。');
    $modcfg = addslashes(serialize($modcfg));

    $db->update("UPDATE {$dbpre}modules SET config='$modcfg' WHERE flag='".MOD_FLAG."'");
    cache_module(MOD_FLAG);
    cpmsg('配置信息更新完毕!', 'admincp.php?action='.$action.'&file='.$file);

} else {

    $modcfg = $db->get_value("SELECT config FROM {$dbpre}modules WHERE flag='".MOD_FLAG."'");
    if($modcfg) {
        $modcfg = unserialize($modcfg);
        extract($modcfg, EXTR_OVERWRITE);
    }

    include cptpl('config', MOD_ROOT.'./admin/templates/');
}
*/
?>
