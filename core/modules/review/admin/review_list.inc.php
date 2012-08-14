<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$R =& $_G['loader']->model(MOD_FLAG.':review');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
$forward = get_forward(cpurl($module,$act));

switch ($op) {
     case 'delete':
        $R->delete($_POST['rids'], TRUE, $_POST['delete_point']);
        redirect('global_op_succeed', $forward );
        break;
     default:
        $pid = isset($_GET['pid']) ? $_GET['pid'] : $MOD['pid'];
		(!$pid || !$R->get_category($pid)) and redirect('item_empty_default_pid');

        $category = $R->variable('category');
		$modelid = $category[$pid]['modelid'];
        $model = $R->variable('model_' . $modelid);

        $where = array();
        if(!$admin->is_founder) $where['r.city_id'] = $_CITY['aid'];
        if($sid = (int)$_GET['sid']) {
            $where['r.sid'] = $sid;
        }
        $where['pid'] = (int) $pid;
        $where['r.status'] = 1;
        $select = 'r.rid,r.sid,r.uid,r.username,r.posttime,r.status,r.content,r.flowers,r.responds,r.ip,s.pid,r.title,s.city_id';
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $R->find($select, $where, array('posttime'=>'DESC'), $start, $offset);
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('pactid' => $pid)));

        $admin->tplname = cptpl('review_list', MOD_FLAG);
 }