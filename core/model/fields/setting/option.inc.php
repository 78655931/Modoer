<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%" valign="top"><strong>ѡ��ֵ��</strong>��ʽ������ֵ=���ƣ�һ��һ����������ȷ���ո�ʽ��д��<br /><span class="font_1">����ֵ�������0��ͬʱ�������ظ����֡�</span></td>
        <td width="*"><textarea name="t_cfg[value]" rows="5" cols="50"><?=$edit ? $t_cfg['value'] : "1=��������"?></textarea></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>ѡ�����ͣ�</strong>���;��������͡�<br /><span class="font_1">������д���޷��ٴθĶ�</span></td>
        <td><select name="t_cfg[type]"<?if($disabled&&$t_cfg['type']=='check')echo $disabled;?>>
            <?if(!$disabled || (!$t_cfg['type'] || $t_cfg['type']=='radio' || $t_cfg['type']=='select')):?>
            <option value="select"<?if($t_cfg['type']=='select')echo' selected="selected"';?>>������(select)</option>
            <option value="radio"<?if($t_cfg['type']=='radio')echo' selected="selected"';?>>��ѡ��ť(radio)</option>
            <?endif;?>
            <?if(!$disabled):?>
            <option value="check"<?if($t_cfg['type']=='check')echo' selected="selected"';?>>��ѡ��(checkbox)</option>
            <?endif;?>
        </select>
    </tr>
    <tr>
        <td class="altbg1"><strong>Ĭ��ֵ��</strong>����д���ƶ�Ӧ������ֵ������Ǹ�ѡ�����ڸ�������ֵ����","�ָ���</td>
        <td><input type="text" class="txtbox2" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>ǰ̨�ָ�����</strong>��ѡ��ǰ̨��ʾʱ���໥֮��ķָ�����</td>
        <td><input type="text" class="txtbox2" name="t_cfg[display_split]" value="<?=$t_cfg['display_split']?>" /></td>
    </tr>
</table>