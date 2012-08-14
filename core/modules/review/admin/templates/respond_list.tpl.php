<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">回应信息管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
				<td width="180">点评标题</td>
				<td width="80">回应会员</td>
                <td width="*">回应内容</td>
                <td width="100">IP</td>
				<td width="110">回应时间</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="respondids[]" value="<?=$val['respondid']?>" /></td>
				<td><a href="<?=url("review/detail/id/$val[rid]")?>" target="_blank"><?=$val['title']?$val['title']:'N/A'?></a></td>
                <td><a href="<?=url("item/space/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><?=$val['content']?></td>
				<td><?=$val['ip']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('respondids[]');" class="btn2">全选</button>
					<input type="checkbox" name="delete_point" id="delete_point" value="1" /><label for="delete_point">删除回应同时减少作者积分</label>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','respondids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>