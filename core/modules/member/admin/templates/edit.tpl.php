<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>&">
    <input type="hidden" name="uid" value="<?=$uid?>" />
    <div class="space">
        <div class="subtitle">�û������޸�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg2"><td colspan="2"><strong>������Ϣ</strong></td></tr>
            <tr>
                <td class="altbg1" width="20%">�û���:</td>
                <td><?=$member['username']?></td>
            </tr>
            <tr>
                <td class="altbg1">�û���:</td>
                <td><select name="memberupdate[groupid]">
                    <?=$group_opt?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">E-mail:</td>
                <td><input type="text" name="memberupdate[email]" value="<?=$member['email']?>" class="txtbox2" /></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>�������</strong></td></tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><input type="text" name="memberupdate[point]" value="<?=$member['point']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1">���:</td>
                <td><input type="text" name="memberupdate[coin]" value="<?=$member['coin']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1">�ʻ�:</td>
                <td><input type="text" name="memberupdate[flowers]" value="<?=$member['flowers']?>" class="txtbox4" /></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>�����޸�</strong></td></tr>
            <tr>
                <td class="altbg1">������:</td>
                <td><input type="password" name="memberupdate[password]" class="txtbox2" />&nbsp;&nbsp;���޸ģ�������</td>
            </tr>
            <tr>
                <td class="altbg1">�ٴ���������:</td>
                <td><input type="password" name="memberupdate[repassword]" class="txtbox2" /></td>
            </tr>
        </table>
        <center>
            <input type="submit" name="dosubmit" value=" �ύ " class="btn" />
            <input type="button" value=" ���� " onclick="history.go(-1);" class="btn" />
        </center>
    </div>
</form>
</div>