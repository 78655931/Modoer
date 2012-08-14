<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">审核信息</div>
        <ul class="cptab">
            <li<?=$act=='review'?' class="selected"':''?>><a href="<?=cpurl($module,'review','checklist')?>">审核点评</a></li>
            <li<?=$act=='respond'?' class="selected"':''?>><a href="<?=cpurl($module,'respond','checklist')?>">审核回应</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
				<td width="50">类型</td>
				<td width="50">作者</td>
				<td width="135">标题</td>
				<td width="*">点评内容</td>
				<td width="30">鲜花</td>
				<td width="30">回应</td>
				<td width="110">点评时间</td>
				<td width="50">编辑</td>
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
				<td><a href="<?=cpurl($module, 'review', 'edit', array('rid' => $val['rid']))?>">编辑</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('rids[]');" class="btn2">全选</button>
				</td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','rids[]')">删除所选</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','rids[]')">审核所选</button>&nbsp;
	</center>
	<?endif;?>
</form>
</div>