<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>&" name="myform">
	<div class="space">
		<div class="subtitle">广告管理</div>
        <ul class="cptab">
            <li<?=$_GET['enabled']=='Y'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'Y'))?>">启用</a></li>
            <li<?=$_GET['enabled']=='N'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'N'))?>">停用</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="25">选</td>
				<td width="100">地区</td>
				<td width="80">排序</td>
                <td width="200">广告名称</td>
				<td width="200">广告位</td>
				<td width="60">类型</td>
                <td width="200">有效期</td>
				<td width="*">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
				<td><input type="checkbox" name="adids[]" value="<?=$val['adid']?>" /></td>
				<td><?=display('modoer:area',"aid/$val[city_id]")?></td>
				<td><?=form_input("adv[$val[adid]][listorder]",$val['listorder'],"txtbox5")?></td>
				<td><?=$val['adname']?></td>
				<td><?=$val['name']?></td>
				<td><?=$sorts[$val['sort']]?></td>
				<td><?=date('Y-m-d',$val['begintime'])?>～<?=$val['endtime']?date('Y-m-d',$val['endtime']):'永久'?></td>
				<td><a href="<?=cpurl($module,$act,'edit',array('adid'=>$val['adid']))?>">编辑</a></td>
            </tr>
            <?}?>
            <?else:?>
            <tr><td colspan="7">暂无信息</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'" />增加广告</button>&nbsp;
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">更新排序</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','adids[]');">删除所选</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>