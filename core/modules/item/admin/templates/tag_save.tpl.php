<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">�༭��ǩ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="altbg1" width="150">��ǩ����:</td>
                <td width="*"><input type="text" class="txtbox2" name="tagname" value="<?=$detail['tagname']?>" />
                <span class="font_2">ע�⣺���ĺ�ı�ǩ����������ݿ��ڵ�ͬ����ǩʱ�����кϲ���</span></td>
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