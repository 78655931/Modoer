<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">���Թ���</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_guestbook')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <?if(!$edit_links):?>
            <tr class="altbg2"><th colspan="12">
                <ul class="subtab">
                    <?foreach($_G['loader']->variable('category',MOD_FLAG) as $key => $val) { if($val['pid']) continue; ?>
                    <li<?=$pid==$key?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('pid'=>$key))?>"><?=$val['name']?></a></li>
                    <?}?>
                </ul>
            </th></tr>
            <?endif;?>
			<tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="180">��������</td>
				<td width="80">���Ի�Ա</td>
                <td width="*">��������</td>
				<td width="110">����ʱ��</td>
                <td width="100">IP</td>
                <td width="50">����</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="guestbookids[]" value="<?=$val['guestbookid']?>" /></td>
				<td><?if($val['name']):?><a href="<?=url("item/detail/id/$val[sid]")?>#review" target="_blank"><?=$val['name'].$val['subname']?></a><?else:?>������Ϣ�����ڻ���ɾ��<?endif;?></td>
                <td><a href="<?=url("item/space/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td>
                    <div><?=$val['content']?></div>
                    <?if($val['replytime']):?>
                    <div style="color:#FF3300;">�ظ���<?=$val['reply']?></div>
                    <?endif;?>
                </td>
				<td>
                    <div><?=date('Y-m-d H:i', $val['dateline'])?></div>
                    <?if($val['replytime']):?>
                    <div style="color:#FF3300;"><?=date('Y-m-d H:i', $val['replytime'])?></div>
                    <?endif;?>
                </td>
                <td><?=$val['ip']?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('guestbookid'=>$val['guestbookid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('guestbookids[]');" class="btn2">ȫѡ</button>
					<input type="checkbox" name="delete_point" id="delete_point" value="1" /><label for="delete_point">ɾ����Ӧͬʱ�������߻���</label>
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
		<button type="button" class="btn" onclick="easy_submit('myform','delete','guestbookids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>