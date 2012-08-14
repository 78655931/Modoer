<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>关联标签组：</strong>设置字段需要的标签组，标签组管理 后台-&gt;点评管理-&gt;标签组管理</td>
        <td width="*">
            <select name="t_cfg[groupid]">
                <option value="">==选择标签组==</option>
                <?=form_item_taggroup($t_cfg['groupid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>允许标签数量限制：</strong>最多支持15个，默认为5个</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']>0?$t_cfg['len']:5?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>前台显示时标签之间的分隔符：</strong>默认为逗号","</td>
        <td><input type="text" class="txtbox5" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
</table>