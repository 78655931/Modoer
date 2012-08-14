<table class="subtable" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="altbg1" width="45%"><strong>文本域宽度[width]：</strong>留空为默认，默认为 98%</td>
        <td width="*"><input type="text" class="txtbox4" name="t_cfg[width]" value="<?=$t_cfg['width']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>文本域高度[height]：</strong>留空为默认，默认为 80px</td>
        <td><input type="text" class="txtbox4" name="t_cfg[height]" value="<?=$t_cfg['height']?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>是否允许HTML代码：</strong></td>
        <td><?=form_radio('t_cfg[html]',array('不使用','使用','使用WEB编辑器'), $t_cfg['html'])?></td>
    </tr>
    <tr>
        <td class="altbg1" valign="top"><strong>默认值：</strong></td>
        <td width="*"><textarea name="t_cfg[default]" rows="5" cols="50"><?=$t_cfg['default']?></textarea></td>
    </tr>
   <tr>
        <td class="altbg1"><strong>允许上传图片:</strong>仅在使用WEB编辑器时有效</td>
        <td>
            <?=form_bool('t_cfg[upimage]',$t_cfg['upimage'])?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>字符数量限制：</strong>留空表示不限制。</td>
        <td>
            <input type="text" class="txtbox5" name="t_cfg[charnum_sup]" value="<?=$t_cfg['charnum_sup']?>" />
            -
            <input type="text" class="txtbox5" name="t_cfg[charnum_sub]" value="<?=$t_cfg['charnum_sub']?>" />
        </td>
    </tr>
</table>