<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>默认值：</strong></td>
        <td width="*"><input type="text" class="txtbox2" name="t_cfg[default]" value="<?=$t_cfg['default']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>文本框长度[size]：</strong>默认为20，留空标识默认。</td>
        <td><input type="text" class="txtbox4" name="t_cfg[size]" value="<?=$t_cfg['size']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>最大字符数量限制：</strong>最多为255个字符，留空表示为255个字符。</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']?>" />
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>是否允许HTML代码：</strong></td>
        <td><?=form_bool('t_cfg[html]', $t_cfg['html'])?></td>
    </tr>
</table>