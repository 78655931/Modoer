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
    if(!in_array($user->groupid,$usergroup)) redirect('抱歉，您所在的用户组不允许兑换礼品或参加抽奖。');
    if($gift['pattern'] == 2){
    	$pattern = (int) $_GET['pattern'];
    	$lid = (int) $_GET['lid'];
    	$lotts = $GL->read($lid,$giftid);
    	$rcode = _get('rcode',null,'_T');
    	if($pattern!=$gift['pattern']) redirect('警告，非法请求，类型不符合，请返回。');
    	if(!$rcode || !$lid) redirect('警告，非法请求，请返回。');
    	if($lotts['lotterycode']!=$rcode) redirect('警告，非法请求，请返回。');
    	//检测是否中奖
    	if(!$GL->check_exists($rcode)) redirect('抱歉，您未中奖,请不要非法提交，请返回。');
    	//检测是否已兑奖
    	if($GL->check_exists($rcode,TRUE)) redirect('抱歉，您已经兑过了奖,请不要非法提交，请返回。');
    }else{
    	$now_total = $EX->count_total($giftid);
		if($gift['timenum']>0 && $now_total>=$gift['timenum']){
			redirect('抱歉，本时间段的礼品已经兑换完，请在下个时间段再来兑换。');
		}

    	if($gift['sid'] && $gift['reviewed']){
			if(!$GT->r_exists($gift['sid'])) {
            	redirect('此礼品需点评后才能兑换，您还未点评，请点击商家名称进行点评。');
        	}
		}
    }
    $address =& _G('loader')->model('member:address')->get_list();
}
?>