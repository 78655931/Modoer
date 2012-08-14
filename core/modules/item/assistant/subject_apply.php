<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$SA =& $_G['loader']->model(MOD_FLAG.':subjectapply');
if($_POST['dosubmit']) {

    $applyid = $SA->save();
    redirect('item_apply_succeed', url('item/detail/id/' . $_POST['sid']));

} else {

    $sid = (int) $_GET['sid'];
    $detail = $SA->check_post_before($sid);

    $category = $SA->get_category($detail['catid']);
    $catcfg =& $detail['catcfg'];
    $pid = $category['catid'];
    $modelid = $category['modelid'];
    if(!$model = $SA->get_model($modelid)) redirect('item_model_empty');

}
?>