<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>ȡֵ��Χ��</strong>�������ֵ���С�����ķ�Χ����ͬ�Ĵ�С��Ӱ�����ݿ��ֶ����ͣ���ȡһ���ʵ��ķ�Χ��</td>
        <td width="*"><input type="text" class="txtbox4" name="t_cfg[min]" value="<?=$t_cfg['min']?>" /> - <input type="text" class="txtbox4" name="t_cfg[max]" value="<?=$t_cfg['max']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>С��λ����</strong>����С�����λ����������ô���0������������ֶ�Ϊ�����͡�<br /><span class="font_1">������д���޷��ٴθĶ�</span></td>
        <td><select name="t_cfg[point]"<?=$disabled?>>
            <option value="0"<?if(!$t_cfg['point'])echo' selected="selected"';?>>0</option>
            <option value="1"<?if($t_cfg['point']=='1')echo' selected="selected"';?>>1</option>
            <option value="2"<?if($t_cfg['point']=='2')echo' selected="selected"';?>>2</option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>Ĭ��ֵ��</strong></td>
        <td><input type="text" class="txtbox" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
</table>