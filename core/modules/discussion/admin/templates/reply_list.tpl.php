<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">�ظ�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="180">����</td>
				<td width="100">��Ӧ��Ա</td>
                <td width="*">��Ӧ����</td>
				<td width="110">��Ӧʱ��</td>
                <td width="50">����</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="rpids[]" value="<?=$val['rpid']?>" /></td>
				<td><?if($val['subject']):?><a href="<?=url("discussion/topic/id/$val[tpid]")?>" target="_blank"><?=$val['subject']?></a><?else:?>���ⲻ���ڻ���ɾ��<?endif;?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=trimmed_title($val['content'],100,'...')?></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('rpid'=>$val['rpid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('rpids[]');" class="btn2">ȫѡ</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','rpids[]')">ɾ����ѡ</button>&nbsp;
		<?endif;?>
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,'topic','list')?>';">����</button>
	</center>
</form>
</div>