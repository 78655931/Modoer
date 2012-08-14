<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�����Ϣ</div>
        <ul class="cptab">
            <li<?=$act=='review'?' class="selected"':''?>><a href="<?=cpurl($module,'review','checklist')?>">��˵���</a></li>
            <li<?=$act=='respond'?' class="selected"':''?>><a href="<?=cpurl($module,'respond','checklist')?>">��˻�Ӧ</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="50">����</td>
				<td width="50">����</td>
				<td width="135">����</td>
				<td width="*">��������</td>
				<td width="30">�ʻ�</td>
				<td width="30">��Ӧ</td>
				<td width="110">����ʱ��</td>
				<td width="50">�༭</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="rids[]" value="<?=$val['rid']?>" /></td>
                <td><?=$val['idtype']?></td>
				<td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><img src="<?=get_face($val['uid'])?>" /></a><br /><?=$val['username']?></td>
                <td><?=$val['title']?$val['title']:'N/A'?></td>
				<td><?=trimmed_title($val['content'], 200, '...')?></td>
				<td><?=$val['flowers']?></td>
				<td><?=$val['responds']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
				<td><a href="<?=cpurl($module, 'review', 'edit', array('rid' => $val['rid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('rids[]');" class="btn2">ȫѡ</button>
				</td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','rids[]')">ɾ����ѡ</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','rids[]')">�����ѡ</button>&nbsp;
	</center>
	<?endif;?>
</form>
</div>