<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$C =& $_G['loader']->model('config');
$op = _input('op');

$groups = array('point1','point2','point3','point4','point5','point6');

switch($op) {
case 'group':
    if(check_submit('dosubmit')) {
        if(!is_array($_POST['point_group'])) redirect('global_op_unselect');
        $post = array();
        $post['point_group'] = serialize($_POST['point_group']);
        $C->save($post, MOD_FLAG);
        redirect('global_op_succeed', cpurl($module,$act,'group'));
    } else {
        $point_group = $C->read('point_group', MOD_FLAG);
        $point_group = unserialize($point_group['value']);
        $admin->tplname = cptpl('point_group', MOD_FLAG);
    }
    break;
case 'post':
    if(!is_array($_POST['point'])) redirect('global_op_unselect');
    $post = array();
    $post['point'] = serialize(new_intval($_POST['point']));
    $C->save($post, MOD_FLAG);
    redirect('global_op_succeed', cpurl($module,$act,'setting'));
    break;
case 'log':
    $PT =& $_G['loader']->model('member:point_log');
    $where = array();
    $select = '*';
    $type = _get('type', 'out', MF_TEXT);
    if($type=='out') {
        $where['out_value'] = array('where_more',array(0.01));
    } else {
        $where['in_value'] = array('where_more',array(0.01));
    }
    $orderby = array('id'=>'DESC');
    $start = get_start($_GET['page'], $offset=20);
    list($total, $list) = $PT->find($select, $where, $orderby, $start, $offset, TRUE);
    if($total) {
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module,$act,'log',$_GET));
    }
    $admin->tplname = cptpl('point_log', MOD_FLAG);
    break;
default:
    $op = 'setting';
    $point = $C->read('point', MOD_FLAG);
    $point = unserialize($point['value']);
    $point_group = $C->read('point_group', MOD_FLAG);
    $point_group = unserialize($point_group['value']);
    $list = read_point_rule();
    $admin->tplname = cptpl('point', MOD_FLAG);
}

function & read_point_rule() {
    global $_G;
    $result = array();
    $modules =& $_G['modules'];
    foreach($modules as $key => $val) {
        $file = MUDDER_MODULE . $val['flag'] . DS .'inc' . DS . 'point_rule.php';
        if(!$rules = read_cache($file)) continue;
        if(!is_array($rules)) continue;
        $result[$val['flag']] = $rules;
    }
    return $result;
}
?>