<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">优惠券审核</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">删?</td>
                <td width="150">名称</td>
                <td width="*">优惠说明</td>
                <td width="150">所属主题</td>
                <td width="100">上传会员</td>
                <td width="110">发布时间</td>
                <td width="60">操作</td>
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
                <td><a href="<?=cpurl($module,$act,'edit',array('couponid'=>$val['couponid']))?>">编辑</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('couponids[]');">全选</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="checkup" />
            <button type="button" class="btn" onclick="easy_submit('myform','checkup','couponids[]')">审核随选</button>
            <button type="button" class="btn" onclick="easy_submit('myform','delete','couponids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
</div>