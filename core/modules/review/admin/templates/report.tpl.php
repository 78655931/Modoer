<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">点评举报</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg2"><th colspan="12">
                <ul class="subtab">
                    <li<?=!$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act)?>">未处理</a></li>
                    <li<?=$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('disposal'=>1))?>">已处理</a></li>
                </ul>
            </th></tr>
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="180">点评标题</td>
				<td width="100">举报用户</td>
				<td width="65">违规类型</td>
				<td width="*">补充内容</td>
				<td width="120">提交时间</td>
				<td width="50">操作</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="reportids[]" value="<?=$val['reportid']?>" /></td>
                <td><a href="<?=url("review/detail/id/$val[id]")?>#review" target="_blank"><?=$val['title']?$val['title']:'N/A'?></a></td>
				<td><?=$val['username']?><?=!$val['uid']?'(游客)':''?>(<?=$val['email']?>)</td>
				<td><?=lang('review_report_sort_' . $val['sort'])?></td>
				<td><?=$val['reportcontent']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
				<td><a href="<?=cpurl($module,'review','report',array('reportid'=>$val['reportid']))?>"><?=$val['disposal']?'已处理':'未处理'?></a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('reportids[]');" class="btn2">全选</button>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','reportids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>