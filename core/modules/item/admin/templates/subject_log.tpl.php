<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">���ⲹ��</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg2"><th colspan="12">
                <ul class="subtab">
                    <li<?=!$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act)?>">δ����</a></li>
                    <li<?=$_GET['disposal']?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('disposal'=>1))?>">�Ѵ���</a></li>
                </ul>
            </th></tr>
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="150">��������</td>
				<td width="80">�ύ�û�</td>
				<td width="60">��������</td>
				<td width="*">��������</td>
				<td width="110">�ύʱ��</td>
				<td width="50">����</td>
            </tr>
			<?if($list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="upids[]" value="<?=$val['upid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name']?><?=$val['subname']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><?=$val['username']?><?=!$val['uid']?'(�ο�)':''?></td>
				<td><?=$val['ismappoint']?'��ͼ����':'������Ϣ'?></td>
				<td><?=$val['upcontent']?></td>
				<td><?=date('Y-m-d H:i', $val['posttime'])?></td>
				<td><a href="<?=cpurl($module,'subject_edit','log',array('upid'=>$val['upid']))?>"><?=$val['disposal']?'�Ѵ���':'δ����'?></a></td>
            </tr>
			<?endwhile;?>
			<tr>
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('upids[]');" class="btn2">ȫѡ</button>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','upids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>