<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">�����Ϣ</div>
        <ul class="cptab">
            <li<?=$act=='subject_check'?' class="selected"':''?>><a href="<?=cpurl($module,'subject_check')?>">�������</a></li>
            <li<?=$act=='picture_check'?' class="selected"':''?>><a href="<?=cpurl($module,'picture_check')?>">���ͼƬ</a></li>
            <li<?=$act=='guestbook_check'?' class="selected"':''?>><a href="<?=cpurl($module,'guestbook_check')?>">�������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="180">��������</td>
				<td width="80">���Ի�Ա</td>
                <td width="*">��������</td>
				<td width="110">����ʱ��</td>
                <td width="100">IP</td>
                <td width="50">����</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="guestbookids[]" value="<?=$val['guestbookid']?>" /></td>
				<td><?if($val['name']):?><a href="<?=url("item/detail/id/$val[sid]")?>#review" target="_blank"><?=$val['name'].$val['subname']?></a><?else:?>������Ϣ�����ڻ���ɾ��<?endif;?></td>
                <td><a href="<?=url("item/space/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=$val['content']?></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><?=$val['ip']?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('guestbookid'=>$val['guestbookid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('guestbookids[]');" class="btn2">ȫѡ</button>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','guestbookids[]')">ɾ����ѡ</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','guestbookids[]')">�����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>