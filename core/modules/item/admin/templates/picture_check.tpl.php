<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">审核信息</div>
        <ul class="cptab">
            <li<?=$act=='subject_check'?' class="selected"':''?>><a href="<?=cpurl($module,'subject_check')?>">审核主题</a></li>
            <li<?=$act=='picture_check'?' class="selected"':''?>><a href="<?=cpurl($module,'picture_check')?>">审核图片</a></li>
            <li<?=$act=='guestbook_check'?' class="selected"':''?>><a href="<?=cpurl($module,'guestbook_check')?>">审核留言</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选?</td>
                <td width="150">图片</td>
				<td width="*">标题与说明</td>
                <td width="80">尺寸/大小</td>
                <td width="80">上传会员</td>
                <td width="150">主题名称/上传时间</td>
            </tr>
			<?if($total):?>
			<?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td class="picthumb"><a href="<?=$val['filename']?>" target="_blank"><img src="<?=$val['thumb']?>" /></a></td>
				<td><?=$val['title']?><br /><?=$val['comments']?></td>
				<td><?=$val['width']?> × <?=$val['height']?><br /><?=$val['size']?> Byte</td>
				<td><img src="<?=get_face($val['uid'])?>" /><br /><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].'&nbsp;'.$val['subname']?></a><br /><?=date('Y-m-d H:i', $val['addtime'])?></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="6" class="altbg1">
					<button type="button" onclick="checkbox_checked('picids[]');" class="btn2">全选</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="6">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','picids[]')">审核所选</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>