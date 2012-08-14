<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function selectall(obj) {
    obj.focus();
    obj.select();
}
</script>
<div id="body">
	<div class="space">
		<div class="subtitle">广告位代码</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0"  trmouse="Y">
			<tr>
                <td class="altbg1" width="200"><strong>调用代码：</strong>复制文本框内的代码粘贴到对应的模板位置。</td>
                <td width="*">
					<input type="text" name="code" id="code" value="{include display('adv:show','name/<?=$detail['name']?>')}" class="txtbox" onclick="selectall(this);" style="width:99%;" />
				</td>
            </tr>
		</table>
	</div>
    <center>
		<input type="button" class="btn" value="<?=lang('admincp_return')?>" onclick="document.location='<?=cpurl($module,$act)?>';" />
    </center>
</div>