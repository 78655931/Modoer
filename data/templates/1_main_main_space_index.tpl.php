<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php $_HEAD['title'] = $space['spacename'] . $_CFG['titlesplit'] . $space['spacedescribe']; include template('modoer_header'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/item.js"></script>
<div id="body">
<div class="link_path">
    <em>
        
<? if(is_array($space_menus)) { foreach($space_menus as $val) { ?>
        <?php $val['url']=str_replace('(uid)', $uid, $val['url']); ?>        <a href="<?php echo url("{$val['url']}"); ?>"<? if(SCRIPTNAV==$val['scriptnav']) { ?>
 class="active"<? } ?>
><?=$val['title']?></a>&nbsp;|
        
<? } } ?>
        已浏览：<span class="font_2"><?=$space['pageview']?></span>
    </em>
    <a href="<?php echo url("modoer/index"); ?>">首页</a>&nbsp;&raquo;&nbsp;<a href="<?php echo url("space/index/uid/{$uid}"); ?>"><?=$member['username']?></a>
</div>
<div id="space_left">
    
<? include template('space_side'); ?>
    <div class="mainrail rail-border-1">
        <h3 class="rail-h-1 rail-h-bg-1">我的好友</h3>
        <ul class="rail-faces">
            
<? if(is_array($friends)) { foreach($friends as $val) { ?>
            <li><div><a href="<?php echo url("space/index/uid/{$val['friend_uid']}"); ?>" target="_blank"><img src="<?php echo get_face($val['friend_uid']); ?>" /></a><a href="<?php echo url("space/index/uid/{$val['friend_uid']}"); ?>" title="<?=$val['username']?>" target="_blank"></div><span><?=$val['username']?></span></a></li>
            
<? } } ?>
        </ul>
        <div class="clear"></div>
    </div>

</div>
<div id="space_right">
    <div class="mainrail rail-border-1">
        <? if($reviews) { ?>
<em><a href="<?php echo url("space/reviews/uid/{$uid}"); ?>">更多</a></em><? } ?>
        <h3 class="rail-h-1 rail-h-bg-1">我发表的点评</h3>
        
<? include template('space_part_review'); ?>
    </div>
    <div class="mainrail rail-border-1">
        <? if($subjects) { ?>
<em><a href="<?php echo url("space/subject/uid/{$uid}"); ?>">更多</a></em><? } ?>
        <h3 class="rail-h-1 rail-h-bg-1">我添加的主题</h3>
        
<? include template('space_part_subject'); ?>
    </div>
</div>
</div><?php footer(); ?>