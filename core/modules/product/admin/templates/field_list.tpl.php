<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'',array('modelid'=>$_GET['modelid']))?>">
    <div class="space">
        <div class="subtitle">�ֶι���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="60">����</td>
                <td width="90">�ֶα���</td>
                <td width="*">�ֶ���</td>
                <td width="120">������</td>
                <td width="100">�ֶ�����</td>
                <td width="40"><center>����</center></td>
                <td width="40"><center>�б�ҳ</center></td>
                <td width="40"><center>����ҳ</center></td>
                <td width="40"><center>��̨</center></td>
                <td width="100">����</td>
            </tr>
            <?if($result) { foreach($result as $val) {?>
            <tr>
                <td><input type="text" name="neworder[<?=$val['fieldid']?>]" value="<?=$val['listorder']?>" class="txtbox5" /></td>
                <td><?=$val['title']?></td>
                <td><?=$val['fieldname']?></td>
                <td><?=$val['tablename']?></td>
                <td><?=$F->fieldtypes[$val['type']]?></td>
                <td><center><?=$val['iscore']?'��':'��'?></center></td>
                <td><center><?=$val['show_list']?'��':'��'?></center></td>
                <td><center><?=$val['show_detail']?'��':'��'?></center></td>
                <td><center><?=$val['isadminfield']?'��':'��'?></center></td>
                <td>
                    <a href="<?=cpurl($module,'field_edit','edit',array('fieldid'=>$val['fieldid']))?>">�༭</a>
                    <?if(!$val['iscore']) { $disable = $val['disable'] ? 'enable' : 'disable'; ?>
                    <a href="<?=cpurl($module,'field_edit',$disable,array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>"><?=$val['disable']?'����':'����'?></a>
                    <a href="<?=cpurl($module,'field_edit','delete',array('modelid'=>$_GET['modelid'],'fieldid'=>$val['fieldid']))?>" onclick="return confirm('��ȷ��Ҫ����ɾ��������');">ɾ��</a>
                    <?}?>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="9">������Ϣ��</td></tr>
            <?}?>
        </table>
        
        <center>
            <?if($result) {?>
            <button type="submit" class="btn" name="dosubmit" value="yes">��������</button>&nbsp;
            <?}?>
            <button type="button" class="btn" onclick="document.location.href='<?=cpurl($module,'field_edit','add',array('modelid'=>$_GET['modelid']))?>'">�����ֶ�</button>&nbsp;
            <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'model_list')?>'" /><?=lang('global_return')?></button>
        </center>
        
    </div>
</form>
</div>