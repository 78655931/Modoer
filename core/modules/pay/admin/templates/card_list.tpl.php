<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
	<div class="space">
		<div class="subtitle">��ֵ������</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr class="altbg2">
                <th colspan="10">
                    <ul class="subtab">
                        <li<?=$status==1?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">����</a></li>
                        <li<?=$status==2?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>2))?>">��ʹ��</a></li>
                        <li<?=$status==3?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>3))?>">ʧЧ</a></li>
                    </ul>
                </th>
            </tr>
            <tr class="altbg1">
				<td width="20">ɾ?</td>
                <td width="140">����</td>
				<td width="140">����</td>
                <td width="80">����</td>
				<td width="60">��ֵ</td>
                <td width="80">��Ч��</td>
                <td width="120">��������</td>
				<td width="60">״̬</td>
                <?if($status==2):?>
                <td width="120">��ֵ��Ա</td>
                <td width="110">��ֵʱ��</td>
                <?endif;?>
			</tr>
			<?if($total>0){while($row=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="cardids[]" value="<?=$row['cardid']?>" /></td>
                <td><?=$row['number']?></td>
                <td><?=$row['password']=='NULL'?'<span class="font_2">������</span>':$row['password']?></td>
                <td><?=$row['cztype']=='rmb'?'�ֽ�':template_print('member','point',array('point'=>$row['cztype']))?></td>
                <td><?=$row['price']?></td>
                <td><?=date('Y-m-d',$row['endtime'])?></td>
                <td><?=date('Y-m-d H:i:s',$row['dateline'])?></td>
                <td><?=$row['status']==1?'����':($row['status']==2?'��ʹ��':'ʧЧ')?></td>
                <?if($status==2):?>
                <td><a href="<?=url("space/index/uid/$row[uid]")?>" target="blank"><?=$row['username']?></a></td>
                <td><?=date('Y-m-d H:i:s',$row['usetime'])?></td>
                <?endif;?>
            </tr>
            <?} $list->free_result();?>
			<tr>
                <td colspan="10" class="altbg1">
                    <button type="button" class="btn2" onclick="checkbox_checked('cardids[]');">ȫѡ</button>&nbsp;
                    <button type="button" class="btn2" onclick="window.open('<?=str_replace('&amp;','&',cpurl($module,$act,'export',$_GET))?>');">����ȫ��</button>&nbsp;
                    <?if($status==1):?><span class="font_2">��������ɾ���ѷ��ŵ�δʹ�õĿ��ţ������ʹ����ʧЧ��</span><?endif;?>
                </td>
            </tr>
            <?} else {?>
			<tr><td colspan="10">������Ϣ��</td></tr>
            <?}?>
		</table>
    </div>
    <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
    <center>
        <?if($total) {?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','cardids[]')">ɾ����ѡ</button>&nbsp;
        <?}?>
        <button type="button" class="btn" onclick="location.href='<?=cpurl($module,$act,'create')?>';">�������ɳ�ֵ��</button>
    </center>
</form>
</div>