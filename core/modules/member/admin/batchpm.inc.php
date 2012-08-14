<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model(':member');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$_G['loader']->helper('form','member');
switch($op) {
    case 'search':
        $total = batchcp_search('total');
        $admin->tplname = cptpl('batchpm', MOD_FLAG);
        break;
    case 'send':
        if($_GET['send']) {
            batchpm_start();
        } else {
            batchpm_init();
        }
        break;
    default:
        $admin->tplname = cptpl('batchpm', MOD_FLAG);
}

function batchcp_search($return_type='data') {
    global $_G, $M;
    $M->db->from($M->table);
    if($_GET['username']) {
        $names = explode(',', str_replace(' ', '', $_GET['username']));
        $M->db->where_in('username', $names);
    }
    if($_GET['groupid']) $M->db->where('groupid', $_GET['groupid']);
    if($return_type=='total') return $M->db->count();
    $M->db->select('uid,username');
    $M->db->order_by('uid');
    $_GET['offset'] = $_GET['offset'] > 0 ? $_GET['offset'] : 100;
    $_GET['start'] = get_start($_GET['page'], $_GET['offset']);
    $M->db->limit($_GET['start'],$_GET['offset']);

    return $list = $M->db->get();
}

function batchpm_init() {
    global $_G, $M, $module, $act;

    if(!$_POST['title']) redirect('membercp_batchpm_title_empty');
    if(!$_POST['message']) redirect('membercp_batchpm_message_empty');

    $C =& $_G['loader']->model('config');
    $config = array();
    $config['batchpm'] = serialize(array($_POST['title'],$_POST['message']));
    $C->save($config, MOD_FLAG);
    $_GET['offset'] = $_POST['offset'] > 0 ? $_POST['offset'] : 100;
    location(cpurl($module,$act,'send',array('username'=>$_POST['username'],
        'groupid'=>$_POST['groupid'],'offset'=>$_GET['offset'],'send'=>'yes')));
}

function batchpm_start() {
    global $_G, $M, $module, $act;

    $C =& $_G['loader']->model('config');

    if(!$list = batchcp_search('data')) {
        $C->delete('batchpm', MOD_FLAG);
        redirect('membercp_batchpm_message_succeed',cpurl('member','batchpm'));
    }

    $batchpm = $C->read('batchpm', MOD_FLAG);
    list($title,$message) = unserialize($batchpm['value']);
    unset($C);
    $M =& $_G['loader']->model(($_G['ucenter'] ? 'ucenter' : 'member').':message');
    while($val=$list->fetch_array()) {
        $M->send(0, $val['uid'], $title, $message);
    }
    $list->free_result();

    $page = $_GET['page'] + 1;
    $url = cpurl($module,$act,'send',array('username'=>$_GET['username'],'groupid'=>$_GET['groupid'],'offset'=>$_GET['offset'],'page'=>$page,'send'=>'yes'));
    redirect('ÕýÔÚ·¢ËÍ'.($_GET['start']+1).'...'.($_GET['start']+$_GET['offset']), $url);
}
?>