<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">链接管理</div>
        <ul class="cptab">
            <li<?=$_GET['type']=='logo'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('type'=>'logo'))?>">图片链接</a></li>
            <li<?=$_GET['type']=='char'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('type'=>'char'))?>">文字链接</a></li>
            <li<?=$op=='checklist'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'checklist')?>">未审核<?if($check_count):?>(<?=$check_count?>)<?endif;?></a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="25">选</td>
                <td width="60">排序</td>
				<td width="150">名称</td>
				<td width="180">地址</td>
                <td width="150">Logo</td>
				<td width="*">描述</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="linkids[]" value="<?=$val['linkid']?>" /></td>
                <td><input type="text" name="links[<?=$val['linkid']?>][displayorder]" value="<?=$val['displayorder']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="links[<?=$val['linkid']?>][title]" value="<?=$val['title']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="links[<?=$val['linkid']?>][link]" value="<?=$val['link']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="links[<?=$val['linkid']?>][logo]" value="<?=$val['logo']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="links[<?=$val['linkid']?>][des]" value="<?=$val['des']?>" class="txtbox5 width" /></td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="6">暂无信息</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'" />增加链接</button>&nbsp;
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">更新排序</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','linkids[]');">删除所选</button>&nbsp;
        <?endif;?>
        <?if($op=='checklist'&&$total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','linkids[]');">审核所选</button>
        <?endif;?>
    </center>
</form>
</div>