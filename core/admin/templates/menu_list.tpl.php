<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">菜单管理<?if($goto_info){?>[<?=$goto_info['title']?>]<?}?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">删?</td>
                <td width="40">菜单ID</td>
                <td width="55">排序</td>
                <td width="110">标题</td>
                <td width="*">网址</td>
                <td width="105">图标</td>
                <td width="25">停用</td>
                <td width="50">类型</td>
                <td width="120">操作</td>
            </tr>
            <?if($list) {?>
            <?foreach($list as $val) {?>
            <tr>
                <input type="hidden" name="menus[<?=$val['menuid']?>][isfolder]" value="<?=$val['isfolder']?>" />
                <td><input type="checkbox" name="menuids[]" value="<?=$val['menuid']?>" style="width:100%;" /></td>
                <td><?=$val['menuid']?></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][listorder]" class="txtbox5" value="<?=$val['listorder']?>" style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][title]" class="txtbox4" value="<?=$val['title']?>" style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][url]" class="txtbox3" value="<?if($val['isfolder']){?>N/A<?}else{?><?=$val['url']?><?}?>" <?if($val['isfolder']){?> disabled<?}?> style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][icon]" class="txtbox4" value="<?=$val['icon']?>" style="width:100%;" /></td>
                <td><input type="checkbox" name="menus[<?=$val['menuid']?>][isclosed]" value="1"<?if($val['isclosed'])echo' checked="checked"';?> /></td>
                <td><?=$val['isfolder']?'菜单组':'菜单'?></td>
                <td>
                    <?if($val['isfolder']) {?>
                        <a href="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">编辑</a>
                        <a href="<?=cpurl($module,$act,'list',array('parentid'=>$val['menuid']))?>">下级</a>
                        <a href="<?=cpurl($module,$act,'add',array('parentid'=>$val['menuid']))?>">添加下级</a>
                        <!--
                    <select id="select" name="select" onChange="selectOperation(this);">
                        <option value="">==操作==</option>
                        <option value="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">编辑菜单</option>
                        <option value="<?=cpurl($module,$act,'list',array('parentid'=>$val['menuid']))?>">查看子菜单</option>
                        <option value="<?=cpurl($module,$act,'add',array('parentid'=>$val['menuid']))?>">添加子菜单</option>
                    </select>
                    -->
                    <? } else {?>
                        <a href="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">编辑</a>
                    <? } ?>
                </td>
            </tr>
            <? } ?>
            <tr class="altbg1">
                <td colspan="2"><input type="button" value="全选" onclick="checkbox_checked('menuids[]');" class="btn2" /></td>
                <td colspan="8" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?} else {?>
                <td colspan="8">暂无信息。</td>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="parentid" value="<?=$parentid?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="更新菜单" class="btn" onclick="easy_submit('myform', 'list', null);" />
            <input type="button" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'menuids[]');" />
            <input type="button" value="增加菜单" class="btn" onclick="location.href='<?=cpurl($module, $act, 'add', array('parentid'=>$parentid))?>'" />
            <?if($parentid){?>
            <input type="button" value="返回上一层" class="btn" onclick="location.href='<?=cpurl($module, $act, 'list', array('parentid'=>$goto_parentid))?>'" />
            <?}?>
        </center>
    </div>
</form>
</div>