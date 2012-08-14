<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$D =& $_G['loader']->model('datacall');
$op = _input('op',null,MF_TEXT);

switch($op) {
case 'tplist':
    $contents = form_datacall_template_files($_G['cfg']['templateid']);
    echo $contents;
    exit;
case 'add':
case 'addsql':
    if(!$_POST['dosubmit']) {
        $datacall = array();
        $datacall['var'] = 'mydata';
        $datacall['expression']['cachetime'] = 1000;
        if($op == 'edit') {
            $datacall['expression']['row'] = 10;
        } else {
            $datacall['expression']['limit'] = '0,10';
        }
    } else {
        $D->save($_POST['datacall']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
    }
    $admin->tplname = cptpl($op=='add'?'datacall_funcall':'datacall_sqlcall');
    break;
case 'edit':
case 'editsql':
    if(!$_POST['dosubmit']) {
        if(!$_GET['callid'] = (int)$_GET['callid']) {
            redirect(sprintf(lang('global_sql_keyid_invalid'), 'callid'));
        }

        if(!$datacall = $D->read($_GET['callid'])) {
            redirect('global_op_nothing');
        }

        $datacall['expression']['params'] = '';
        if(is_array($datacall['expression'])) {
            foreach($datacall['expression'] as $key => $val) {
                if(empty($val)) continue;
                if(in_array($key, array('cachetime','row','order'))) continue;
                $datacall['expression']['params'] .= $split . $key . '=' . $val;
                $split = "\r\n";
            }
        }
    } else {
        if(!$_POST['callid'] = (int)$_POST['callid']) {
            redirect(sprintf(lang('global_sql_keyid_invalid'), 'callid'));
        }
        $D->save($_POST['datacall'], $_POST['callid']);
        redirect('global_op_succeed', get_forward(cpurl($module,$act,'list'),1));
    }
    $admin->tplname = cptpl($op=='edit'?'datacall_funcall':'datacall_sqlcall');
    break;
case 'refresh':
    if($_POST['callids']) {
        $D->refresh($_POST['callids']);
    } else {
        $D->delete_datacall_cache_all();
    }
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'delete':
    if(!$_POST['callids']) {
        redirect('global_op_unselect');
    }
    $D->delete($_POST['callids']);
    redirect('global_op_succeed', get_forward(cpurl($module,$act,'list')));
    break;
case 'import':
    if($_POST['dosubmit']) {
        $D->import();
        redirect('global_op_succeed', cpurl($module,$act,'list'));
    } else {
        $admin->tplname = cptpl('datacall_import');
    }
    break;
case 'export':
    $D->export();
    break;
case 'code':
    $callid = (int) $_GET['callid'];
    $detail = $D->read($callid);
    $admin->tplname = cptpl('datacall_code');
    break;
default:
    $op = 'list';
    $where = array();
    $_GET['flag'] && $where['module']= $_GET['flag'];
    $_GET['callid'] && $where['callid']= $_GET['callid'];
    $_GET['name'] && $where['name']= $_GET['name'];
    $_GET['orderby'] = $_GET['orderby'] ? $_GET['orderby'] : 'callid';
    $_GET['ordersc'] = $_GET['ordersc'] ? $_GET['ordersc'] : 'DESC';
    $_GET['offset'] = $_GET['offset'] > 0 ? $_GET['offset'] : 20;
    list($total, $list) = $D->find('*',$where,array($_GET['orderby']=>$_GET['ordersc']),get_start($_GET['page'], $_GET['offset']),$_GET['offset'],TRUE);
    if($total) {
        $multipage = multi($total,$_GET['offset'],$_GET['page'],cpurl($module,$act,'list',$_GET));
    }
    $admin->tplname = cptpl('datacall_list');
}
?>