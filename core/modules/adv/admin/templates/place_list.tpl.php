<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
	<div class="space">
		<div class="subtitle">���λ����</div>
        <ul class="cptab">
            <li<?=$_GET['enabled']=='Y'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'Y'))?>">����</a></li>
            <li<?=$_GET['enabled']=='N'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'N'))?>">ͣ��</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0"  trmouse="Y">
            <tr class="altbg1">
				<td width="25">ѡ</td>
                <td width="200">����</td>
				<td width="350">����</td>
				<td width="*">����</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="apids[]" value="<?=$val['apid']?>" /></td>
                <td><?=$val['name']?></td>
                <td><?=$val['des']?></td>
                <td>
					<a href="<?=cpurl($module,$act,'edit',array('apid'=>$val['apid']))?>">�༭</a>&nbsp;
					<a href="<?=cpurl($module,'list','',array('apid'=>$val['apid']))?>">������</a>&nbsp;
					<a href="<?=cpurl($module,$act,'code',array('apid'=>$val['apid']))?>">���Ƶ��ô���</a>&nbsp;
				</td>
            </tr>
            <?}?>
            <?else:?>
            <tr>
                <td colspan="6">������Ϣ</td>
            </tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'" />���ӹ��λ</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','apids[]');">ɾ����ѡ</button>&nbsp;
    </center>
</form>
</div>