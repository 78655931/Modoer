<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>&" name="myform">
	<div class="space">
		<div class="subtitle">������</div>
        <ul class="cptab">
            <li<?=$_GET['enabled']=='Y'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'Y'))?>">����</a></li>
            <li<?=$_GET['enabled']=='N'?' class="selected"':''?>><a href="<?=cpurl($module,$act,'',array('enabled'=>'N'))?>">ͣ��</a></li>
        </ul>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
				<td width="25">ѡ</td>
				<td width="100">����</td>
				<td width="80">����</td>
                <td width="200">�������</td>
				<td width="200">���λ</td>
				<td width="60">����</td>
                <td width="200">��Ч��</td>
				<td width="*">����</td>
			</tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()) {?>
			<tr>
				<td><input type="checkbox" name="adids[]" value="<?=$val['adid']?>" /></td>
				<td><?=display('modoer:area',"aid/$val[city_id]")?></td>
				<td><?=form_input("adv[$val[adid]][listorder]",$val['listorder'],"txtbox5")?></td>
				<td><?=$val['adname']?></td>
				<td><?=$val['name']?></td>
				<td><?=$sorts[$val['sort']]?></td>
				<td><?=date('Y-m-d',$val['begintime'])?>��<?=$val['endtime']?date('Y-m-d',$val['endtime']):'����'?></td>
				<td><a href="<?=cpurl($module,$act,'edit',array('adid'=>$val['adid']))?>">�༭</a></td>
            </tr>
            <?}?>
            <?else:?>
            <tr><td colspan="7">������Ϣ</td></tr>
            <?endif;?>
		</table>
	</div>
    <div><?=$multipage?></div>
    <center>
        <input type="hidden" name="op" value="update" />
        <input type="hidden" name="dosubmit" value="yes" />
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'" />���ӹ��</button>&nbsp;
        <?if($total):?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null);">��������</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','adids[]');">ɾ����ѡ</button>&nbsp;
        <?endif;?>
    </center>
</form>
</div>