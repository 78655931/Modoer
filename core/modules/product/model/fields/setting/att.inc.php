<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>关联属性组：</strong>设置字段需要的属性组，属性组组管理 后台-&gt;点评管理-&gt;属性组管理</td>
        <td width="*">
            <select name="t_cfg[catid]">
                <option value="">==选择属性组==</option>
                <?=form_item_attcat($t_cfg['catid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>允许属性值数量限制：</strong>默认为1个，1个表示为单选</td>
        <td><input type="text" class="txtbox5" name="t_cfg[len]" value="<?=$t_cfg['len']>0?$t_cfg['len']:1?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>前台显示时标签之间的分隔符：</strong>默认为逗号","</td>
        <td><input type="text" class="txtbox5" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
</table>