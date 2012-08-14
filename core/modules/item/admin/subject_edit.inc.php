<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$op = _input('op',null,MF_TEXT);

switch($op) {
    case 'link':
        $sid = _input('sid',null,MF_INT_KEY);
        if(!$sid) exit();
        $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $_GET['sid'], true);
        if(!$edit_links) exit;
        echo "<ul class=\"dropdown-ajaxmenu\" style=\"display:block;\">\n";
        foreach($edit_links as $val) {
            if($val['flag']=='item:subject_edit') continue;
            echo "<li><a href=\"$val[url]\">$val[title]</a></li>\n";
        }
        echo "</ul>\n";
        output();
        break;
    case 'owner':
        if(!$sid = _input('sid', null, 'intval')) redirect(lang('global_sql_keyid_invalid','sid'));
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($sid)) redirect('item_empty');
        if(check_submit('dosubmit')) {
            $S->set_owner($sid, _post('username'), _post('expirydate'));
            redirect('global_op_succeed', get_forward(cpurl($module,$act,'',array('sid'=>$sid))));
        } elseif(_get('do')=='delete') {
            $S->delete_owner($sid, _get('uid',null,'intval'));
            redirect('global_op_succeed_delete', get_forward(cpurl($module,$act,'',array('sid'=>$sid))));
        } else {
            $owners = $S->owners($sid);
            $admin->tplname = cptpl('subject_owner', MOD_FLAG);
        }
        break;
    case 'log':
    default:
        if($op == 'log') $LOG =& $_G['loader']->model(MOD_FLAG.':subjectlog');
        if(!$_POST['dosubmit']) {
            switch($op) {
                case 'log': // 处理来自主题补充
                    if(!$upid = (int) $_GET['upid']) redirect(lang('global_sql_keyid_invalid','upid'));
                    if(!$log = $LOG->read($upid)) redirect('itemcp_log_empty');
                    $_GET['sid'] = $log['sid'];
                    break;
            }

            $_G['loader']->helper('form', MOD_FLAG);

            $sid = $_GET['sid'] = (int) $_GET['sid'];
            if(!$detail = $I->read($sid)) {
                redirect('global_op_empty');
            }

            $pid = $detail['pid'];
            define('ITEM_PID', $pid);
            define('EDIT_SID', $sid);
            $field_form = $I->create_form($pid, $detail);

            $category = $I->variable('category');
            $model = $I->variable('model_' . $category[$pid]['modelid']);

            $edit_links = $_G['hook']->hook('admincp_subject_edit_link', $sid, true);
            $admin->tplname = cptpl('subject_save', MOD_FLAG);

        } else {

            if(!$_POST['sid'] = (int) $_POST['sid']) {
                redirect(lang('global_sql_keyid_invalid', 'sid'));
            }
            $post = $_POST['t_item'];
            $I->save($post, $_POST['sid']);

    		$navs = array(
    			array('name'=>'global_redirect_return', 'url'=>get_forward(cpurl($module,$act,'list'),1)),
    			array('name'=>'item_redirect_subjectlist', 'url'=>cpurl($module,'subject_list')),
    			array('name'=>'item_redirect_addsubject', 'url'=>cpurl($module,'subject_add')),
    		);

            switch($op) {
            case 'log': // 处理来自主题补充
                if(!$upid = (int) $_POST['upid']) redirect(lang('global_sql_keyid_invalid','upid'));
                $LOG->disposal($_POST['log'], $upid);
    			$navs[] = array(
    				'name'=>'item_redirect_subjectloglist',
    				'url'=>cpurl($module,'subject_log'),
    			);
                break;
            }

            redirect('global_op_succeed', $navs);
        }
}

function create_field_form($pid, $detail = NULL, $ff_prarms = array('class'=>'altbg1')) {
    $loader = _G('loader');

    $cate = $loader->variable('category', 'item');
    
    if(!$category = $cate[$pid]) redirect('不存在分类。');
    if(!$fieldlist = $loader->variable('field_' . $category['modelid'], 'item')) redirect('不存在字段信息。');

    $FF =& $loader->model('item:fieldform');
    $FF->all_data($detail);
    if($ff_prarms && is_array($ff_prarms)) {
        foreach($ff_prarms as $k => $v) {
            $FF->$k = $v;
        }
    }
    $FF->class = "altbg1";
    $content = '';
    foreach($fieldlist as $val) {
        $content .= $FF->form($val, $detail[$val['fieldname']], TRUE) . "\r\n";
    }

    return $content;
}
?>