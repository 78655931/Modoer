<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="search" />
    <div class="space">
        <div class="subtitle">����Ϣ֪ͨ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="300"><strong>�û���:</strong>����û����ö��š�,���ָ�</td>
                <td width="*"><input type="text" name="username" class="txtbox2" value="<?=$_GET['username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�û���:</strong>ѡ����������������û���</td>
                <td><select name="groupid" rows="5">
                    <option value="" style="color:#CC0000;">==ȫ���û���==</option>
                    <?=form_member_usergroup($_GET['groupid'])?>
                </select>&nbsp;<button type="submit" name="dosubmit" value="yes" class="btn2">ɸѡ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" action="<?=cpurl($module,$act,'send')?>">
    <div class="space">
        <div class="subtitle">����Ҫ��Ļ�Ա: <?=$total?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($total):?>
            <tr>
                <td class="altbg1" width="300"><strong>����:</strong></td>
                <td width="*"><input type="text" name="title" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����:</strong></td>
                <td><textarea name="message" style="height:100px;width:500px;"></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������������:</strong></td>
                <td><input type="text" name="offset" value="100" /></td>
            </tr>
            <?else:?>
            <tr>
                <td colspan="2">��Ǹ��û���ҵ��κ���Ϣ��</td>
            </tr>
            <?endif;?>
        </table>
        <?if($total):?>
        <center>
            <input type="hidden" name="username" value="<?=$_GET['username']?>" />
            <input type="hidden" name="groupid" value="<?=$_GET['groupid']?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn">��ʼ����</button>
        </center>
        <?endif;?>
    </div>
</form>
<?endif;?>
</div>