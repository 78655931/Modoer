<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$F =& $_G['loader']->model(MOD_FLAG.':favorite');

switch($op) {
    case 'add':
        if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
        $post = array('id' => $sid);
        $F->save($post);
        redirect('item_favorite_succeed');
        break;
    case 'delete':
        $F->delete($_POST['fids']);
        redirect('global_op_succeed_delete', get_forward(url('item/member/ac/'.$ac)));
        break;
	default:
        $pid = isset($_GET['pid']) ? (int)$_GET['pid'] : (int)$MOD['pid'];
		(!$pid || !$F->get_category($pid)) and redirect('item_empty_default_pid');

		$category = $F->variable('category');
		$modelid = $category[$pid]['modelid'];
		$model = $F->variable('model_' . $modelid);
        $fields = $F->variable('field_' . $modelid);

        $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
        foreach ($fields as $value) {
            if($var = $varname[$value['fieldname']]) {
                $$var = $value['title'];  
            }
        }

        $select = 'f.*,s.city_id,s.name,s.subname,s.pid,s.catid';
        $where = array();
        $where['pid'] = (int) $pid;
        $where['f.uid'] = $user->uid;
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $F->find($select,$where, $start, $offset);
        $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/page/_PAGE_"));

        $tplname = 'favorite_list';
}
?>