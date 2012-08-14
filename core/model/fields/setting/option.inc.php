<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%" valign="top"><strong>选项值：</strong>格式：数字值=名称，一行一个，必须正确按照格式填写。<br /><span class="font_1">数字值必须大于0，同时不能有重复出现。</span></td>
        <td width="*"><textarea name="t_cfg[value]" rows="5" cols="50"><?=$edit ? $t_cfg['value'] : "1=数据名称"?></textarea></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>选项类型：</strong>类型决定表单类型。<br /><span class="font_1">此项填写后将无法再次改动</span></td>
        <td><select name="t_cfg[type]"<?if($disabled&&$t_cfg['type']=='check')echo $disabled;?>>
            <?if(!$disabled || (!$t_cfg['type'] || $t_cfg['type']=='radio' || $t_cfg['type']=='select')):?>
            <option value="select"<?if($t_cfg['type']=='select')echo' selected="selected"';?>>下拉框(select)</option>
            <option value="radio"<?if($t_cfg['type']=='radio')echo' selected="selected"';?>>单选按钮(radio)</option>
            <?endif;?>
            <?if(!$disabled):?>
            <option value="check"<?if($t_cfg['type']=='check')echo' selected="selected"';?>>复选框(checkbox)</option>
            <?endif;?>
        </select>
    </tr>
    <tr>
        <td class="altbg1"><strong>默认值：</strong>请填写名称对应的数字值，如果是复选框，请在各个数字值中用","分隔。</td>
        <td><input type="text" class="txtbox2" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>前台分隔符：</strong>多选在前台显示时，相互之间的分隔符号</td>
        <td><input type="text" class="txtbox2" name="t_cfg[display_split]" value="<?=$t_cfg['display_split']?>" /></td>
    </tr>
</table>