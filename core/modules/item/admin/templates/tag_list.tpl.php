<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">��ǩ����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="50">��ǩID</td>
				<td width="*">����</td>
				<td width="100">����</td>
				<td width="110">״̬</td>
                <td width="110">�������ʱ��</td>
				<td width="100">����</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="tagids[]" value="<?=$val['tagid']?>" /></td>
                <td><?=$val['tagid']?></td>
				<td><a href="<?=url("item/tag/tagid/$val[tagid]")?>" target="_blank"><?=$val['tagname']?></a></td>
				<td><?=$val['total']?></td>
                <td><?=$val['closed']?'<span class="font_1">�ر�</span>':'<span class="font_3">����</span>'?></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
				<td><a href="<?=cpurl($module,$act,'edit',array('tagid'=>$val['tagid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('tagids[]');" class="btn2">ȫѡ</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <input type="hidden" name="closed" value="0" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','tagids[]')">ɾ����ѡ</button>&nbsp;
        <button type="button" class="btn" onclick="submit_form('myform','op','close','closed',1,'tagids[]')">�ر���ѡ</button>&nbsp;
        <button type="button" class="btn" onclick="submit_form('myform','op','close','closed',0,'tagids[]')">������ѡ</button>&nbsp;
	</center>
	<?endif;?>
</form>
</div>