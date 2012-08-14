<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">广告位管理</div>
        <ul class="cptab">
            <li<?=$_GET['enabled']=='Y'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'Y'))?>">启用</a></li>
            <li<?=$_GET['enabled']=='N'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'N'))?>">停用</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0"  trmouse="Y">
            <tr class="altbg1">
				<td width="25">选</td>
                <td width="200">名称</td>
				<td width="350">描述</td>
				<td width="*">操作</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="apids[]" value="<?=$val['apid']?>" /></td>
                <td><?=$val['name']?></td>
                <td><?=$val['des']?></td>
                <td>
					<a href="<?=cpurl($module,$act,'edit',array('apid'=>$val['apid']))?>">编辑</a>&nbsp;
					<a href="<?=cpurl($module,'list','',array('apid'=>$val['apid']))?>">管理广告</a>&nbsp;
					<a href="<?=cpurl($module,$act,'code',array('apid'=>$val['apid']))?>">复制调用代码</a>&nbsp;
				</td>
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
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'" />增加广告位</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','apids[]');">删除所选</button>&nbsp;
    </center>
</form>
</div>