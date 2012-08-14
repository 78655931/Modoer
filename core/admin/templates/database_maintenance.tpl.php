<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">数据库维护</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20"><input type="checkbox" name="maintenance[]" value="check" checked="check" /></td>
                <td width="*">检查表</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="repair" checked="check" /></td>
                <td>修复表</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="analyze" checked="check" /></td>
                <td>分析表</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="optimize" checked="check" /></td>
                <td>优化表</td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value=" 提交 " class="btn" /></center>
    </div>
</form>
</div>