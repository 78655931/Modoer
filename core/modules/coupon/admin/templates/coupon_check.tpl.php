<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">�Ż�ȯ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">ɾ?</td>
                <td width="150">����</td>
                <td width="*">�Ż�˵��</td>
                <td width="150">��������</td>
                <td width="100">�ϴ���Ա</td>
                <td width="110">����ʱ��</td>
                <td width="60">����</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="couponids[]" value="<?=$val['couponid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><?=$val['des']?></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].$val['subname']?></a></td>
                <td><?=$val['username']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('couponid'=>$val['couponid']))?>">�༭</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('couponids[]');">ȫѡ</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">������Ϣ��</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="checkup" />
            <button type="button" class="btn" onclick="easy_submit('myform','checkup','couponids[]')">�����ѡ</button>
            <button type="button" class="btn" onclick="easy_submit('myform','delete','couponids[]')">ɾ����ѡ</button>
            <? } ?>
        </center>
    </div>
</form>
</div>