<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model(MOD_FLAG.':category');
if(!$_POST['dosubmit']) {

	$_G['loader']->helper('form','item,review');
    $M =& $_G['loader']->model(MOD_FLAG.':model');
    if(!$models = $M->model_list()) {
        redirect('itemcp_cat_model_empty', cpurl($module,'model_add'));
    }
    $admin->tplname = cptpl('category_add', MOD_FLAG);

} else {

    $t_cat = $_POST['t_cat'];
    $pid = $_POST['pid'] = (int) $_POST['pid'];

    if(empty($t_cat['name'])) {
        redirect('itemcp_cat_empty_name');
    }
    if(empty($pid) && empty($t_cat['modelid'])) {
        redirect('itemcp_cat_unselect_model');
    }
    if(empty($pid) && empty($t_cat['review_opt_gid'])) {
        redirect('itemcp_cat_unselect_review_opt');
    }

    $t_cat['config'] = array();
    $catid = $C->add($t_cat);

    redirect('itemcp_cat_add_succeed', cpurl($module,'category_edit','',array('catid'=>$catid)));

}
?>