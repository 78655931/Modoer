<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">ͼƬ�ֻ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="250">ͼƬ����</td>
                <td width="100">ͼƬ����</td>
                <td width="*">����</td>
            </tr>
            <?php if($groups):?>
            <?php foreach($groups as $key => $val) :?>
            <tr>
                <td><?=$key?></td>
                <td><?=$val?></td>
                <td><a href="<?=cpurl($module,$act,'list',array('gn'=>$key))?>">����</a></td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr><td colspan="4">������Ϣ��</td></tr>
            <?php endif; ?>
        </table>
        <center>
            <input type="button" value="�½�ͼƬ�ֻ�" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>';" />
        </center>
    </div>
</form>
</div>