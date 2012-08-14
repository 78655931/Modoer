<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');
$op = _input('op');
$ST =& $_G['loader']->model('item:subjectstyle');
switch ($op) {
	case 'manage':
		$sid = _get('sid',0,MF_INT_KEY);
		$list = $ST->my($sid);
    	$admin->tplname = cptpl('template_manage', MOD_FLAG);
		break;

	case 'manage_delete':
		$id = _input('id',0,MF_INT_KEY);
		$style = $ST->read($id);
		if(empty($style)) redirect('对不起，模版记录不存在。');
		$ST->delete($id);
		redirect('global_op_succeed_delete', cpurl($module,$act,'manage',array('sid'=>$style['sid'])));
		break;

	case 'manage_add':
		$sid = _get('sid',0,MF_INT_KEY);
		$tpllist = $_G['loader']->variable('templates');
		$admin->tplname = cptpl('template_manage_save', MOD_FLAG);
		break;

	case 'manage_edit':
		$id = _get('id',0,MF_INT_KEY);
		$style = $ST->read($id);
		if(empty($style)) redirect('对不起，模版记录不存在。');
		$sid = $style['sid'];
		$template = $_G['loader']->model('template')->read($style['templateid']);
		if(empty($template)) redirect('对不起，模版文件不存在。');
		$admin->tplname = cptpl('template_manage_save', MOD_FLAG);
		break;

	case 'manage_save':
		$isedit = _input('do')=='manage_edit';
		$endtime = strtotime($_POST['datetime']);
		if(!$endtime) redirect('对不起，您没有设置一个有效的到期时间。');
		$sid = _input('sid',null,MF_INT_KEY);
		if(!$sid) redirect(lang('global_sql_keyid_invalid','sid'));
		if($isedit) {
			$id = _input('id',null,MF_INT_KEY);
			if(!$id) redirect(lang('global_sql_keyid_invalid','id'));
		} else {
			$templateid = _input('templateid',0,MF_INT_KEY);
			$get = $ST->get_exists(array('sid'=>$sid,'templateid'=>$templateid));
			if($get['id']) {
				$id = $get['id'];
				$isedit = true;
			}
		}
		if($isedit) {
			$ST->_renew($id, $endtime, true);
		} else {
			$ST->_addnew(0,$sid,$templateid,$endtime,true);
		}
		redirect('global_op_succeed',cpurl($module,$act,'manage',array('sid'=>$sid)));

	case 'manage_refresh':
		$sid = _input('sid',null,MF_INT_KEY);
		if(!$sid) redirect(lang('global_sql_keyid_invalid','sid'));
        $ST =& $_G['loader']->model('item:subjectstyle');
        $list = $ST->my($sid,false);
        $content = '';
        if($list) while($_val=$list->fetch_array()) {
            $selected = '';
            $content .= "\t<option value=\"$_val[templateid]\"".$selected.">$_val[name]".($_val['endtime']?('('.date('Y-m-d',$_val['endtime']).')'):'')."</option>\r\n";
        }
        echo $content;
        output();
		break;

	default:
		$_GET['type'] = 'item';
		$subtitle = lang('itemcp_template_title');
		if($MOD['selltpl_pointtype']) {
		    $use_price = true;
		    $selltype_pointtype = $MOD['selltpl_pointtype'];
		    $_G['loader']->helper('form','member');
		}
		include MUDDER_ADMIN . 'template.inc.php';
		break;
}

?>