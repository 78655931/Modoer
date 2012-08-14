<?php
/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'make_randcode', 'compare_randcode' );
$login_ops = array( 'compare_randcode' );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;
if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}
$GT =& $_G['loader']->model(MOD_FLAG.':gift');
switch($op) {
case 'make_randcode':
    if(!$codelen = (int)$_POST['codelen']) redirect(lang('global_sql_keyid_invalid', 'codelen'));
    $randnum = $GT->randnum($codelen);
    echo $randnum;
    output();
    break;
case 'compare_randcode':
	if(!$giftid = (int)$_POST['giftid']) redirect(lang('global_sql_keyid_invalid', 'giftid'));
	$detail = $GT->read($giftid);
	if($_POST['dosubmit']) {
		if(!$_POST['pay_style']) redirect('您未选择支付此次抽奖的积分，请点确定后重新选择。');
		if($detail['num']=='0') redirect('抱歉，奖品已经被抽完了，请您下次尽早抽奖。');
		if($detail['starttime'] && $detail['endtime']){
			if($_G[timestamp]<$detail['starttime']) redirect('抱歉，抽奖还未开始，请在抽奖开始后再抽奖。');
			if($_G[timestamp]>$detail['endtime']) redirect('抱歉，抽奖已结束，感谢您的支持。');
		}
		$usergroup = explode(',',trim($detail['usergroup'],','));
    	if(!in_array($user->groupid,$usergroup)) redirect('抱歉，您所在的用户组不允许兑换礼品或参加抽奖。');
		if($detail['sid'] && $detail['reviewed']){
			if(!$GT->r_exists($detail['sid'])) {
        	    redirect('此礼品需点评后才能兑换，您还未点评，请点击商家名称进行点评。');
    	    }
		}
		$M =& $_G['loader']->model('member:member');
		$meminfo = $M->read($user->uid);
		$pointtype = $detail['pointtype'];
		if($detail['pointtype2']) $pointtype2 = $detail['pointtype2'];
		$pointname = template_print('member','point',array('point'=>$pointtype));
		if($detail['pointtype2']) $pointname2 = template_print('member','point',array('point'=>$pointtype2));
		if($detail['price'] && $detail['point']){
			if($meminfo[$pointtype]<$detail['price'] && $meminfo[$pointtype2]<$detail['point']) redirect('您的'.$pointname.'和'.$pointname2.'都不足，无法参与抽奖');
		}else{
			if($meminfo[$pointtype]<$detail['price']) redirect('您的'.$pointname.'都不足，无法参与抽奖');
		}
		$detail['allowtime']==""?$allowtime = '0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23':$allowtime = $detail['allowtime'];
		$nowtime = (int) date("H");
		if(in_array($nowtime,explode(',',$allowtime))){
			$E =& $_G['loader']->model(MOD_FLAG.':exchange');
			$GL =& $_G['loader']->model(MOD_FLAG.':lottery');
			$now_total = $E->count_total($giftid);
			if($detail['timenum']>0 && $now_total>=$detail['timenum']){
				redirect('抱歉，本时间段的礼品已经抽完，请在下个时间段再抽奖。');
			}else{
				$newcode = $GT->randnum($detail['randomcodelen']);
				if($_POST['pay_style']==1){
        			$pointtype = $detail['pointtype'];
        			$total_price = $detail['price'];
        			$pointname = template_print('member','point',array('point'=>$pointtype));
        		}elseif($_POST['pay_style']==2){
        			$total_price = $detail['point'];
        			$pointtype = $detail['pointtype2'];
        			$pointname = template_print('member','point',array('point'=>$pointtype));
        		}else{
        			$total_price = $detail['point3'];
        			$total_price2 = $detail['point4'];
        			$pointtype = $detail['pointtype3'];
        			$pointtype2 = $detail['pointtype4'];
        			$pointname = template_print('member','point',array('point'=>$pointtype));
        			$pointname2 = template_print('member','point',array('point'=>$pointtype2));
    		    }
	   			if($_POST['pay_style']==3){
	   				if($user->$pointtype < $detail['point3']) {
        				redirect(lang('exchange_check_price_less',$pointname));
        			}
        			if($user->$pointtype2 < $detail['point4']) {
        				redirect(lang('exchange_check_price_less',$pointname2));
        			}
        		}else{
        			if($user->$pointtype < $total_price) {
        				redirect(lang('exchange_check_price_less',$pointname));
        			}
        		}
        		//会员积分变化
        		if($_POST['pay_style']==3){
        			$E->member_coin($user->uid, -$total_price, $pointtype, $detail['name']);
        			$E->member_coin($user->uid, -$total_price2, $pointtype2, $detail['name']);
        		}else{
        			$E->member_coin($user->uid, -$total_price, $pointtype, $detail['name']);
        		}
				if($newcode==$detail['randomcode']){
					$post['giftid'] = $detail['giftid'];
					$post['uid'] = $user->uid;
					$post['lotterycode'] = md5($GL->makecode());
					$lid = $GL->save($post);
					echo "<script language='javascript'>";
					echo "alert('恭喜您，您中奖了，将为您跳转到我的奖品页面，请继续完成订单。');";
					echo "top.location='".url('exchange/member/ac/lottery')."';";
					echo "</script>";
				}else{
					redirect('抱歉，您未中奖，请再接再厉。');
				}
			}
		}else{
			redirect('抱歉，该礼品在本时间段不允许抽奖，请在允许抽奖时间段内抽奖。');
		}
	} else {
		include template('exchange_ajax_point');
	}
	output();
    break;
default:
    redirect('global_op_unkown');
}
?>