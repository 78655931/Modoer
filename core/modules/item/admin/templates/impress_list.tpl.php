<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">印象管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg2"><th colspan="9">
                <ul class="subtab">
                    <?foreach($_G['loader']->variable('category',MOD_FLAG) as $key => $val) { if($val['pid']) continue; ?>
                    <li<?=$pid==$key?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('pid'=>$key))?>"><?=$val['name']?></a></li>
                    <?}?>
                </ul>
            </th></tr>
            <tr class="altbg1">
                <td width="25">选?</td>
                <td width="120">名称</td>
                <td width="80">数量</td>
				<td width="*">主题</td>
                <td width="110">最后更新</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><?=$val['title']?></td>
                <td><?=$val['total']?></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].'&nbsp;'.$val['subname']?></a></td>
                <td><?=date('Y-m-d H:i', $val['dateline'])?></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="2"><button type="button" onclick="checkbox_checked('ids[]');" class="btn2">全选</button></td>
				<td colspan="4" style="text-align:right;"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="5">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','ids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>