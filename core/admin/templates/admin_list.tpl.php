<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">��̨�û�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">ɾ?</td>
                <td width="*">�û���</td>
                <td width="150">E-mail</td>
                <td width="120">����¼</td>
                <td width="100">��¼IP</td>
                <td width="60">��¼����</td>
                <td width="60">��Ч��</td>
                <td width="80">�༭</td>
            </tr>
            <? if($total) { ?>
            <? while($value = $list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="adminids[]" value="<?=$value['id']?>" <?if(!$admin->is_founder){?>disabled<?}?> /></td>
                <td><?=$value['adminname']?></td>
                <td><?=$value['email']?></td>
                <td><?=date('Y-m-d H:i',$value['logintime'])?></td>
                <td><?=$value['loginip']?></td>
                <td><?=$value['logincount']?></td>
                <td><?=$value['closed']?'<font color="red">��</font>':'<font color="green">��</font>'?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('adminid'=>$value['id']))?>">�༭</a></td>
            </tr>
            <? } ?>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <?if($admin->is_founder) {?>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'adminids[]');" />
            <input type="button" value=" ���� " class="btn" onclick="document.location.href='<?=cpurl($module,$act,"add")?>'" />
        </center>
        <?}?>
    </div>
</form>
</div>