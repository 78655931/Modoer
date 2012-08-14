<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$op = _input('op');
$TT =& $_G['loader']->model('member:tasktype');
$T =& $_G['loader']->model('member:task');
$tasktypes = $_G['loader']->variable('tasktype', 'member');

switch($op) {
case 'type':
    $task_types = $TT->load_local();
    $admin->tplname = cptpl('tasktype', MOD_FLAG);
    break;
case 'install':
    if($ttid = $TT->install()) {
        redirect('global_op_succeed',cpurl($module,$act,'type'));
    }
    break;
case 'unstall':
    $TT->unstall();
    redirect('global_op_succeed',cpurl($module,$act,'type'));
    break;
case 'update':
    $T->update(_post('task',null));
    redirect('global_op_succeed',cpurl($module,$act));
    break;
case 'add':
case 'edit':
    if($op=='edit') {
        $detail = $T->read(_get('taskid',null,MF_INT_KEY));
        if(!$detail) redirect('member_task_empty');
        $taskflag = $detail['taskflag'];
    } else {
        $taskflag = _get('flag', null, MF_TEXT);
        if(!$taskflag) {
            $tasktypes = $_G['loader']->variable('tasktype', 'member');
            if($tasktypes) {
                $taskflag = each($tasktypes);
                $taskflag = $taskflag['key'];
            }
        }
        $detail = null;
    }
    if($taskflag) $form_condition = $TT->create_form($taskflag, $detail);
    $admin->tplname = cptpl('task_save', MOD_FLAG);
    break;
case 'save':
    $taskid = _post('do')=='edit' ? _post('taskid',null,MF_INT_KEY) : null;
    $taskid = $T->save($T->get_post($_POST), $taskid);
    if($taskid) redirect('global_op_succeed', cpurl($module,$act));
    break;
case 'delete':
    $T->delete(_input('taskid',null,MF_INT_KEY));
    redirect('global_op_succeed',cpurl($module,$act));
default:
    list($total,$list) = $T->find($_GET['page']);
    $admin->tplname = cptpl('task', MOD_FLAG);
}
?>