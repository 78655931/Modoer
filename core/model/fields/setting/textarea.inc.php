<table class="subtable" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="altbg1" width="45%"><strong>�ı�����[width]��</strong>����ΪĬ�ϣ�Ĭ��Ϊ 98%</td>
        <td width="*"><input type="text" class="txtbox4" name="t_cfg[width]" value="<?=$t_cfg['width']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�ı���߶�[height]��</strong>����ΪĬ�ϣ�Ĭ��Ϊ 80px</td>
        <td><input type="text" class="txtbox4" name="t_cfg[height]" value="<?=$t_cfg['height']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�Ƿ�����HTML���룺</strong></td>
        <td><?=form_radio('t_cfg[html]',array('��ʹ��','ʹ��','ʹ��WEB�༭��'), $t_cfg['html'])?></td>
    </tr>
    <tr>
        <td class="altbg1" valign="top"><strong>Ĭ��ֵ��</strong></td>
        <td width="*"><textarea name="t_cfg[default]" rows="5" cols="50"><?=$t_cfg['default']?></textarea></td>
    </tr>
   <tr>
        <td class="altbg1"><strong>�����ϴ�ͼƬ:</strong>����ʹ��WEB�༭��ʱ��Ч</td>
        <td>
            <?=form_bool('t_cfg[upimage]',$t_cfg['upimage'])?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>�ַ��������ƣ�</strong>���ձ�ʾ�����ơ�</td>
        <td>
            <input type="text" class="txtbox5" name="t_cfg[charnum_sup]" value="<?=$t_cfg['charnum_sup']?>" />
            -
            <input type="text" class="txtbox5" name="t_cfg[charnum_sub]" value="<?=$t_cfg['charnum_sub']?>" />
        </td>
    </tr>
</table>