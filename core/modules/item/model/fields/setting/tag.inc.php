<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>������ǩ�飺</strong>�����ֶ���Ҫ�ı�ǩ�飬��ǩ����� ��̨-&gt;��������-&gt;��ǩ�����</td>
        <td width="*">
            <select name="t_cfg[groupid]">
                <option value="">==ѡ���ǩ��==</option>
                <?=form_item_taggroup($t_cfg['groupid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�����ǩ�������ƣ�</strong>���֧��15����Ĭ��Ϊ5��</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']>0?$t_cfg['len']:5?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>ǰ̨��ʾʱ��ǩ֮��ķָ�����</strong>Ĭ��Ϊ����","</td>
        <td><input type="text" class="txtbox5" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
</table>