<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<div class="mainrail rail-border-1">
    <h3 class="rail-h-1 rail-h-bg-1"><?=$member['username']?></h3>
    <div style="text-align:center;"><a href="<?php echo url("space/index/uid/{$uid}"); ?>"><img src="<?php echo get_face($member['uid']); ?>" /></a></div>
    <ul class="rail-list">
        <li style="text-align:center;">
            <a href="javascript:add_friend(<?=$uid?>);">�Ӻ���</a>
            <a href="javascript:send_message(<?=$uid?>);">������</a>
            <a href="javascript:member_follow(<?=$uid?>);">�ӹ�ע</a>
        </li>
        <li>���룺<?php echo newdate($member['regdate'], 'Y-m-d H:i'); ?></li>
        <li>��¼��<?php echo newdate($member['logintime'], 'Y-m-d H:i'); ?></li>
        <li>�ȼ���<?php echo template_print('member','group',array('groupid'=>$member['groupid'],));?></li>
        <li>������<span class="font_2"><?=$member['reviews']?></span></li>
        <li>�Ǽǣ�<span class="font_2"><?=$member['subjects']?></span></li>
        <li>�ʻ���<span class="font_2"><?=$member['flowers']?></span></li>
        <li>��ע��<span class="font_2"><?=$member['follow']?></span></li>
        <li>��˿��<span class="font_2"><?=$member['fans']?></span></li>
        <li>���ʣ�<span class="font_2"><?=$space['pageview']?></span></li>
    </ul>
</div>