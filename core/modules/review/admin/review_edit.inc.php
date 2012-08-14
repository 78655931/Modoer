<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$R =& $_G['loader']->model(MOD_FLAG.':review');

if($op == 'report') {
    $RT =& $_G['loader']->model(MOD_FLAG.':report');
}

if(!$_POST['dosubmit']) {

    switch($op) {
    case 'report': // 处理来自举报
        $reportid = (int) $_GET['reportid'];
        if(!$report = $RT->read($reportid)) redirect('itemcp_report_empty');
        $_GET['rid'] = $report['rid'];
        break;
    }

	$rid = (int) $_GET['rid'];
	$select = 'r.*,s.pid';
	if(!$detail = $R->read($rid, $select, TRUE)) {
        if($op != 'report') redirect('global_op_empty');
    }

    if($detail) {
        $pid = $detail['pid'];
        $category = $R->get_category($pid);
        $modelid = $category['modelid'];
        $catcfg = $category['config'];
		$rogid = $category['review_opt_gid'];
        $model = $R->get_model($modelid);

        $review_opts = $R->variable('review_opt_' . $rogid);
        $taggroups = $R->variable('taggroup');
    }

	$admin->tplname = cptpl('review_edit', MOD_FLAG);

} else {

    $forward = get_forward(cpurl($module,'review_list'),1);

    switch($op) {
    case 'report': // 处理来自主题补充
        $reportid = (int) $_POST['reportid'];
        $RT->disposal($_POST['report'], $reportid);
        if($_POST['report']['delete'] || $_POST['empty_review']) {
            redirect(global_op_succeed, $forward);
        }
        break;
    }

	if(!$_POST['rid'] = (int) $_POST['rid']) {
		redirect(lang('global_sql_keyid_invalid', 'rid'));
	}
	$post = $R->get_post($_POST['review']);
	$rid = $R->save($_POST['review'], $_POST['rid']);
    
	redirect(global_op_succeed, $forward);

}

?>