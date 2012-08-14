<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
$op = _input('op');
$G =& $_G['loader']->model(MOD_FLAG.':usergroup');
$pt = $MOD['sellgroup_pointtype'];
$useday = $MOD['sellgroup_useday'] > 0 ? $MOD['sellgroup_useday'] : 30;
if(!$pt) redirect('global_op_function_disabled');

switch ($op) {
	case 'buy':
		$day = _input('day',1,MF_INT_KEY);
		$groupid = _input('groupid', 0, MF_INT_KEY);
		$useday *= $day;
		if(empty($useday)) redirect('member_group_upgrade_day_invalid');
		$group = $G->read($groupid);
		if(empty($group)) redirect('member_group_upgrade_empty');
		if($group['grouptype']!='special') redirect('member_group_upgrade_invalid');
        if($user->groupid == $groupid && empty($user->nexttime)) {
            redirect(lang('member_group_upgrade_do_without',display('member:group',"groupid/$groupid")));
        }
		//计算所需花费
		$price = $group['price'] * $day;
		$tplname = 'group_upgrade';
		break;
	case 'pay':
		$G->group_upgrade();
		redirect('恭喜您，账号升级成功！',url("member/index/ac/group"));
		# code...
		break;
	default:
		$myprice = $user->$pt;
		$list = $G->read_all('special');
		$tplname = 'group_upgrade';
		break;
}