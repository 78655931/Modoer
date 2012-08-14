<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">编辑标签</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="altbg1" width="150">标签名称:</td>
                <td width="*"><input type="text" class="txtbox2" name="tagname" value="<?=$detail['tagname']?>" />
                <span class="font_2">注意：更改后的标签名如果遇数据库内的同名标签时将进行合并。</span></td>
			</tr>
        </table>
    </div>
	<center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <input type="hidden" name="tagid" value="<?=$tagid?>" />
        <?=form_submit('dosubmit',lang('global_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('global_return'),'btn')?>
	</center>
</form>
</div>