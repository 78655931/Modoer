<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">���⿨���ݹ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="80">״̬</td>
                <td width="*">����</td>
				<td width="120">����ʱ��</td>
				<td width="120">����ʱ��</td>
				<td width="80">����</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
				<td><input type="checkbox" name="ids[]" value="<?=$val[id]?>"></td>
				<td><?=($val[status]?'<span class="font_3">δʹ��</span>':'<span class="font_2">��ʹ��</span>')?></td>
				<td><?=$val[serial]?></td>
				<td><?=date('Y-m-d H:i',$val[dateline])?></td>
				<td><?=$val[sendtime]?date('Y-m-d H:i',$val[sendtime]):'δ����'?></td>
				<td>-</td>
			</tr>
			<?endwhile?>
			<tr class="altbg1">
				<td colspan="2"><input type="button" class="btn2" value="ȫѡ" onclick="checkbox_checked('ids[]');"></td>
				<td colspan="6"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">������Ϣ</td>
			</tr>
			<?endif;?>
		</table>
	</div>
	<center>
		<input type="hidden" name="giftid" value="<?=$giftid?>">
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">ɾ����ѡ</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="serial_add();">����</button>&nbsp;
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,'gift','edit',array('giftid'=>$giftid))?>'">����</button>&nbsp;
	</center>
</form>
<script type="text/javascript">
function serial_add () {
	var form = $("<form action=\"<?=cpurl($module,$act,'save')?>\" method=\"post\"></form>");
	form.append("<textarea name=\"serial\" rows=\"5\" cols=\"40\" id='post_serial'></textarea>");
	form.append("<div class=\"font_1\" style='margin:5px 0;'>һ��һ��������Ϣ;���磺����:12345678 ����:123456</div>");
	form.append("<input type=\"submit\" name=\"dosubmit\" value=\"�ύ\">&nbsp;&nbsp;");
	form.append("<input type=\"button\" value=\"�ر�\" onclick='dlgClose()'>");
	form.append("<input type=\"hidden\" name=\"giftid\" value=\"<?=$giftid?>\">");
	form.bind('submit',function(){
		if($('#post_serial').val()=='') {
			alert('�Բ�������δ��д������Ϣ��');
			return false;
		}
		return true;
	});
	dlgOpen('��ӿ���',form,430,200);
}
</script>