<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$A =& $_G['loader']->model(':article');
$op = _input('op',null,MF_TEXT);
$_G['loader']->helper('form',MOD_FLAG);
$_G['loader']->helper('misc',MOD_FLAG);
$_G['loader']->helper('form','item');
switch($op) {
case 'add':
    if($sid = _get('sid',null,MF_INT_KEY)) {
        $S =& $_G['loader']->model('item:subject');
        $subject = $S->read($sid);
        $mysubjects = $S->mysubject($user->uid);
        if(in_array($sid, $mysubjects)) $_GET['role'] = 'owner';
    }
    $_GET['role'] = $_GET['role'] == 'owner' ? 'owner' : 'member';
    if($_GET['role'] == 'member') $user->check_access('article_post', $A);
    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->upimage = (int)$MOD['editor_image'];
    $editor->pagebreak = true;
    $edit_html = $editor->create_html();
    $mymenu = 'menu';
    $_GET['role'] == 'owner' && $mymenu = 'mmenu';
    $tplname = 'article_save';
    break;
case 'edit':
    $articleid = _get('articleid', null, MF_INT_KEY);
    if(!$detail = $A->read($articleid)) redirect('article_empty');
    $_GET['role'] = _get('role','member',MF_TEXT);
    $_GET['role'] != 'owner' && $_GET['role'] = 'member';
    $mymenu = 'menu';
    $_GET['role'] == 'owner' && $mymenu = 'mmenu';
    $access = false;
    if($detail['sid']) {
        $sids = explode(',', $detail['sid']);
        foreach($sids as $sid) {
            if(in_array($sid, $_G['mysubjects'])) {
                $access = true;
            }
        }
    }

    if(!$access) {
        $access = $user->check_access('article_post', $A, false);
        if(!$access) $access = $detail['uid'] == $user->uid;
    }
    //subjectlink
    if(!$access) redirect('global_op_access');

    $_G['loader']->lib('editor',null,false);
    $editor = new ms_editor('content');
    $editor->upimage = (int)$MOD['editor_image'];
    $editor->pagebreak = true;
    $editor->content = $detail['content'];
    $edit_html = $editor->create_html();
    $tplname = 'article_save';
    break;
case 'save':
    if($_POST['do']=='edit') {
        $articleid = (int) $_POST['articleid'];
    } else {
        if($MOD['post_seccode']) check_seccode($_POST['seccode']);
        $articleid = null;
    }
    is_array($_POST['sid']) && $_POST['sid'] = implode(',', $_POST['sid']);
    $post = $A->get_post($_POST);
    $articleid = $A->save($post,$articleid, $_POST['role']);
    $next_ac = $_POST['role'] == 'owner' ? 'g_article' : 'm_article';
    redirect(RETURN_EVENT_ID, get_forward(url('article/member/ac/'.$next_ac),1),array(lang('article_redirect_add')));
    break;
case 'delete':
    $A->delete($_POST['articleids'], TRUE); //同时删除积分
    redirect('global_op_succeed_delete', get_forward(url('article/member/ac/m_article')));
    break;
default:
    redirect('global_op_unkown');
}
?>