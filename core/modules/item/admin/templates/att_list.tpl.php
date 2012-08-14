<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>">
<input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
    <div class="space">
        <div class="subtitle">属性管理</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'att_cat','edit',array('catid'=>$catid))?>" onfocus="this.blur()">编辑属性组</a></li>
            <li class="selected"><a href="<?=cpurl($module,'att_list','',array('catid'=>$catid))?>" onfocus="this.blur()">值管理</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>值名称：</strong>向属性组[<?=$cat['name']?>]添加所属的属性值，一次性添加多个值，请使用"|"分隔。</td>
                <td width="40%"><center><input type="text" class="txtbox" name="names" /></center></td>
                <td class="altbg1" width="15%"><center><button type="submit" name="dosubmit" value="yes" class="btn2" />添加属性值</button></center></td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">属性值列表[<?=$cat['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">选?</td>
                <td width="50">ID</td>
                <td width="60">排序</td>
                <td width="210">名称</td>
                <td width="*">图标(图片请放在目录 /static/images/att 内)</td>
            </tr>
            <?if($list) {
            while($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="attids[]" value="<?=$val['attid']?>" /></td>
                <td><?=$val['attid']?></td>
                <td><input type="text" class="txtbox5" name="att_list[<?=$val['attid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="att_list[<?=$val['attid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><input type="text" class="txtbox4" name="att_list[<?=$val['attid']?>][icon]" value="<?=$val['icon']?>" /></td>
            </tr>
            <?}?>
            <tr class="altbg1"><td colspan="5">
            <button type="button" onclick="checkbox_checked('attids[]');" class="btn2">全选</button>
            <?} else {?>
            <tr><td colspan="5">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <?if($list){?>
            <button type="submit" class="btn" />更新提交</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','attids[]')">删除所选</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'att_cat')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>