<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">��Ʒ����</div>
        <ul class="cptab">
            <?php foreach($status_name as $k => $v):?>
            <li<?if($status==$k)echo' class="selected";'?>><a href="<?=cpurl($module,$act,'list',array('status'=>$k))?>"><?=$v?>(<?=(int)$status_group[$k]?>)</a></li>
            <?php endforeach;?>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="30">ѡ</td>
                <td width="60">���</td>
                <td width="100">��Ա</td>
				<td width="*">����</td>
				<td width="90">�һ�����</td>
				<td width="90">���ѽ��</td>
                <td width="110">�һ�ʱ��</td>
                <td width="100">����</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="exchangeids[]" value="<?=$val['exchangeid']?>" /></td>
                <td><?=$val['exchangeid']?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><a href="<?=url("exchange/gift/id/$val[giftid]")?>" target="_blank"><?=$val['giftname']?></a></td>
                <td><?=$val['number']?></td>
                <td><?=$val['price']*$val['number']?></td>
                <td><?=date('Y-m-d H:i',$val['exchangetime'])?></td>
                <td><a href="<?=cpurl($module,$act,'detail',array('exchangeid'=>$val['exchangeid']))?>">�鿴/����</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="6" class="altbg1">
					<button type="button" onclick="checkbox_checked('exchangeids[]');" class="btn2">ȫѡ</button>
                    <input type="checkbox" name="member_point" id="member_point" value="1"<?if($status!=4)echo' checked="checked"';?> /><label for="member_point">�˻��û��һ����</label>
                    <input type="checkbox" name="gift_num" id="gift_num" value="1"<?if($status!=4)echo' checked="checked"';?> /><label for="gift_num">��ԭ��Ʒ���Ͷһ�����</label>
				</td>
				<td colspan="4" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="10">������Ϣ��</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','exchangeids[]')">ɾ����ѡ</button>
        <?endif;?>
	</center>
</form>
</div>