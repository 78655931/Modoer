<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
if(!$_G['subject_owner']) redirect('item_manage_access');

$op = _input('op');
$S =& $_G['loader']->model(MOD_FLAG.':subject');
$SS =& $_G['loader']->model(MOD_FLAG.':subjectstyle');

switch($op) {
    case 'use':
        $templateid = _input('templateid',0,MF_INT_KEY);
        $SS->use_style($_G['manage_subject']['sid'], $templateid);
        echo 'OK';
        output();
        break;
    case 'buy':
        $templateid = _input('templateid',null,MF_INT_KEY);
        $SS->buy($_G['manage_subject']['sid'], $templateid);
        echo 'OK';
        output();
        break;
    case 'buy_check':
    	$templateid = _input('templateid',null,MF_INT_KEY);
    	$tpl = $SS->check_buy($_G['manage_subject']['sid'], $templateid);
        foreach($tpl as $k=>$v) $tpl[$k] = urlencode($v);
        $str = urldecode(json_encode($tpl));
        echo $str;
        output();
    	break;
    case 'shop':
        $c_sid = (int)$_G['manage_subject']['sid'];
        $TPL =& $_G['loader']->model('template');
        $list = $TPL->read_all('item');
        $tplname = 'style_shop';
        break;
    default:
        $op='my';
        $SS->update_status($_G['manage_subject']['sid']);
        $list = $SS->my($_G['manage_subject']['sid']);
        $tplname = 'style_my';
}
?>