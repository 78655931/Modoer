<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">会员卡申请</div>
        <ul class="cptab">
            <?foreach($status_array as $k => $v) :?>
            <li<?if($_GET['status']==$k)echo' class="selected"';?>><a href="<?=cpurl($module,$act,'list',array('status'=>$k))?>"><?=$v?>(<?=(int)$status_group[$k]?>)</a></li>
            <?endforeach;?>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="20">删?</td>
                <td width="120">会员</td>
                <td width="120">联系人</td>
                <td width="100">联系电话</td>
                <td width="80">数量</td>
                <td width="80"><?if($MOD['pointgroup'])echo" {$point_group[$MOD['pointgroup']][name]}";?></td>
                <td width="110">申请时间</td>
                <td width="70">状态</td>
                <td width="70">操作</td>
            </tr>
            <?if($total) { while($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="applyids[]" value="<?=$val['applyid']?>" /></td>
                <td><a href="<?=url('space/index/uid/'.$val['uid'])?>"><?=$val['username']?></a></td>
                <td><?=$val['linkman']?></td>
                <td><?=$val['mobile']?></td>
                <td><?=$val['num']?></td>
                <td><?=$val['coin']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=$status_array[$val['status']]?></td>
                <td><a href="<?=cpurl($module,$act,'checkup',array('applyid'=>$val['applyid']))?>">处理</a></td>
            </tr>
            <? } ?>
            <tr>
                <td colspan="9" class="altbg1">
                    <button type="button" class="btn2" onclick="checkbox_checked('applyids[]');">全选</button>&nbsp;
                    <?if(!$_GET['status']):?><span class="font_1">删除等待处理的信息，将不会返还金币给申请会员</span><?endif;?>
                </td>
            </tr>
            <? } else { ?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="" />
            <button type="button" class="btn" onclick="easy_submit('myform','delete','applyids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
</div>