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
                <td width="25">ѡ?</td>
                <td width="*">��������</td>
				<td width="120">��������</td>
				<td width="120">�ύʱ��</td>
                <td width="100">�Ǽǻ�Ա</td>
				<td width="100">����</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="sids[]" value="<?=$val['sid']?>" /></td>
                <td><?=$val['name']?>&nbsp;<?=$val['subname']?><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><?=display('item:category',"catid/$val[pid]")?>&gt;<?=display('item:category',"catid/$val[catid]")?></td>
				<td><?=date('Y-m-d H:i', $val['addtime'])?></td>
                <td><?=$val['creator']?$val['creator']:'<span class="font_2">[��̨���]</span>'?></td>
				<td><a href="<?=cpurl($module,'subject_edit','',array('pid'=>$pid, 'sid'=>$val['sid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="6" class="altbg1">
					<button type="button" onclick="checkbox_checked('sids[]');" class="btn2">ȫѡ</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','sids[]')">�����ѡ</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>