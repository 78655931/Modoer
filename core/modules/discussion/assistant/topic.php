<?php
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$TP = $_G['loader']->model('discussion:topic');

switch($op) {
    case 'post':
    	$post = $TP->get_post($_POST);
    	$tpid = _post('tpid',null,MF_INT_KEY);
    	if(!$tpid) $tpid = null;
        if($MOD['topic_seccode']&&$tpid==null) check_seccode($_POST['seccode']);
    	$tpid = $TP->save($post, $tpid);
    	if(RETURN_EVENT_ID=='CHECK') {
    		redirect('global_op_succeed_check',url("discussion/topic/id/$tpid"));
    	} else {
    		if(DEBUG||defined('IN_AJAX')) redirect('global_op_succeed',url("discussion/topic/id/$tpid"));
            location(url("discussion/topic/id/$tpid"));
    	}
    	break;
    case 'edit':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);
        if(empty($detail)) redirect('discussion_topic_empty');
        if($detail['uid']!=$user->uid) redirect('global_op_access');
        //ฑํว้
        $smilies = array();
        for ($i=1; $i <= 30; $i++) $smilies[$i] = "$i";

        $tplname = 'topic_save';
        break;
    case 'delete':
        $tpid = _input('tpid', null, MF_INT_KEY);
        $detail = $TP->read($tpid);
        $TP->delete($tpid);
        location(url("discussion/list/sid/$detail[sid]"));
        break;
    default:

        redirect('global_op_unknow');
 }
?>