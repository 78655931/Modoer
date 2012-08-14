<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">标签管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="50">标签ID</td>
				<td width="*">名称</td>
				<td width="100">数量</td>
				<td width="110">状态</td>
                <td width="110">最后增加时间</td>
				<td width="100">操作</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="tagids[]" value="<?=$val['tagid']?>" /></td>
                <td><?=$val['tagid']?></td>
				<td><a href="<?=url("item/tag/tagid/$val[tagid]")?>" target="_blank"><?=$val['tagname']?></a></td>
				<td><?=$val['total']?></td>
                <td><?=$val['closed']?'<span class="font_1">关闭</span>':'<span class="font_3">正常</span>'?></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
				<td><a href="<?=cpurl($module,$act,'edit',array('tagid'=>$val['tagid']))?>">编辑</a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('tagids[]');" class="btn2">全选</button>
				</td>
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
        <input type="hidden" name="closed" value="0" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','tagids[]')">删除所选</button>&nbsp;
        <button type="button" class="btn" onclick="submit_form('myform','op','close','closed',1,'tagids[]')">关闭所选</button>&nbsp;
        <button type="button" class="btn" onclick="submit_form('myform','op','close','closed',0,'tagids[]')">启用所选</button>&nbsp;
	</center>
	<?endif;?>
</form>
</div>