<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$GT =& $_G['loader']->model(MOD_FLAG.':gift');
$EX =& $_G['loader']->model(MOD_FLAG.':exchange');

$from = _input('from',null);
if(check_submit('dosubmit')) {
    $_G['exchange_action'] = 'post';
    $post = $EX->get_post($_POST);
    $exchangeid = $EX->save($post, $from);
    redirect('exchange_post_return', url('exchange/member/ac/m_gift'));
} else {
    $giftid = (int) $_GET['giftid'];
    $M =& $_G['loader']->model('member:member');
    $minfo = $M->read($user->uid);
    $GL =& $_G['loader']->model(MOD_FLAG.':lottery');
    $_G['exchange_action'] = 'form';
    $params = array();
    $gift = $EX->check_exchange($giftid, $from);
    $usergroup = explode(',',trim($gift['usergroup'],','));
    if(!in_array($user->groupid,$usergroup)) redirect('��Ǹ�������ڵ��û��鲻����һ���Ʒ��μӳ齱��');
    if($gift['pattern'] == 2){
    	$pattern = (int) $_GET['pattern'];
    	$lid = (int) $_GET['lid'];
    	$lotts = $GL->read($lid,$giftid);
    	$rcode = _get('rcode',null,'_T');
    	if($pattern!=$gift['pattern']) redirect('���棬�Ƿ��������Ͳ����ϣ��뷵�ء�');
    	if(!$rcode || !$lid) redirect('���棬�Ƿ������뷵�ء�');
    	if($lotts['lotterycode']!=$rcode) redirect('���棬�Ƿ������뷵�ء�');
    	//����Ƿ��н�
    	if(!$GL->check_exists($rcode)) redirect('��Ǹ����δ�н�,�벻Ҫ�Ƿ��ύ���뷵�ء�');
    	//����Ƿ��Ѷҽ�
    	if($GL->check_exists($rcode,TRUE)) redirect('��Ǹ�����Ѿ��ҹ��˽�,�벻Ҫ�Ƿ��ύ���뷵�ء�');
    }else{
    	$now_total = $EX->count_total($giftid);
		if($gift['timenum']>0 && $now_total>=$gift['timenum']){
			redirect('��Ǹ����ʱ��ε���Ʒ�Ѿ��һ��꣬�����¸�ʱ��������һ���');
		}

    	if($gift['sid'] && $gift['reviewed']){
			if(!$GT->r_exists($gift['sid'])) {
            	redirect('����Ʒ���������ܶһ�������δ�����������̼����ƽ��е�����');
        	}
		}
    }
    $address =& _G('loader')->model('member:address')->get_list();
}
?>