<table class="subtable" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="altbg1" width="45%"><strong>��ʽ��</strong></td>
        <td width="*">
            <?=form_select('t_cfg[format]',array('Y-m-d'=>'����(2010-05-27)','Y-m-d H:i:s'=>'����+ʱ��(2010-05-27 20:30:20)'), $t_cfg['format'])?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>Ĭ��ֵ��</strong></td>
        <td><input type="radio" name="t_cfg[default]" value=""<?if(!$t_cfg['default'])echo' checked="checked"';?> /> ����<br />
            <input type="radio" name="t_cfg[default]" value="NOW"<?if($t_cfg['default']=='NOW')echo' checked="checked"';?> /> ��ǰ����<br />
            <input type="radio" name="t_cfg[default]" value="CUSTOM"<?if($t_cfg['default']=='CUSTOM')echo' checked="checked"';?> /> ָ�����ڣ�<input type="text" name="t_cfg[time]" class="txtbox2" value="<?=$t_cfg['time']?>" />
        </td>
    </tr>
</table>