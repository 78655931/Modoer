<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'add')?>">
<input type="hidden" name="newcat[pid]" value="<?=$_GET['catid']?>" />
    <div class="space">
        <div class="subtitle">�������</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,$act,'config',array('catid'=>$catid))?>" onfocus="this.blur()">��������</a></li>
            <li class="selected"><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$catid))?>" onfocus="this.blur()">�ӷ������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>�ӷ������ƣ�</strong>��<span class="font_1"><?=$t_cat['name']?></span>���һ���ӷ��ࡣһ������Ӷ�����࣬��ʹ��"|"�ָ���</td>
                <td width="40%"><center><input type="text" class="txtbox" name="newcat[name]" /></center></td>
                <td class="altbg1" width="15%"><center><button type="submit" name="dosubmit" value="yes" class="btn2" />����ӷ���</button></center></td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�ӷ����б�[<?=$t_cat['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">ѡ?</td>
                <td width="40">ID</td>
                <td width="40">Attid</td>
                <td width="30">����</td>
                <td width="80">����</td>
                <td width="220">����</td>
                <td width="50">����</td>
                <td width="*">����</td>
            </tr>
            <?if($result) {
            foreach($result as $val) { ?>
            <tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><?=$val['attid']?></td>
                <td><input type="checkbox" name="t_cat[<?=$val['catid']?>][enabled]" value="1" <?if($val['enabled'])echo' checked="checked"';?> /></td>
                <td><input type="text" class="txtbox5" name="t_cat[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="t_cat[<?=$val['catid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><?=$val['total']?></td>
                <td>
                    <?if($val['level']<3):?><a href="<?=cpurl($module,$act,'subcat',array('catid'=>$val['catid']))?>">�¼�����</a>&nbsp;<?endif;?>
                    <a href="<?=cpurl($module,$act,'edit',array('catid'=>$val['catid']))?>">��������</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('catid'=>$val['catid']))?>" onclick="return confirm('��ȷ��Ҫ����ɾ��������');">ɾ��</a>
                </td>
            </tr>
            <?}?>
            <tr class="altbg1"><td colspan="8">
            <button type="button" onclick="checkbox_checked('catids[]');" class="btn2">ȫѡ</button>&nbsp;
            ʹ�÷��������ֶ����������� ��ģ������=>��������:�������� ��ѡ�񡰰�����˳�򡱡�</td></tr>
            <?} else {?>
            <tr><td colspan="8">������Ϣ��</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="subcat" />
            <?if($result){?>
            <button type="submit" class="btn" />�����ύ</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','rebuild','catids[]')">�ؽ�ͳ��</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'category_list')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>