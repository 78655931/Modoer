<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�������</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_discussion')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="180">��������</td>
				<td width="��">����</td>
                <td width="100">����</td>
				<td width="110">����ʱ��</td>
                <td width="120">����</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="tpids[]" value="<?=$val['tpid']?>" /></td>
				<td><?if($val['subjectname']):?><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['subjectname'].$val['subname']?></a><?else:?>������Ϣ�����ڻ���ɾ��<?endif;?></td>
                <td><?=$val['subject']?></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('tpid'=>$val['tpid']))?>">�༭</a>
                </td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('tpids[]');" class="btn2">ȫѡ</button>
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
        <button type="button" class="btn" onclick="easy_submit('myform','delete','tpids[]')">ɾ����ѡ</button>
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','tpids[]')">�����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>