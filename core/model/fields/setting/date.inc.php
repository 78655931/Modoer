<table class="subtable" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="altbg1" width="45%"><strong>格式：</strong></td>
        <td width="*">
            <?=form_select('t_cfg[format]',array('Y-m-d'=>'日期(2010-05-27)','Y-m-d H:i:s'=>'日期+时间(2010-05-27 20:30:20)'), $t_cfg['format'])?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>默认值：</strong></td>
        <td><input type="radio" name="t_cfg[default]" value=""<?if(!$t_cfg['default'])echo' checked="checked"';?> /> 留空<br />
            <input type="radio" name="t_cfg[default]" value="NOW"<?if($t_cfg['default']=='NOW')echo' checked="checked"';?> /> 当前日期<br />
            <input type="radio" name="t_cfg[default]" value="CUSTOM"<?if($t_cfg['default']=='CUSTOM')echo' checked="checked"';?> /> 指定日期：<input type="text" name="t_cfg[time]" class="txtbox2" value="<?=$t_cfg['time']?>" />
        </td>
    </tr>
</table>