<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'listorder')?>">
    <div class="space">
        <div class="subtitle">分类管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">ID</td>
                <td width="45">启用</td>
                <td width="80">排序</td>
                <td width="150">名称</td>
                <td width="*">操作</td>
            </tr>
            <?if(!empty($catlist)) { 
            foreach($catlist as $val) {?>
            <tr>
                <td><?=$val['catid']?></td>
                <td><input type="checkbox" name="category[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><?=$val['name']?></td>
                <td><a href="<?=cpurl($module,'category_edit','config',array('catid'=>$val['catid']))?>">参数设置</a>&nbsp;
                <a href="<?=cpurl($module,'category_edit','subcat',array('catid'=>$val['catid']))?>">子分类管理</a>&nbsp;
                <a href="<?=cpurl($module,'category_edit','delete',array('catid'=>$val['catid']))?>" onclick="return confirm('您确定删除这个主分类吗？请确定这个主分类下无任何子分类存在，否则请先删除子分类。');">删除主分类</a></td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <?}?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_add')?>'">增加分类</button>&nbsp;
            <?if($catlist) {?>
            <button type="submit" name="dosubmit" value="yes" class="btn">更新排序</button>
            <?}?>
        </center>
    </div>
</form>
</div>