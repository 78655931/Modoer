<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
<div class="space">
    <div class="subtitle">���»���</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="setting" /></td>
            <td>����ϵͳ���û���</td>
        </tr>
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="template" /></td>
            <td>������վģ�滺��</td>
        </tr>
        <tr>
            <td width="20"><input type="checkbox" name="cache[]" value="datacall" /></td>
            <td>�������ݵ���ȫ������</td>
        </tr>
        <tr class="altbg1">
            <td colspan="2">
                <input type="button" value="ȫѡ" onclick="checkbox_checked('cache[]');" class="btn2" />
            </td>
        </tr>
    </table>
    <center><input type="submit" name="dosubmit" value="���»���" class="btn" /></center>
</div>
</form>