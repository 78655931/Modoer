<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
<div class="space">
    <div class="subtitle">更新缓存</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="setting" /></td>
            <td>更新系统配置缓存</td>
        </tr>
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="template" /></td>
            <td>更新网站模版缓存</td>
        </tr>
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="datacall" /></td>
            <td>更新数据调用全部缓存</td>
        </tr>
        <tr class="altbg1">
            <td colspan="2">
                <input type="button" value="全选" onclick="checkbox_checked('cache[]');" class="btn2" />
            </td>
        </tr>
    </table>
    <center><input type="submit" name="dosubmit" value="更新缓存" class="btn" /></center>
</div>
</form>