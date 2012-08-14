<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">¡Ù—‘±‡º≠</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="150" class="altbg1" valign="top">¡Ù—‘ƒ⁄»›:</td>
                <td width="*"><textarea name="guestbook[content]" style="width:500px;height:100px;"><?=$detail['content']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">ªÿ∏¥ƒ⁄»›:</td>
                <td><textarea name="guestbook[reply]" style="width:500px;height:100px;"><?=$detail['reply']?></textarea></td>
            </tr>
        </table>
    </div>
	<center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <input type="hidden" name="guestbookid" value="<?=$guestbookid?>" />
        <?=form_submit('dosubmit',lang('global_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('global_return'),'btn')?>
	</center>
</form>
</div>