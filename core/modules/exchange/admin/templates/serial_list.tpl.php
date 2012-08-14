<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">虚拟卡数据管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
				<td width="80">状态</td>
                <td width="*">内容</td>
				<td width="120">生成时间</td>
				<td width="120">发送时间</td>
				<td width="80">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
				<td><input type="checkbox" name="ids[]" value="<?=$val[id]?>"></td>
				<td><?=($val[status]?'<span class="font_3">未使用</span>':'<span class="font_2">已使用</span>')?></td>
				<td><?=$val[serial]?></td>
				<td><?=date('Y-m-d H:i',$val[dateline])?></td>
				<td><?=$val[sendtime]?date('Y-m-d H:i',$val[sendtime]):'未发送'?></td>
				<td>-</td>
			</tr>
			<?endwhile?>
			<tr class="altbg1">
				<td colspan="2"><input type="button" class="btn2" value="全选" onclick="checkbox_checked('ids[]');"></td>
				<td colspan="6"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">暂无信息</td>
			</tr>
			<?endif;?>
		</table>
	</div>
	<center>
		<input type="hidden" name="giftid" value="<?=$giftid?>">
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="serial_add();">增加</button>&nbsp;
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,'gift','edit',array('giftid'=>$giftid))?>'">返回</button>&nbsp;
	</center>
</form>
<script type="text/javascript">
function serial_add () {
	var form = $("<form action=\"<?=cpurl($module,$act,'save')?>\" method=\"post\"></form>");
	form.append("<textarea name=\"serial\" rows=\"5\" cols=\"40\" id='post_serial'></textarea>");
	form.append("<div class=\"font_1\" style='margin:5px 0;'>一行一条卡密信息;例如：卡号:12345678 密码:123456</div>");
	form.append("<input type=\"submit\" name=\"dosubmit\" value=\"提交\">&nbsp;&nbsp;");
	form.append("<input type=\"button\" value=\"关闭\" onclick='dlgClose()'>");
	form.append("<input type=\"hidden\" name=\"giftid\" value=\"<?=$giftid?>\">");
	form.bind('submit',function(){
		if($('#post_serial').val()=='') {
			alert('对不起，您尚未填写卡密信息。');
			return false;
		}
		return true;
	});
	dlgOpen('添加卡密',form,430,200);
}
</script>