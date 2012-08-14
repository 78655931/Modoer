<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>取值范围：</strong>设置数字的最小到最大的范围，不同的大小会影响数据库字段类型，请取一个适当的范围。</td>
        <td width="*"><input type="text" class="txtbox4" name="t_cfg[min]" value="<?=$t_cfg['min']?>" /> - <input type="text" class="txtbox4" name="t_cfg[max]" value="<?=$t_cfg['max']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>小数位数：</strong>设置小数点的位数，如果设置大于0，则表明数据字段为浮点型。<br /><span class="font_1">此项填写后将无法再次改动</span></td>
        <td><select name="t_cfg[point]"<?=$disabled?>>
            <option value="0"<?if(!$t_cfg['point'])echo' selected="selected"';?>>0</option>
            <option value="1"<?if($t_cfg['point']=='1')echo' selected="selected"';?>>1</option>
            <option value="2"<?if($t_cfg['point']=='2')echo' selected="selected"';?>>2</option>
        </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>默认值：</strong></td>
        <td><input type="text" class="txtbox" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
</table>