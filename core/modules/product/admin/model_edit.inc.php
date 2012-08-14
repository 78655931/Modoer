<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model(MOD_FLAG.':model');

$modelid = $_POST['dosubmit'] ? $_POST['modelid'] : $_GET['modelid'];
if(!$modelid = (int) $modelid) {
    redirect(sprintf(lang('global_sql_keyid_invalid'), $_GET['modelid']));
}

if(!$_POST['dosubmit']) {

    $subtitle = lang('itemcp_model_edit');
    $t_model = $M->read($_GET['modelid']);
    $disabled = ' disabled="disabled"';

    $admin->tplname = cptpl('model_save', MOD_FLAG);

} else {

    $t_model = $_POST['t_model'];
    unset($t_model['tablename'], $t_model['usearea']);

    foreach($t_model as $key => $val) {
        if(empty($val)) redirect('itemcp_model_post_unmet_from');
    }
    $M->edit($t_model, $modelid);

    redirect('global_op_succeed', cpurl($module, 'model_list'));
}
?>