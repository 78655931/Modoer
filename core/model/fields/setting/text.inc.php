<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>Ĭ��ֵ��</strong></td>
        <td width="*"><input type="text" class="txtbox2" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�ı��򳤶�[size]��</strong>Ĭ��Ϊ20�����ձ�ʶĬ�ϡ�</td>
        <td><input type="text" class="txtbox4" name="t_cfg[size]" value="<?=$t_cfg['size']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>����ַ��������ƣ�</strong>���Ϊ255���ַ������ձ�ʾΪ255���ַ���</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']?>" />
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�Ƿ�����HTML���룺</strong></td>
        <td><?=form_bool('t_cfg[html]', $t_cfg['html'])?></td>
    </tr>
</table>