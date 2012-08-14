<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1" width="45%"><strong>允许关联的主题分类：</strong>允许对某一些分类的主题进行关联，只支持主分类</td>
        <td width="*">
            <?php
                $category = $_G['loader']->variable('category','item');
                $t_cfg['categorys'] = $t_cfg['categorys'] ? explode(',',$t_cfg['categorys']) : array();
                foreach($category as $val) {
                    $values[$val['catid']] = $val['name'];
                }
                echo form_check('t_cfg[categorys][]',$values,$t_cfg['categorys']);
            ?>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>允许在当前字段内保存关联主题数量：</strong>超出范围，则以“更多”链接显示在独立的关联列表中。注意：在这里改动数量后，系统不会对之前存在的数量进行更新。</td>
        <td><input type="text" class="txtbox4" name="t_cfg[savelen]" value="<?=$t_cfg['savelen']>0?$t_cfg['savelen']:5?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>前台显示时标签之间的分隔符：</strong>默认为逗号","</td>
        <td><input type="text" class="txtbox4" name="t_cfg[split]" value="<?=$t_cfg['split']?$t_cfg['split']:','?>" /></td>
    </tr>
    <tr>
        <td class="altbg1"><strong>内容页显示模式：</strong>主题内容页浏览时，以图片方式还是文字方式进行显示</td>
        <td><?=form_radio('t_cfg[showmod]',array('pic'=>'图片模式','word'=>'文字模式'),$t_cfg['showmod']?$t_cfg['showmod']:'word')?></td>
    </tr>
</table>