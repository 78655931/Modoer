<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">���ݿ�ά��</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20"><input type="checkbox" name="maintenance[]" value="check" checked="check" /></td>
                <td width="*">����</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="repair" checked="check" /></td>
                <td>�޸���</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="analyze" checked="check" /></td>
                <td>������</td>
            </tr>
            <tr>
                <td><input type="checkbox" name="maintenance[]" value="optimize" checked="check" /></td>
                <td>�Ż���</td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value=" �ύ " class="btn" /></center>
    </div>
</form>
</div>