<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'check')?>">
    <div class="space">
        <div class="subtitle">����/�鿴������Ϣ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="150">�����Ա:</td>
                <td width="*"><a href="<?=url("space/index/uid/$detail[uid]")?>" target="_blank"><?=$detail['username']?></a></td>
            </tr>
            <tr>
                <td class="altbg1">����������:</td>
                <td><?=$detail['applyname']?></td>
            </tr>
            <tr>
                <td class="altbg1">��ϵ��ʽ:</td>
                <td><?=$detail['contact']?></td>
            </tr>
            <?if($detail['pic'] && $catcfg['subject_apply_uppic']):?>
            <tr>
                <td class="altbg1"><?=$catcfg['subject_apply_uppic_name']?$catcfg['subject_apply_uppic_name']:'�����Ƭ'?>:</td>
                <td><a href="<?=$detail['pic']?>" target="_blank">����鿴</a></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1">����ʱ��:</td>
                <td><?=date('Y-m-d H:i', $detail['dateline'])?></td>
            </tr>
            <tr>
                <td class="altbg1">����˵��:</td>
                <td><?=$detail['content']?></td>
            </tr>
            <tr>
                <td colspan="2" class="altbg2"><strong>������Ϣ</strong></td>
            </tr>
            <?if(!$detail['status']):?>
            <tr>
                <td class="altbg1">ͨ������:</td>
                <td><?=form_bool('apply[status]', 1)?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">������Ч��:</td>
                <td><input type="text" name="apply[expirydate]" value="" class="txtbox3" />&nbsp;yyyy-mm-dd&nbsp;���ձ�ʾ������ʱ��</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">����˵��:</td>
                <td><textarea name="apply[returned]" style="width:500px;height:50px;"><?=$detail['returned']?></textarea></td>
            </tr>
            <tr class="altbg1">
                <td colspan="2"><input type="checkbox" name="apply[pm]" id="pm" value="1" checked="checked" />
                <label for="pm">���������Զ���Ϣ�ķ�ʽ���͸������Ա</label></td>
            </tr>
            <?else:?>
            <tr>
                <td class="altbg1">������:</td>
                <td><?=$detail['checker']?></td>
            </tr>
            <tr>
                <td class="altbg1">������:</td>
                <td><?=$detail['status']==1?'��ͨ��':'δͨ��'?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">����˵��:</td>
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