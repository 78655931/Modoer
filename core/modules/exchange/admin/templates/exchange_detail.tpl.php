<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>">
    <div class="space">
        <div class="subtitle">�鿴/����һ���Ϣ</div>
            <table class="maintable" border="0" cellspacing="0" cellpadding="0">
                <tr><td colspan="2" class="altbg2"><strong>��Ա�һ���Ϣ</strong></td></tr>
                <tr><td width="80" class="altbg1">�һ���Ա��</td><td><a href="<?=url("space/index/uid/$val[$uid]")?>" target="_blank"><?=$detail['username']?></a></td></tr>
                <tr><td class="altbg1">��Ʒ���ƣ�</td><td><?=$detail['giftname']?></td></tr>
                <tr><td class="altbg1">�һ�������</td><td><?=$detail['number']?></td></tr>
                <tr><td class="altbg1">�һ��ܼۣ�</td><td><?=$detail['price']*$detail['number']?></td></tr>
                <tr><td class="altbg1" valign="top">��ϵ��ʽ��</td><td colspan="2"><?=$detail['contact']?></td></tr>
                <tr><td colspan="2" class="altbg2"><strong>��Ʒ�����Ϣ</strong>&nbsp;[��Ӧ���]</td></tr>
                <tr><td class="altbg1">��Ʒ���ƣ�</td><td><a href="<?=url("exchange/gift/id/$gift[giftid]")?>" target="_blank"><?=$gift['name']?></a></td></tr>
                <tr><td class="altbg1">��ǰ��棺</td><td><?=$gift['num']?>&nbsp;&nbsp;(�Ѽ�ȥ�һ�����)</td></tr>
                <tr><td class="altbg1">��Ʒ���ۣ�</td><td><?=$gift['price']?></td></tr>
                <tr><td class="altbg1">��Ʒ״̬��</td><td><?=$gift['available']?'����':'<span class="font_1">������</span>'?></td></tr>
                <tr><td colspan="2" class="altbg2"><strong>�һ�����</strong></td></tr>
                <?if($detail['checker']):?>
                <tr>
                    <td class="altbg1">����Ա��</td>
                    <td><?=$detail['checker']?></td>
                </tr>
                <?endif;?>
                <tr>
                    <td class="altbg1">���������</td>
                    <td>
                        <?=form_select('status', array('==�������==','������','������','�ѷ���','���˿�'), $detail['status'])?>&nbsp;&nbsp;
                        ѡ��<span class="font_1">�����˿</span>�������һ��ܼ۸���Ա���������޷��ٴο۳���
                    </td>
                </tr>
                <tr>
                    <td class="altbg1">����˵����</td>
                    <td><input type="text" name="des" value="<?=$detail['status_extra']?>" class="txtbox" />&nbsp;&nbsp;�ѷ���ʱ����д�����ţ��˿�ʱ����д�˿�˵��</td>
                </tr>
				<?if($serial):?>
				<tr>
					<td class="altbg1">������Ϣ��</td>
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