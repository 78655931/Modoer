<? !defined('IN_MUDDER') && exit('Access Denied'); if($subjects) { ?>
<?php $category=$_G['loader']->variable('category','item');$i=0; ?><ul class="subject_list">
<? if($subjects) { while($val = $subjects->fetch_array()) { ?>
<li style="<? if($i%2==0) { ?>
background:#FFF
<? } else { ?>
background:#EEE<? } ?>
"><cite><?php echo newdate($val['addtime'],'Y-m-d H:i:s'); ?></cite>[<a href="<?php echo url("item/list/catid/{$val['pid']}"); ?>"><?php echo $category[$val['pid']]['name']; ?></a>]<a href="<?php echo url("item/detail/id/{$val['sid']}"); ?>"><?=$val['name']?>&nbsp;<?=$val['subname']?></a></li><?php $i++; } } ?>
</ul><? if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } } else { ?>
<div class="messageborder">
    <span class="msg-ico">暂时没有信息。</span>
</div><? } ?>
