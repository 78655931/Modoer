<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">积分记录</div>
        <ul class="cptab">
            <li<?=$type=='out'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'out'))?>">支出记录</a></li>
            <li<?=$type=='in'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'in'))?>">收入记录</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <?if($type=='out'):?>
                <td width="150">支出用户</td>
                <?else:?>
                <td width="150">收入用户</td>
                <?endif;?>
                <td width="120">金额</td>
                <td width="120">时间</td>
                <td width="*">说明</td>
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
                <td colspan="6">暂无信息。</td>
            </tr>
            <? endif; ?>
        </table>
        <div><?=$multipage?></div>
    </div>
</form>
</div>