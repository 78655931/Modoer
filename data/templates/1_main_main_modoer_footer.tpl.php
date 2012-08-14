<? !defined('IN_MUDDER') && exit('Access Denied'); ?>
<?="\r\n"?>
<div id="footer">
    <?php $foot_menus = $_CFG['foot_menuid'] ? $_G['loader']->variable('menu_' . $_CFG['foot_menuid']) : ''; ?>    <div class="links">
        
<? if(is_array($foot_menus)) { foreach($foot_menus as $val) { ?>
        <a href="<?php echo url("{$val['url']}"); ?>"<? if($val['target']) { ?>
 target="<?=$val['target']?>"<? } ?>
><?=$val['title']?></a>&nbsp;|
        
<? } } ?>
        <a href="javascript://;" onclick="window.scrollTo(0,0);">·µ»Ø¶¥²¿</a><? if($_CFG['icpno']) { ?>
&nbsp;|
        <a href="http://www.miibeian.gov.cn/" target="_blank"><?=$_CFG['icpno']?></a><? } if($_CFG['statistics']) { ?>
&nbsp;|
        <?=$_CFG['statistics']?><? } ?>
    </div>
    <div>
         Powered by <span class="product"><a href="http://www.modoer.com/" target="_blank">Modoer</a></span> <span class="version"><?=$_G['modoer']['version']?><? if($_CFG['buildinfo']) { ?>
&nbsp;(<?=$_G['modoer']['build']?>)<? } ?>
</span>&nbsp;<?=$_CFG['copyright']?><br /><?=$_CFG['statement']?>
    </div>
    <? if($sitedebug) { ?>
<div class="bottom"><?=$sitedebug?></div><? } ?>
</div><? if(DEBUG) { ?>
<?php echo $_G['db']->debug_print();; ?><?php echo $_G['loader']->debug_print();; } ?>
</body>
</html>