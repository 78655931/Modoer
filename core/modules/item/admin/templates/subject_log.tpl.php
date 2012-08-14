<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">主题补充</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg2"><th colspan="12">
                <ul class="subtab">
                    <li<?=!$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act)?>">未处理</a></li>
                    <li<?=$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('disposal'=>1))?>">已处理</a></li>
                </ul>
            </th></tr>
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="150">主题名称</td>
				<td width="80">提交用户</td>
				<td width="60">补充类型</td>
				<td width="*">补充内容</td>
				<td width="110">提交时间</td>
				<td width="50">操作</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="upids[]" value="<?=$val['upid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name']?><?=$val['subname']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><?=$val['username']?><?=!$val['uid']?'(游客)':''?></td>
				<td><?=$val['ismappoint']?'地图报错':'补充信息'?></td>
				<td><?=$val['upcontent']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
				<td><a href="<?=cpurl($module,'subject_edit','log',array('upid'=>$val['upid']))?>"><?=$val['disposal']?'已处理':'未处理'?></a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('upids[]');" class="btn2">全选</button>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','upids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>