<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">审核评论</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
				<td width="140">标题</td>
				<td width="80">评论者</td>
				<td width="25">评分</td>
				<td width="*">内容</td>
                <td width="70">ip</td>
				<td width="110">评论时间</td>
				<td width="50">编辑</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="cids[]" value="<?=$val[cid]?>" /></td>
                <td><?=$val['title']?></td>
                <td><?=$val['username']?></td>
                <td><?=$val['grade']?></td>
                <td><?=trimmed_title($val['content'],50,'...')?></td>
                <td><?=$val['ip']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,'comment_list','edit',array('cid'=>$val['cid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('cids[]');" class="btn2">全选</button>
				</td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="6">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','cids[]')">审核通过</button>
		<button type="button" class="btn" onclick="easy_submit('myform','delete','cids[]')">删除所选</button>
	</center>
	<?endif;?>
<?=form_end()?>
</div>