<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$LK =& $_G['loader']->model(MOD_FLAG.':mylink');
$op = $_GET['op'] ? $_GET['op'] : $_POST['op'];

switch($op) {
    case 'delete':
        $LK->delete($_POST['linkids'],$_POST['uppoint']);
        redirect('global_op_succeed_delete', cpurl($module,$act));
        break;
    case 'update':
        $LK->update($_POST['links']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act)));
        break;
    case 'checkup':
        $LK->checkup($_POST['linkids']);
        redirect('global_op_succeed_checkup', cpurl($module,$act,'checklist'));
        break;
    case 'add':
        $admin->tplname = cptpl('save', MOD_FLAG);
        break;
    case 'save':
        if($_POST['do']=='edit') {
            if(!$linkid = (int)$_POST['linkid']) redirect(lang('global_sql_keyid_invalid','linkid'));
        } else {
            $linkid = null;
        }
        $post = $LK->get_post($_POST);
        $LK->save($post, $linkid);
        redirect('global_op_succeed', get_forward(cpurl($module,$act),1));
        break;
    case 'checklist':
        $where = array('ischeck'=>0);
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $LK->find('*', $where, 'linkid', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'checklist'));
        }
        $admin->tplname = cptpl('list', MOD_FLAG);
        break;
    default:
        $op = 'list';
        !$_GET['type'] && $_GET['type'] = 'char';
        $where = array('ischeck'=>1);
        $offset=20;
        $start = get_start($_GET['page'],$offset);
        if($_GET['type']=='logo') $where['nq_logo'] = '1';
        if($_GET['type']=='char') $where['logo'] = '';
        list($total, $list) = $LK->find('*', $where, 'displayorder', $start, $offset, TRUE);
        if($total) {
            $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, 'list', array('type'=>$_GET['type'])));
        }
        $check_count = $LK->get_check_count();
        $admin->tplname = cptpl('list', MOD_FLAG);
}
?>