<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
	<div class="space">
		<div class="subtitle">���߳�ֵ������¼</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr class="altbg2">
                <th colspan="10">
                    <ul class="subtab">
                        <li<?=!$status?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>0))?>">δ�����</a></li>
                        <li<?=$status==1?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">��֧������</a></li>
                        <li<?=$status==2?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>2))?>">�ѹ��ڶ���</a></li>
                    </ul>
                </th>
            </tr>
            <tr class="altbg1">
				<td width="20">ɾ?</td>
                <td width="120">������</td>
				<td width="*">��ֵ��Ա</td>
				<td width="100">֧�����</td>
                <td width="100">�һ����</td>
                <td width="80">����״̬</td>
				<td width="120">��������ʱ��</td>
                <?if($status==1):?>
                <td width="120">�������ʱ��</td>
                <?endif;?>
                <!--{/if}-->
                <td width="100">IP��¼</td>
			</tr>
			<?if($total){while($row=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="orderids[]" value="<?=$row['orderid']?>" /></td>
                <td><?=$row['orderid']?></td>
                <td><a href="<?=url("space/index/uid/$row[uid]")?>"><?=$row['username']?></a></td>
                <td><?=$row['price']?></td>
                <td><?=$row['point']?>&nbsp;<?=$row['cztype']=='rmb'?'�ֽ�':template_print('member','point',array('point'=>$row['cztype']))?></td>
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
                    <button type="button" class="btn2" onclick="checkbox_checked('orderids[]');">ȫѡ</button>&nbsp;
                    <?if($status<=1):?><span class="font_2">����ɾ����δ���֧������֧���Ķ�����</span><?endif;?>
                </td>
            </tr>
            <?} else {?>
			<tr><td colspan="10">������Ϣ��</td></tr>
            <?}?>
		</table>
    </div>
    <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
	<?if($total) {?>
    <center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','orderids[]')">ɾ����ѡ</button>&nbsp;
    </center>
    <?}?>
</form>
</div>