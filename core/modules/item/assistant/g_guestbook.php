<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$GB =& $_G['loader']->model(MOD_FLAG.':guestbook');

switch($op) {
    case 'delete':
        $GB->delete($_POST['guestbookids']);
        redirect('global_op_succeed', get_forward(url('item/member/ac/'.$ac)));
        break;
    case 'reply':
        if($_POST['dosubmit']) {
            $guestbookid = (int) $_POST['guestbookid'];
            $GB->reply($guestbookid, $_POST['reply']);
            redirect('global_op_succeed', get_forward(url('item/member/ac/g_guestbook'),1));
        } else {
            $guestbookid = (int) $_GET['guestbookid'];
            if(!$detail = $GB->read($guestbookid)) {
                redirect('item_guestbook_empty');
            }
            $S =& $_G['loader']->model(MOD_FLAG.':subject');
            $subject = $S->read($detail['sid'],'name,subname,owner,status', FALSE);

            if($user->username !=  $subject['owner']) {
                redirect('global_op_access');
            }
            $tplname = 'guestbook_save';
        }
        break;
    case 'insert':
        $guestbookid = (int) $_GET['guestbookid'];
        if(!$detail = $GB->read($guestbookid)) redirect('item_guestbook_empty');
        echo $detail['reply'];
        output();
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

        $S =& $_G['loader']->model('item:subject');
        $mysubjects = $S->mysubject($user->uid);

        $select = 'g.*,s.city_id,s.name,s.subname,s.pid,s.catid';
        $where = array();
		//$where['pid'] = (int) $pid;
		$where['g.sid'] = $_G['manage_subject']['sid'];
        $start = get_start($_GET['page'], $offset = 10);

        list($total, $list) = $GB->find($select, $where, array('g.dateline'=>'DESC'), $start, $offset, 1, 1);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/page/_PAGE_"));

        $path_title = lang('item_title_g_guestbook');
        $tplname = 'guestbook_list';
}
?>