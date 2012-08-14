<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>
        1. 新增词语过滤时，“替换为”处填写“<span class="font_1">{BLOCK}</span>”则为禁止提交，填写“<span class="font_1">{CHECK}</span>”则为手工审核，其他为替换值。<br />
        2. 使用过多的词语过滤项，将会影响服务器效率。
    </td></tr>
    </table>
</div>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">词语过滤</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">&nbsp;<a href="javascript:checkbox_checked('ids[]');">选</a></td>
                <td width="250">不良词语</td>
                <td width="250">替换为</td>
                <td width="*">操作者</td>
            </tr>
            <?php if($total > 0) {?>
            <?php while($val = $list->fetch_array()) {?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><input type="text" name="words[<?=$val['id']?>][keyword]" class="txtbox3 width" value="<?=$val['keyword']?>" /></td>
                <td><input type="text" name="words[<?=$val['id']?>][expression]" class="txtbox3 width" value="<?=$val['expression']?>" /></td>
                <td><?=$val['admin']?></td>
            </tr>
            <? } $list->free_result(); ?>
            <? } else {?>
            <tr>
                <td colspan="4">暂无信息。</td>
            </tr>
            <?}?>
            <tr class="altbg1">
                <td>增加:</td>
                <td><input type="text" name="newword[keyword]" class="txtbox3 width" /></td>
                <td><input type="text" name="newword[expression]" class="txtbox3 width" /></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <?=$multipage?>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <? if($total) : ?>
            <input type="button" name="dosubmit" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'ids[]');" />
            <? endif; ?>
            <input type="button" name="dosubmit" value="提交更新" class="btn" onclick="easy_submit('myform', 'update', null);" />
        </center>
    </div>
</form>
</div>