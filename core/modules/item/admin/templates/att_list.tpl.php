<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>">
<input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
    <div class="space">
        <div class="subtitle">���Թ���</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'att_cat','edit',array('catid'=>$catid))?>" onfocus="this.blur()">�༭������</a></li>
            <li class="selected"><a href="<?=cpurl($module,'att_list','',array('catid'=>$catid))?>" onfocus="this.blur()">ֵ����</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>ֵ���ƣ�</strong>��������[<?=$cat['name']?>]�������������ֵ��һ������Ӷ��ֵ����ʹ��"|"�ָ���</td>
                <td width="40%"><center><input type="text" class="txtbox" name="names" /></center></td>
                <td class="altbg1" width="15%"><center><button type="submit" name="dosubmit" value="yes" class="btn2" />�������ֵ</button></center></td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">����ֵ�б�[<?=$cat['name']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">ѡ?</td>
                <td width="50">ID</td>
                <td width="60">����</td>
                <td width="210">����</td>
                <td width="*">ͼ��(ͼƬ�����Ŀ¼ /static/images/att ��)</td>
            </tr>
            <?if($list) {
            while($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="attids[]" value="<?=$val['attid']?>" /></td>
                <td><?=$val['attid']?></td>
                <td><input type="text" class="txtbox5" name="att_list[<?=$val['attid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <td><input type="text" class="txtbox3" name="att_list[<?=$val['attid']?>][name]" value="<?=$val['name']?>" /></td>
                <td><input type="text" class="txtbox4" name="att_list[<?=$val['attid']?>][icon]" value="<?=$val['icon']?>" /></td>
            </tr>
            <?}?>
            <tr class="altbg1"><td colspan="5">
            <button type="button" onclick="checkbox_checked('attids[]');" class="btn2">ȫѡ</button>
            <?} else {?>
            <tr><td colspan="5">������Ϣ��</td></tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="catid" value="<?=$_GET['catid']?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <?if($list){?>
            <button type="submit" class="btn" />�����ύ</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','attids[]')">ɾ����ѡ</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'att_cat')?>'" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>