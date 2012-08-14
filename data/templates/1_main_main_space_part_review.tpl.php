<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/review.js"></script><?php $S=$_G['loader']->model('item:subject'); if($reviews) { if($reviews) { while($val = $reviews->fetch_array()) { ?>
<div class="review">
    <?php $category = $S->get_category($val['pcatid']);
        $catcfg =& $category['config'];
     ?>    <div class="field f_w_item">
        <div class="feed">
        <? if($val['uid']) { ?>
            <em>
                <span class="respond-ico"><a href="javascript:get_respond('<?=$val['rid']?>');">回应</a></span>(<span class="font_2" id="respond_<?=$val['rid']?>"><?=$val['responds']?></span> 条)&nbsp;
                <span class="flower-ico"><a href="javascript:add_flower(<?=$val['rid']?>, <?=$val['pcatid']?>);">鲜花</a>(<a href="javascript:get_flower(<?=$val['rid']?>, <?=$val['pcatid']?>);"><span id="flower_<?=$val['rid']?>" class="font_2"><?=$val['flowers']?></span> 朵</a>)</span>&nbsp;
                <a href="javascript:post_report(<?=$val['rid']?>, <?=$val['pcatid']?>);">举报</a>
            </em>
            <span class="member-ico"><a href="<?php echo url("space/index/uid/{$val['uid']}"); ?>"><?=$val['username']?></a></span>
            
<? } else { ?>
            <span class="font_3">游客(<?php echo preg_replace("/^([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)$/","\\1.\\2.*.*", $val['ip']); ?>)</span>
            <? } ?>
            在&nbsp;<?php echo newdate($val['posttime'], 'w2style'); ?>&nbsp;点评了&nbsp;<strong><a href="<?php echo template_print('review','typeurl',array('idtype'=>$val['idtype'],'id'=>$val['id'],));?>"><?=$val['subject']?></a></strong>
        </div>
        <? if(display('review:viewdigest',array('digest'=>$val['digest'],'uid'=>$val['uid']))) { ?>
        <div class="info">
            <ul class="score">
                
<?php $_QUERY['get__val']=$_G['datacall']->datacall_get('reviewopt',array('catid'=>$val['pcatid'],),'item');
if(is_array($_QUERY['get__val']))foreach($_QUERY['get__val'] as $_val_k => $_val) { ?>
                <li><?=$_val['name']?></li><li class="start<?php echo cfloat($val[$_val['flag']]); ?>"></li>
                <?php } ?>
            </ul>
            <div class="clear"></div>
            <? if($val['title']!=$val['subject']) { ?>
<h4 class="title"><a href="<?php echo url("review/detail/id/{$val['rid']}"); ?>"><?=$val['title']?></a></h4><? } ?>
            <? if($val['pictures']) { ?>
            <div class="pictures">
                <?php $val['pictures'] = unserialize($val['pictures']); ?>                
<? if(is_array($val['pictures'])) { foreach($val['pictures'] as $pic) { ?>
                <a href="<?=URLROOT?>/<?=$pic['picture']?>"><img src="<?=URLROOT?>/<?=$pic['thumb']?>" onmouseover="tip_start(this);" /></a>
                
<? } } ?>
            </div>
            <? } ?>
            <?php $reviewurl = '...<a href="' . url("review/detail/id/".$val['rid']) . '">[查看全文]</a>';
                $val['content'] = trimmed_title($val['content'],500,$reviewurl);
             ?>            <p><?php echo nl2br($val['content']); ?></p>
            <ul class="params">
                <? if($val['price'] && $catcfg['useprice']) { ?>
                <li><?=$catcfg['useprice_title']?>: <span class="font_2"><?=$val['price']?><?=$catcfg['useprice_unit']?></span></li>
                <? } ?>
                <?php $detail_tags = $val['taggroup'] ? @unserialize($val['taggroup']) : array(); ?>                
<? if(is_array($taggroups)) { foreach($taggroups as $_key => $_val) { ?>
                    <? if($detail_tags[$_key]) { ?>
                        <li><?=$_val['name']?>:
                        
<? if(is_array($detail_tags[$_key])) { foreach($detail_tags[$_key] as $tagid => $tagname) { ?>
                            <a href="<?php echo url("item/tag/tagname/{$tagname}"); ?>"><?=$tagname?></a>
                        
<? } } ?>
                        </li>
                    <? } ?>
                
<? } } ?>
            </ul>
            <div id="flowers_<?=$val['rid']?>"></div>
            <div id="responds_<?=$val['rid']?>"></div>
        </div>
        
<? } else { ?>
        <div class="review-digest-message">
            <? if(!$user->isLogin) { ?>
            <a href="javascript:dialog_login();">登录后查看精华点评</a>
            
<? } else { ?>
            <a href="javascript:review_view_digst(<?=$val['rid']?>);">查看精华点评</a>（第一次查看需要购买）
            <? } ?>
        </div>
        <? } ?>
    </div>
    <div class="clear"></div>
</div>
<? } } if($multipage) { ?>
<div class="multipage"><?=$multipage?></div><? } } else { ?>
<div class="messageborder">
    <span class="msg-ico">暂时没有信息。</span>
</div><? } ?>
