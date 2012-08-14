<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
if(!$_G['subject_owner']) redirect('item_manage_access');

$op = _input('op');
$S =& $_G['loader']->model(MOD_FLAG.':subject');
$SS =& $_G['loader']->model('item:subjectsetting');

switch($op) {
    case 'change_banner':
        if($_POST['dosubmit']) {
            $SS->change_banner();
            redirect('global_op_succeed',url("item/member/ac/g_subject"));
        } else {
            $sid = _input('sid', null, MF_INT_KEY);
            if(!$SS->check_access($sid)) redirect('global_op_access');
            $banner = $SS->read($sid, 'banner');
            $banner_width = $SS->get_dsetting('banner_width',900);
            $banner_height = $SS->get_dsetting('banner_height',150);
            $tplname = 'setting_banner_upload';
        }
        break;
    case 'bcastr_list':
        if($_POST['dosubmit']) {
            $SS->bcastr_list_post();
            redirect('global_op_succeed',url("item/member/ac/$ac/op/bcastr_list/sid/$_POST[sid]"));
        } else {
            $sid = _input('sid', null, MF_INT_KEY);
            if(!$SS->check_access($sid)) redirect('global_op_access');
            $bcastrs = $SS->read($sid, 'bcastr');
            if($bcastrs) $bcastrs = unserialize($bcastrs);
            if(!$bcastrs) location(url("item/member/ac/$ac/op/bcastr_save/sid/$sid"));
            $tplname = 'setting_bcastr_list';
        }
        break;
    case 'bcastr_delete':
        $bcastrs = $SS->bcastr_delete();
        echo 'OK';
        output();
    case 'bcastr_save':
        if($_POST['dosubmit']) {
            $SS->bcastr_save_post();
            location(url("item/member/ac/$ac/op/bcastr_list/sid/$_POST[sid]"));
        } else {
            $sid = _input('sid', null, MF_INT_KEY);
            if(!$SS->check_access($sid)) redirect('global_op_access');
            $bcastr_width = $SS->get_dsetting('bcastr_width',740);
            $bcastr_height = $SS->get_dsetting('bcastr_height',200);
            $flag = _get('flag','',MF_TEXT);
            if($bcastrs = $SS->read($sid, 'bcastr')) {
                $bcastrs = @unserialize($bcastrs);
                if($bcastrs && count($bcastrs)>=5) redirect('橱窗图片放满了。');
            }
            if($flag) if(!$detail = $bcastrs[$flag]) redirect('您操作的橱窗图片不存在。');
        }
        $tplname = 'setting_bcastr_save';
        break;
    case 'save_title':
        $SS->save_title();
        echo 'OK';
        output();
        break;
    default:
        redirect('global_op_unkown');
}
?>