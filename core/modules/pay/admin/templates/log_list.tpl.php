<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
	<div class="space">
		<div class="subtitle">在线充值订单记录</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr class="altbg2">
                <th colspan="10">
                    <ul class="subtab">
                        <li<?=!$status?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>0))?>">未付款订单</a></li>
                        <li<?=$status==1?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">已支付订单</a></li>
                        <li<?=$status==2?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>2))?>">已过期订单</a></li>
                    </ul>
                </th>
            </tr>
            <tr class="altbg1">
				<td width="20">删?</td>
                <td width="120">订单号</td>
				<td width="*">充值会员</td>
				<td width="100">支付金额</td>
                <td width="100">兑换金额</td>
                <td width="80">订单状态</td>
				<td width="120">订单创建时间</td>
                <?if($status==1):?>
                <td width="120">交易完成时间</td>
                <?endif;?>
                <!--{/if}-->
                <td width="100">IP记录</td>
			</tr>
			<?if($total){while($row=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="orderids[]" value="<?=$row['orderid']?>" /></td>
                <td><?=$row['orderid']?></td>
                <td><a href="<?=url("space/index/uid/$row[uid]")?>"><?=$row['username']?></a></td>
                <td><?=$row['price']?></td>
                <td><?=$row['point']?>&nbsp;<?=$row['cztype']=='rmb'?'现金':template_print('member','point',array('point'=>$row['cztype']))?></td>
                <td><?=lang('pay_status_'.$row['status'])?></td>
                <td><?=date('Y-m-d H:i',$row['dateline'])?></td>
                <?if($status==1):?>
                <td><?=date('Y-m-d H:i',$row['exchangetime'])?></td>
                <?endif;?>
                <td><?=$row['ip']?></td>
            </tr>
            <?} $list->free_result();?>
			<tr>
                <td colspan="10" class="altbg1">
                    <button type="button" class="btn2" onclick="checkbox_checked('orderids[]');">全选</button>&nbsp;
                    <?if($status<=1):?><span class="font_2">谨慎删除尚未完成支付和已支付的订单。</span><?endif;?>
                </td>
            </tr>
            <?} else {?>
			<tr><td colspan="10">暂无信息。</td></tr>
            <?}?>
		</table>
    </div>
    <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
	<?if($total) {?>
    <center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','orderids[]')">删除所选</button>&nbsp;
    </center>
    <?}?>
</form>
</div>