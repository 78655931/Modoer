<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>">
    <div class="space">
        <div class="subtitle">查看/处理兑换信息</div>
            <table class="maintable" border="0" cellspacing="0" cellpadding="0">
                <tr><td colspan="2" class="altbg2"><strong>会员兑换信息</strong></td></tr>
                <tr><td width="80" class="altbg1">兑换会员：</td><td><a href="<?=url("space/index/uid/$val[$uid]")?>" target="_blank"><?=$detail['username']?></a></td></tr>
                <tr><td class="altbg1">礼品名称：</td><td><?=$detail['giftname']?></td></tr>
                <tr><td class="altbg1">兑换数量：</td><td><?=$detail['number']?></td></tr>
                <tr><td class="altbg1">兑换总价：</td><td><?=$detail['price']*$detail['number']?></td></tr>
                <tr><td class="altbg1" valign="top">联系方式：</td><td colspan="2"><?=$detail['contact']?></td></tr>
                <tr><td colspan="2" class="altbg2"><strong>礼品库存信息</strong>&nbsp;[对应编号]</td></tr>
                <tr><td class="altbg1">礼品名称：</td><td><a href="<?=url("exchange/gift/id/$gift[giftid]")?>" target="_blank"><?=$gift['name']?></a></td></tr>
                <tr><td class="altbg1">当前库存：</td><td><?=$gift['num']?>&nbsp;&nbsp;(已减去兑换总量)</td></tr>
                <tr><td class="altbg1">礼品单价：</td><td><?=$gift['price']?></td></tr>
                <tr><td class="altbg1">礼品状态：</td><td><?=$gift['available']?'可用':'<span class="font_1">不可用</span>'?></td></tr>
                <tr><td colspan="2" class="altbg2"><strong>兑换处理</strong></td></tr>
                <?if($detail['checker']):?>
                <tr>
                    <td class="altbg1">处理员：</td>
                    <td><?=$detail['checker']?></td>
                </tr>
                <?endif;?>
                <tr>
                    <td class="altbg1">处理操作：</td>
                    <td>
                        <?=form_select('status', array('==处理操作==','待处理','处理中','已发货','已退款'), $detail['status'])?>&nbsp;&nbsp;
                        选择<span class="font_1">“已退款”</span>将返还兑换总价给会员，返还后将无法再次扣除。
                    </td>
                </tr>
                <tr>
                    <td class="altbg1">处理说明：</td>
                    <td><input type="text" name="des" value="<?=$detail['status_extra']?>" class="txtbox" />&nbsp;&nbsp;已发货时，可写货单号；退款时，可写退款说明</td>
                </tr>
				<?if($serial):?>
				<tr>
					<td class="altbg1">卡密信息：</td>
					<td>
						<?while ($val=$serial->fetch_array()):?>
							<?=$val['serial']?><br />
						<?endwhile;?>
					</td>
				</tr>
				<?endif;?>
            </table>
        </div>
    </div>
    <center>
        <input type="hidden" name="exchangeid" value="<?=$exchangeid?>" />
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('admincp_return'),'btn')?>
    </center>
</form>
</div>