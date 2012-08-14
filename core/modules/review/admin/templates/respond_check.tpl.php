<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">�����Ϣ</div>
        <ul class="cptab">
            <li<?=$act=='review'?' class="selected"':''?>><a href="<?=cpurl($module,'review','checklist')?>">��˵���</a></li>
            <li<?=$act=='respond'?' class="selected"':''?>><a href="<?=cpurl($module,'respond','checklist')?>">��˻�Ӧ</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="180">��������</td>
				<td width="80">��Ӧ��Ա</td>
                <td width="*">��Ӧ����</td>
                <td width="100">IP</td>
				<td width="110">��Ӧʱ��</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="respondids[]" value="<?=$val['respondid']?>" /></td>
				<td><?if($val['sid']):?><a href="<?=url("item/detail/id/$val[sid]")?>#review" target="_blank"><?=$val['title']?$val['title']:'N/A'?></a><?else:?>������Ϣ�����ڻ���ɾ��<?endif;?></td>
                <td><a href="<?=url("item/space/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=$val['content']?></td>
				<td><?=$val['ip']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('respondids[]');" class="btn2">ȫѡ</button>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','respondids[]')">ɾ����ѡ</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','respondids[]')">�����ѡ</button>&nbsp;
	</center>
	<?endif;?>
</form>
</div>