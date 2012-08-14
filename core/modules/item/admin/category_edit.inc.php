<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model(MOD_FLAG.':category');
$_G['loader']->helper('form', MOD_FLAG);
$_G['loader']->helper('form', 'review');

$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
empty($op) && $op = 'config';
switch($op) {
case 'config':
    if(!$_POST['dosubmit']) {
        $catid = $_GET['catid'] = (int) $_GET['catid'];
        if(empty($catid)) redirect('itemcp_cat_empty_catid');
        $t_cat = $C->read($catid);
        $t_cfg =& $t_cat['config'];
        $t_mod = $C->variable('model_' . $t_cat['modelid']);
        $t_tag = $C->variable('taggroup');
        $admin->tplname = cptpl('catedit_config', MOD_FLAG);
    } else {
        $_POST['catid'] = (int) $_POST['catid'];
        $_POST['t_cat']['config'] = $_POST['t_cfg'];
        $C->save($_POST['t_cat'], $_POST['catid']);
        redirect('global_op_succeed', cpurl($module, $act, $op, array('catid'=>$_POST['catid'])));
    }
    break;
case 'subcat':
    if(!$_POST['dosubmit']) {
        $catid = $_GET['catid'] = (int)$_GET['catid'];
        $t_cat = $C->read($catid);
        if($t_cat['level'] > 2) {
            redirect('itemcp_cat_mainclass_invalid');
        }
        $result = $C->getlist($catid);
        $admin->tplname = cptpl('catedit_subcat', MOD_FLAG);
    } else {
        $_POST['catid'] = (int) $_POST['catid'];
        empty($_POST['catid']) && redirect(sprintf(lang('global_sql_keyid_invalid'), 'catid'));
        empty($_POST['t_cat']) && readrect(lang('global_op_nothing'));
        $C->update_subcats($_POST['t_cat'], $_POST['catid']);
        redirect('global_op_succeed', cpurl($module,$act,$op,array('catid'=>$_POST['catid'])));
    }
    break;
case 'add':
    $newcat = $_POST['newcat'];
    empty($newcat['name']) && redirect('itemcp_cat_add_subcat_empty_name');
    empty($newcat['pid']) && redirect('itemcp_cat_add_subcat_empty_pid');
    $C->add($newcat, true);
    redirect('global_op_succeed', cpurl($module,$act,'subcat',array('catid'=>$newcat['pid'])));
    break;
case 'edit':
	$catid = _get('catid',null,'intval');
	if(!$detail = $C->read($catid)) redirect('itemcp_cat_empty');
	$admin->tplname = cptpl('catedit_subcat_save', MOD_FLAG);
	break;
case 'save':
	$catid = _post('catid',null,'intval');
	$post = $C->get_post($_POST);
	$C->save($post, $catid);
	redirect('global_op_succeed', cpurl($module,$act,'edit',array('catid'=>$catid)));
	break;
case 'delete':
    $_GET['catid'] = (int) $_GET['catid'];
    $pid = $C->delete($_GET['catid']);
    $url = empty($pid) ? cpurl($module,'category_list') : cpurl($module, $act, 'subcat', array('catid'=>$pid));
    redirect('global_op_succeed', $url);
    break;
case 'rebuild':
    if(isset($_POST['catids'])) {
        $C->rebuild(_post('catids'));
    } else {
        $C->rebuild(_get('catid'));
    }
    redirect('global_op_succeed', get_forward(cpurl($module,$act)));
    break;
default:
    redirect('global_op_unkown');
}

?>