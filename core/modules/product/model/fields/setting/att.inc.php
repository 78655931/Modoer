<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>���������飺</strong>�����ֶ���Ҫ�������飬����������� ��̨-&gt;��������-&gt;���������</td>
        <td width="*">
            <select name="t_cfg[catid]">
                <option value="">==ѡ��������==</option>
                <?=form_item_attcat($t_cfg['catid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>��������ֵ�������ƣ�</strong>Ĭ��Ϊ1����1����ʾΪ��ѡ</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']>0?$t_cfg['len']:1?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>ǰ̨��ʾʱ��ǩ֮��ķָ�����</strong>Ĭ��Ϊ����","</td>
        <td><input type="text" class="txtbox5" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
</table>