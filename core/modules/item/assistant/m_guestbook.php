<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$GB =& $_G['loader']->model(MOD_FLAG.':guestbook');
$mymenu = 'menu';
switch($op) {
    case 'delete':
        $GB->delete($_POST['guestbookids'],TRUE,TRUE);
        if($_G['in_ajax']) {
            echo 'OK';
            exit;
        } else {
            redirect('global_op_succeed', get_forward(url('item/member/ac/'.$ac)));
        }
        break;
    case 'add':
        if(!$sid = (int)$_GET['sid']) redirect(lang('global_sql_keyid_invaild','sid'));
        $S =& $_G['loader']->model(MOD_FLAG.':subject');
        if(!$subject = $S->read($sid, 'sid,pid,catid,name,subname,owner,status', FALSE)) redirect('item_empty');
        $is_owner = $user->isLogin && $user->username == $subject['owner'];
        if($is_owner) redirect('item_guestbook_self');

        $category = $S->get_category($subject['pid']);
        $model = $S->get_model($category['modelid']);

        $tplname = 'guestbook_save';
        break;
    case 'edit':
        if(!$guestbookid = (int)$_GET['guestbookid']) redirect(lang('global_sql_keyid_invalid', 'guestbookid'));
        if(!$detail = $GB->read($guestbookid)) redirect('item_guestbook_empty');
        if($user->uid != $detail['uid']) redirect('global_op_access');

        $S =& $_G['loader']->model(MOD_FLAG.':subject');
        if(!$subject = $S->read($detail['sid'], 'sid,pid,catid,name,subname,owner,status', FALSE)) redirect('item_empty');
        $is_owner = $user->isLogin && $user->username == $subject['owner'];
        if($is_owner) redirect('item_guestbook_self');

        $category = $S->get_category($subject['pid']);
        $model = $S->get_model($category['modelid']);

        $tplname = 'guestbook_save';
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$guestbookid = (int)$_POST['guestbookid']) redirect(lang('global_sql_keyid_invalid', 'guestbookid'));
        } else {
            $guestbookid = NULL;
        }
        $post = $GB->get_post($_POST);
        $guestbookid = $GB->save($post, $guestbookid);
        redirect(RETURN_EVENT_ID, get_forward('item/member/ac/m_guestbook'));
        break;
    default:
        $pid = isset($_GET['pid']) ? (int)$_GET['pid'] : (int)$MOD['pid'];
		(!$pid || !$GB->get_category($pid)) and redirect('item_empty_default_pid');

		$category = $GB->variable('category');
		$modelid = $category[$pid]['modelid'];
		$model = $GB->variable('model_' . $modelid);
        $fields = $GB->variable('field_' . $modelid);

        $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
        foreach ($fields as $value) {
            if($var = $varname[$value['fieldname']]) {
                $$var = $value['title'];  
            }
        }

        $select = 'g.*,s.name,s.subname,s.city_id,s.aid,s.pid,s.catid';
        $where = array();
		//$where['pid'] = (int) $pid;
		$where['g.uid'] = $user->uid;
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $GB->find($select, $where, array('g.dateline'=>'DESC'), $start, $offset, 1, 1);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/page/_PAGE_"));

        $path_title = lang('item_title_m_guestbook');
        $tplname = 'guestbook_list';
}
?>