<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('ITEM_SUBJECT_ADD', TRUE);
$I =& $_G['loader']->model(MOD_FLAG.':subject');

if(!$_POST['dosubmit']) {

    $user->check_access('item_subjects', $I);

    //检查分店
    if(isset($_GET['subbranch_sid'])) {
        $subbranch_sid = _get('subbranch_sid',null,'intval');
        if(!$subbranch = $I->read($subbranch_sid)) redirect('item_empty');
        $category = $I->get_category($subbranch['pid']);
        if(!$category['config']['use_subbranch']) redirect('item_post_subbranch_enabled');
        $_GET['pid'] = (int)$subbranch['pid'];
        $detail = array();
        foreach(array('city_id','aid','pid','catid','name') as $key) {
            $detail[$key] = $subbranch[$key];
        }
        define('item_allownull_subname', 1); //
    } elseif($name = _get('name',null,MF_TEXT)) {
        $detail['name'] = $name;
    }

    $_G['loader']->helper('form', MOD_FLAG);
    if($pid = (int) $_GET['pid']) {
        define("ITEM_PID", $pid);
        $field_form = $I->create_form($pid, $detail, null); //生成表单
    }
    $tplname = 'subject_save';
    $mymenu = 'menu';

} else {

    if($MOD['seccode_subject']) check_seccode($_POST['seccode']);
    $post = $_POST['t_item'];
    $post['dateline'] = $_G['timestamp'];
    $post['cuid'] = $user->uid; //创建者;
    $post['creator'] = $user->username; //创建者;
    $sid = $I->save($post);

    if(RETURN_EVENT_ID == 'global_op_succeed') {
        $url = url('item/detail/id/' . $sid);
    } else {
        $url = url('item/member/ac/m_subject/pid/'.ITEM_PID);
    }

    redirect(RETURN_EVENT_ID, $url);
}
?>