<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$M =& $_G['loader']->model('member:message', TRUE, FALSE, $op=='send');

if(!defined('IN_UC') || $_G['in_ajax']) {
    switch($op) {
    case 'read':
        if($_G['in_ajax']) {
            if(!$user->isLogin) dialog('Message', '', 'login');

            $pmid = _post('pmid',null,MF_INT_KEY);
            $pmid = isset($pmid) && is_numeric($pmid) && $pmid > 0 ? intval($pmid) : 0;
            if(!$pmid) {
                redirect('member_pm_not_exists');
            }

            $msg = $M->read($user->uid, $pmid);
            if(!$msg) {
                redirect('member_pm_not_exists_2');
            }
            $folder = $msg['recvuid'] == $user->uid ? 'inbox' : 'outbox';
            include template('pm_readmessage_table', 'member', MOD_FLAG);
            output();
        }
        break;
    case 'write':
        $recvuid = $_G['in_ajax'] ? $_POST['recvuid'] : $_GET['recvuid'];
        $subject = $_G['in_ajax'] ? $_POST['subject'] : ($_GET['subject'] ? urldecode($_GET['subject']) : '');
        $recvuid = is_numeric($recvuid) && $recvuid > 0 ? $recvuid : '';
        if($recvuid) {
            if($recvuid == $user->uid) redirect('member_pm_dnot_send_self');
            $recv = $user->read($recvuid);
            $recv_username = $recv['username'];;
        }
        if($_G['in_ajax']) {
            if(!$user->isLogin) dialog('Write message', '', 'login');
            if($subject && $_G['charset'] != 'utf-8') {
                $_G['loader']->lib('chinese', NULL, FALSE);
                $CHS = new ms_chinese('utf-8', $_G['charset']);
                $subject = $subject ? _T($CHS->Convert($subject)) : '';
            }
            require_once template('member_pm');
            output();
        } else {
            $tplname = 'pm_write';
            $subject = _T($subject);
        }
        break;
    case 'send':
        $result = $M->send($user->uid, $_POST['recv_users'], $_POST['subject'], $_POST['content'], TRUE);
        if(!$result) {
            redirect('An unknown error occurred.');
        }
        redirect('global_op_succeed', url('member/index/ac/pm/folder/outbox'));
    case 'delete':
        if(!check_submit('dosubmit')) {
            $M->delete($user->uid, $_POST['folder'], $_POST['pmids']);
            redirect('global_op_succeed', url('member/index/ac/'.$ac.'/folder/'.$_POST['folder']));
        }
        break;
    default:
        if(!check_submit('dosubmit')) {
            $folder = $_GET['folder'] == 'outbox' ? 'outbox' : 'inbox';
            $offset = 20;
            list($total, $list) = $M->find($user->uid, $folder,array('posttime'=>'DESC'), get_start($_GET['page'], $offset), $offset);
            if(!$total && $folder == 'inbox' && $user->newmsgs > 0) {
                $M->clear_new_record($user->uid);
            }
            $sub_param = array('inbox' => lang('member_pm_inbox'), 'outbox' => lang('member_pm_outbox'));
            $multipage = multi($total, $offset, $_GET['page'], url("member/index/ac/$ac/folder/$folder/page/_PAGE_"));
        }
    }

}
?>