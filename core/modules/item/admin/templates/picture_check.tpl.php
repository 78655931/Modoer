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
                <td width="150">ͼƬ</td>
				<td width="*">������˵��</td>
                <td width="80">�ߴ�/��С</td>
                <td width="80">�ϴ���Ա</td>
                <td width="150">��������/�ϴ�ʱ��</td>
            </tr>
			<?if($total):?>
			<?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td class="picthumb"><a href="<?=$val['filename']?>" target="_blank"><img src="<?=$val['thumb']?>" /></a></td>
				<td><?=$val['title']?><br /><?=$val['comments']?></td>
				<td><?=$val['width']?> �� <?=$val['height']?><br /><?=$val['size']?> Byte</td>
				<td><img src="<?=get_face($val['uid'])?>" /><br /><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].'&nbsp;'.$val['subname']?></a><br /><?=date('Y-m-d H:i', $val['addtime'])?></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="6" class="altbg1">
					<button type="button" onclick="checkbox_checked('picids[]');" class="btn2">ȫѡ</button>
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
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','picids[]')">�����ѡ</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>