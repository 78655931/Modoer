<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>&">
    <div class="space">
        <div class="subtitle">�û��ȼ�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="10%">[<a href="javascript:;" onclick="allchecked();">ѡ</a>] ɾ?</td>
                <td width="20%">����ͷ��</td>
                <td width="70%">���ָ���</td>
            </tr>
            <? if($userranks) { foreach($userranks as $rank) { ?>
            <tr>
                <td><input type="checkbox" name="rankid[]" value="<?=$rank['rankid']?>" /></td>
                <td><input type="text" name="userrank[<?=$rank['rankid']?>][rankname]" value="<?=$rank['rankname']?>" class="txtbox3" /></td>
                <td><input type="text" name="userrank[<?=$rank['rankid']?>][point]" value="<?=$rank['point']?>" class="txtbox4" /></td>
            </tr>
            <?}}?>
            <tr>
                <td class="altbg1">����:</td>
                <td class="altbg1"><input type="text" name="newrank[rankname]" class="txtbox3" /></td>
                <td class="altbg1"><input type="text" name="newrank[point]" class="txtbox4" /></td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value=" �ύ " class="btn" /></center>
    </div>
</form>
</div>