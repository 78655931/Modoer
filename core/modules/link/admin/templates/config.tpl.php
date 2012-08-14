<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>图片链接数量:</strong>在首页显示图片链接的数量</td>
                <td width="*"><input type="text" name="modcfg[num_logo]" class="txtbox4" value="<?=$modcfg['num_logo']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>文字链接数量:</strong>在首页显示文字链接的数量</td>
                <td><input type="text" name="modcfg[num_char]" class="txtbox4" value="<?=$modcfg['num_char']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许链接申请:</strong>是否允许游客提交友情链接申请</td>
                <td><?=form_bool("modcfg[open_apply]",$modcfg['open_apply']);?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>