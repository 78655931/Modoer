<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$SS = $_G['loader']->model('item:subjectsetting');
$sid = _input('sid', null, MF_INT_KEY);
$setting = $SS->read($sid);

$edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);

switch ($op) {
	case 'banner':
		$banner = $setting['banner'];
        $banner_width = $SS->get_dsetting('banner_width',900);
        $banner_height = $SS->get_dsetting('banner_height',150);
		$admin->tplname = cptpl('setting_banner', MOD_FLAG);
		break;
	case 'update_banner':
		$SS->change_banner();
        redirect('global_op_succeed',cpurl($module,$act,'banner',array('sid'=>$sid)));
		break;
	case 'delete_banner':
		$SS->delete_banner();
		redirect('global_op_succeed_delete',cpurl($module,$act,'banner',array('sid'=>$sid)));
		break;
	case 'bcastr':
		$bcastrs = $SS->read($sid, 'bcastr');
        if($bcastrs) $bcastrs = unserialize($bcastrs);
        $admin->tplname = cptpl('setting_bcastr', MOD_FLAG);
		break;
	case 'update_bcastr':
		$SS->bcastr_list_post();
		redirect('global_op_succeed',cpurl($module,$act,'bcastr',array('sid'=>$sid)));
		break;
	case 'delete_bcastr':
		$SS->bcastr_delete();
        redirect('global_op_succeed_delete',cpurl($module,$act,'bcastr',array('sid'=>$sid)));
		break;
	case 'edit_bcastr':
	case 'add_bcastr':
        $bcastr_width = $SS->get_dsetting('bcastr_width',740);
        $bcastr_height = $SS->get_dsetting('bcastr_height',200);
        if($op == 'edit_bcastr') {
            if($bcastrs = $SS->read($sid, 'bcastr')) {
                $bcastrs = @unserialize($bcastrs);
                if($bcastrs && count($bcastrs)>=5) redirect('橱窗图片放满了。');
            }
        	$flag = _get('flag','',MF_TEXT);
        	if(!$detail = $bcastrs[$flag]) redirect('您操作的橱窗图片不存在。');
    	}
        $admin->tplname = cptpl('setting_bcastr_save', MOD_FLAG);
		break;
	case 'save_bcastr':
		$SS->bcastr_save_post();
		redirect('global_op_succeed',cpurl($module,$act,'bcastr',array('sid'=>$sid)));
		break;
	default:
		redirect('global_op_unknow');
		break;
}
?>