<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">搜索引擎优化</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>启用后台审核:</strong>管理员通过后台对前台发布的优惠券进行审核。</td>
                <td width="*"><?=form_bool('modcfg[check]',$modcfg['check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许商家发布:</strong>设置优惠券只允许拥有商铺的管理员（店主）发布。<br /><span class="font_1">本功能设置后，会员组内的相关权限将失效。</span></td>
                <td><?=form_bool('modcfg[post_item_owner]',$modcfg['post_item_owner'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>优惠券原图水印:</strong>是否在优惠券上加入水印，注意：加了水印的优惠券可能失效。</td>
                <td><?=form_bool('modcfg[watermark]',$modcfg['watermark'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>优惠券原图是否进行裁剪:</strong>是否允许对上传的优惠券原图进行图片最裁剪（按系统默认的最大尺寸裁剪）</td>
                <td><?=form_bool('modcfg[autosize]',$modcfg['autosize'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>优惠券缩略图宽度和高度:</strong>优惠券上传后自动截取大小，宽度 x 高度，建议（默认）为160 x 100 px</td>
                <td><input type="text" name="modcfg[thumb_width]" value="<?=$modcfg['thumb_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[thumb_height]" value="<?=$modcfg['thumb_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>前台发布优惠券开启验证码:</strong>前台发布发布优惠券时，必须输入验证码。</td>
                <td><?=form_bool('modcfg[seccode]',$modcfg['seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用优惠券评论功能:</strong>会员可以对优惠券进行（需要安装评论模块）</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>优惠券每页显示数量:</strong>默认每页显示5条，留空表示默认。</td>
                <td><input type="text" name="modcfg[listnum]" value="<?=$modcfg['listnum']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>优惠券发布保证:</strong>优惠券发布保证说明，以避免不必要的法律责任</td>
                <td><input type="text" name="modcfg[des]" value="<?=$modcfg['des']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的优惠券使用主题风格:</strong>对已经关联了主题的优惠券（列表闻内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>模块首页副标题:</strong>&lt;title&gt;中显示的副标题</td>
                <td width="*"><input type="text" name="modcfg[subtitle]" value="<?=$modcfg['subtitle']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>