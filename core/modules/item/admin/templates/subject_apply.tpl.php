<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg2"><th colspan="12">
                <ul class="subtab">
                    <li<?=!$_GET['status']?' class="current"':''?>><a href="<?=cpurl($module,$act)?>">δ����</a></li>
                    <li<?=$_GET['status']==1?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">��ͨ��</a></li>
                    <li<?=$_GET['status']==2?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>2))?>">δͨ��</a></li>
                </ul>
            </th></tr>
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="*">��������</td>
				<td width="100">�����Ա</td>
				<td width="100">����������</td>
				<td width="110">�ύʱ��</td>
				<td width="80">����</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="applyids[]" value="<?=$val['applyid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name']?>&nbsp;<?=$val['subname']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><?=$val['applyname']?></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
				<td><a href="<?=cpurl($module,$act,'check',array('applyid'=>$val['applyid']))?>">����/�鿴</a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('applyids[]');" class="btn2">ȫѡ</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','applyids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>