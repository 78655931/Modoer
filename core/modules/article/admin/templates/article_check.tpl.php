<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">审核文章</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="*">名称</td>
                <td width="100">城市</td>
                <td width="140">类别</td>
                <td width="120">作者</td>
				<td width="80">att</td>
				<td width="120">发布时间</td>
                <td width="80">状态</td>
                <td width="120">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="articleids[]" value="<?=$val['articleid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><?=template_print('modoer','area',array('aid'=>$val['city_id']))?></td>
                <td><?=misc_article::category_path($val['catid'])?></td>
                <td><?=$val['author']?></td>
                <td><?=$val['att']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=lang('global_status_'.$val['status'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('articleid'=>$val['articleid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('articleids[]');" class="btn2">全选</button>
				</td>
				<td colspan="10" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="10">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','checkup','articleids[]')">通过审核</button>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','articleids[]')">删除所选</button>
        <?endif;?>
	</center>
</form>
</div>