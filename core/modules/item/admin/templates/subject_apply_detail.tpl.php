<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'check')?>">
    <div class="space">
        <div class="subtitle">处理/查看认领信息</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="150">申请会员:</td>
                <td width="*"><a href="<?=url("space/index/uid/$detail[uid]")?>" target="_blank"><?=$detail['username']?></a></td>
            </tr>
            <tr>
                <td class="altbg1">申请人姓名:</td>
                <td><?=$detail['applyname']?></td>
            </tr>
            <tr>
                <td class="altbg1">联系方式:</td>
                <td><?=$detail['contact']?></td>
            </tr>
            <?if($detail['pic'] && $catcfg['subject_apply_uppic']):?>
            <tr>
                <td class="altbg1"><?=$catcfg['subject_apply_uppic_name']?$catcfg['subject_apply_uppic_name']:'相关照片'?>:</td>
                <td><a href="<?=$detail['pic']?>" target="_blank">点击查看</a></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1">申请时间:</td>
                <td><?=date('Y-m-d H:i', $detail['dateline'])?></td>
            </tr>
            <tr>
                <td class="altbg1">申请说明:</td>
                <td><?=$detail['content']?></td>
            </tr>
            <tr>
                <td colspan="2" class="altbg2"><strong>处理信息</strong></td>
            </tr>
            <?if(!$detail['status']):?>
            <tr>
                <td class="altbg1">通过申请:</td>
                <td><?=form_bool('apply[status]', 1)?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">管理有效期:</td>
                <td><input type="text" name="apply[expirydate]" value="" class="txtbox3" />&nbsp;yyyy-mm-dd&nbsp;留空表示不限制时间</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">处理说明:</td>
                <td><textarea name="apply[returned]" style="width:500px;height:50px;"><?=$detail['returned']?></textarea></td>
            </tr>
            <tr class="altbg1">
                <td colspan="2"><input type="checkbox" name="apply[pm]" id="pm" value="1" checked="checked" />
                <label for="pm">将处理结果以短消息的方式发送给申请会员</label></td>
            </tr>
            <?else:?>
            <tr>
                <td class="altbg1">处理人:</td>
                <td><?=$detail['checker']?></td>
            </tr>
            <tr>
                <td class="altbg1">处理结果:</td>
                <td><?=$detail['status']==1?'已通过':'未通过'?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">处理说明:</td>
                <td><?=$detail['returned']?></td>
            </tr>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if(!$detail['status']):?>
        <input type="hidden" name="forward"value="<?=get_forward()?>" />
        <input type="hidden" name="applyid" value="<?=$applyid?>" />
        <?=form_submit('dosubmit',lang('global_submit'),'yes','btn')?>&nbsp;
        <?endif;?>
        <?=form_button_return(lang('global_return'),'btn')?>
	</center>
</form>
</div>