<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="space">
        <div class="subtitle">ģ�͹���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="40">ģ��ID</td>
                <td width="*">����</td>
                <td width="180">���ݱ�</td>
                <td width="80">��������</td>
                <td width="80">��λ</td>
                <td width="80">��������</td>
                <td width="200">����</td>
            </tr>
            <?if($result) {
            foreach($result as $val) {?>
            <tr>
                <td><?=$val['modelid']?></td>
                <td><?=$val['name']?></td>
                <td>[dbpre]<?=$val['tablename']?></td>
                <td><?=$val['item_name']?></td>
                <td><?=$val['item_unit']?></td>
                <td><?=$val['usearea']?'��':'��'?></td>
                <td>
                    <a href="<?=cpurl($module,'model_edit','',array('modelid'=>$val['modelid']))?>">�༭</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('modelid'=>$val['modelid']))?>" onclick="return confirm('��ȷ��Ҫ����ɾ��������');">ɾ��</a>&nbsp;
                    <a href="<?=cpurl($module,'field_list','',array('modelid'=>$val['modelid']))?>">�ֶι���</a>&nbsp;
                    <a href="<?=cpurl($module,'model_list','export',array('modelid'=>$val['modelid']))?>" target="_blank">����ģ��</a>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="7">������Ϣ��</td></tr>
            <?}?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'model_add')?>'">����ģ��</button>
    </center>
</div>