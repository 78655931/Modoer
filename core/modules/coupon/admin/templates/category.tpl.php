<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">�������</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="30">ѡ?</td>
				<td width="60">ID</td>
                <td width="80">����</td>
                <td width="*">����</td>
				<td width="80">����</td>
				<td width="100">����</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><input type="text" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox5 width" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" class="txtbox2" /></td>
                <td><?=$val['num']?></td>
                <td><a href="<?=cpurl($module,'coupon','add',array('catid'=>$val['catid']))?>">����</a>&nbsp;<a href="<?=cpurl($module,'category','delete',array('catid'=>$val['catid']))?>" onclick="return confirm('��ȷ��Ҫɾ���𣬱��β�����ɾ���������µ������Ż�ȯ��Ϣ��');">ɾ��</a></td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="7">������Ϣ</td>
            </tr>
            <?endif;?>
            <tr class="altbg1">
                <td colspan="2">����</td>
                <td><input type="text" name="newcategory[listorder]" class="txtbox5 width" /></td>
                <td colspan="4"><input type="text" name="newcategory[name]" class="txtbox2" />&nbsp;
                <button type="button" class="btn2" onclick="easy_submit('myform','add',null);">���</button></td>
            </tr>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">���²���</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','rebuild','catids[]');">�ؽ�����ͳ��</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','catids[]');">ɾ����ѡ</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>