<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>&">
    <input type="hidden" name="uid" value="<?=$uid?>" />
    <div class="space">
        <div class="subtitle">用户资料修改</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg2"><td colspan="2"><strong>基本信息</strong></td></tr>
            <tr>
                <td class="altbg1" width="20%">用户名:</td>
                <td><?=$member['username']?></td>
            </tr>
            <tr>
                <td class="altbg1">用户组:</td>
                <td><select name="memberupdate[groupid]">
                    <?=$group_opt?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">E-mail:</td>
                <td><input type="text" name="memberupdate[email]" value="<?=$member['email']?>" class="txtbox2" /></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>积分情况</strong></td></tr>
            <tr>
                <td class="altbg1">积分:</td>
                <td><input type="text" name="memberupdate[point]" value="<?=$member['point']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1">金币:</td>
                <td><input type="text" name="memberupdate[coin]" value="<?=$member['coin']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1">鲜花:</td>
                <td><input type="text" name="memberupdate[flowers]" value="<?=$member['flowers']?>" class="txtbox4" /></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>密码修改</strong></td></tr>
            <tr>
                <td class="altbg1">新密码:</td>
                <td><input type="password" name="memberupdate[password]" class="txtbox2" />&nbsp;&nbsp;不修改，请留空</td>
            </tr>
            <tr>
                <td class="altbg1">再次输入密码:</td>
                <td><input type="password" name="memberupdate[repassword]" class="txtbox2" /></td>
            </tr>
        </table>
        <center>
            <input type="submit" name="dosubmit" value=" 提交 " class="btn" />
            <input type="button" value=" 返回 " onclick="history.go(-1);" class="btn" />
        </center>
    </div>
</form>
</div>