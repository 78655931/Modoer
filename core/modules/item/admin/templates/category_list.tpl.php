<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'listorder')?>">
    <div class="space">
        <div class="subtitle">�������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">ID</td>
                <td width="45">����</td>
                <td width="80">����</td>
                <td width="150">����</td>
                <td width="*">����</td>
            </tr>
            <?if(!empty($catlist)) { 
            foreach($catlist as $val) {?>
            <tr>
                <td><?=$val['catid']?></td>
                <td><input type="checkbox" name="category[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><?=$val['name']?></td>
                <td><a href="<?=cpurl($module,'category_edit','config',array('catid'=>$val['catid']))?>">��������</a>&nbsp;
                <a href="<?=cpurl($module,'category_edit','subcat',array('catid'=>$val['catid']))?>">�ӷ������</a>&nbsp;
                <a href="<?=cpurl($module,'category_edit','delete',array('catid'=>$val['catid']))?>" onclick="return confirm('��ȷ��ɾ���������������ȷ����������������κ��ӷ�����ڣ���������ɾ���ӷ��ࡣ');">ɾ��������</a></td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="4">������Ϣ��</td></tr>
            <?}?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_add')?>'">���ӷ���</button>&nbsp;
            <?if($catlist) {?>
            <button type="submit" name="dosubmit" value="yes" class="btn">��������</button>
            <?}?>
        </center>
    </div>
</form>
</div>