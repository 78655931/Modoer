<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">���ּ�¼</div>
        <ul class="cptab">
            <li<?=$type=='out'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'out'))?>">֧����¼</a></li>
            <li<?=$type=='in'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'in'))?>">�����¼</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <?if($type=='out'):?>
                <td width="150">֧���û�</td>
                <?else:?>
                <td width="150">�����û�</td>
                <?endif;?>
                <td width="120">���</td>
                <td width="120">ʱ��</td>
                <td width="*">˵��</td>
            </tr>
            <? if($total):?>
            <? while($val=$list->fetch_array()):?>
            <tr>
                <?if($type=='out'):?>
                <td><a href="<?=url("space/index/uid/$val[out_uid]")?>" target="_blank"><?=$val['out_username']?></a></td>
                <td><?=$val['out_value']?></td>
                <?else:?>
                <td><a href="<?=url("space/index/uid/$val[in_uid]")?>" target="_blank"><?=$val['in_username']?></a></td>
                <td><?=$val['in_value']?></td>
                <?endif;?>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=$val['des']?></td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr>
                <td colspan="6">������Ϣ��</td>
            </tr>
            <? endif; ?>
        </table>
        <div><?=$multipage?></div>
    </div>
</form>
</div>