<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <input type="hidden" name="uid" value="<?=$detail['uid']?>" />
    <div class="space">
        <div class="subtitle">�û�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg2"><td colspan="2"><strong>������Ϣ</strong></td></tr>
            <tr>
                <td class="altbg1" width="150">UID:</td>
                <td width="*"><?=$detail['uid']?></td>
            </tr>
            <tr>
                <td class="altbg1" width="150">�û���:</td>
                <td width="*"><?=$detail['username']?></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>���û���</strong></td></tr>
            <tr>
                <td class="altbg1">point(�ȼ�����):</td>
                <td><input type="text" name="point[point]" value="<?=$detail['point']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1">rmb(�ֽ�):</td>
                <td><input type="text" name="point[rmb]" value="<?=$detail['rmb']?>" class="txtbox3" /></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>��չ����</strong></td></tr>
            <?foreach(array('point1','point2','point3','point4','point5','point6') as $key):?>
            <tr>
                <td class="altbg1"><?=$key?><?if($point_group[$key]['enabled'])echo"({$point_group[$key][name]})";?>:</td>
                <td><input type="text" name="point[<?=$key?>]" value="<?=$detail[$key]?>" class="txtbox3" /></td>
            </tr>
            <?endforeach;?>
        </table>
    </div>
    <center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="dosubmit" value="yes" class="btn">�ύ</button>&nbsp;
        <button type="button" onclick="history.go(-1);" class="btn">����</button>
    </center>
</form>
</div>