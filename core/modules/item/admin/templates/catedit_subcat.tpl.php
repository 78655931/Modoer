<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'add')?>">
<input type="hidden" name="newcat[pid]" value="<?=$_GET['catid']?>" />
    <div class="space">
        <div class="subtitle">分类管理</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,$act,'config',array('catid'=>$catid))?>" onfocus="this.blur()">参数设置</a></li>
            <li class="selected"><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$catid))?>" onfocus="this.blur()">子分类管理</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>子分类名称：</strong>向<span class="font_1"><?=$t_cat['name']?></span>添加一个子分类。一次性添加多个分类，请使用"|"分隔。</td>
                <td width="40%"><center><input type="text" class="txtbox" name="newcat[name]" /></center></td>
                <td class="altbg1" width="15%"><center><button type="submit" name="dosubmit" value="yes" class="btn2" />添加子分类</button></center></td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">子分类列表[<?=$t_cat['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">选?</td>
                <td width="40">ID</td>
                <td width="40">Attid</td>
                <td width="30">启用</td>
                <td width="80">排序</td>
                <td width="220">名称</td>
                <td width="50">数量</td>
                <td width="*">操作</td>
            </tr>
            <?if($result) {
            foreach($result as $val) { ?>
            <tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><?=$val['attid']?></td>
                <td><input type="checkbox" name="t_cat[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="t_cat[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="t_cat[<?=$val['catid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><?=$val['total']?></td>
                <td>
                    <?if($val['level']<3):?><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$val['catid']))?>">下级分类</a>&nbsp;<?endif;?>
                    <a href="<?=cpurl($module,$act,'edit',array('catid'=>$val['catid']))?>">其他设置</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('catid'=>$val['catid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>
                </td>
            </tr>
            <?}?>
            <tr class="altbg1"><td colspan="8">
            <button type="button" onclick="checkbox_checked('catids[]');" class="btn2">全选</button>&nbsp;
            使用分类排序字段排序，请现在 本模块设置=>界面设置:分类排序 中选择“按分类顺序”。</td></tr>
            <?} else {?>
            <tr><td colspan="8">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="subcat" />
            <?if($result){?>
            <button type="submit" class="btn" />更新提交</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','rebuild','catids[]')">重建统计</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_list')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>