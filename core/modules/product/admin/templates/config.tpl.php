<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能配置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>开启产品发布验证:</strong>发布产品时，必须填写验证码 </td>
                <td width="*"><?=form_bool('modcfg[seccode_product]', $modcfg['seccode_subject'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用产品发布审核:</strong>发布产品后必须通过后台审核</td>
                <td><?=form_bool('modcfg[check_product]',$modcfg['check_product']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用产品点评（评论）功能:</strong>会员可以对产品进行点评（需要安装评论模块）</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>图片尺寸:</strong>上传点评对象的图片时，限制期最大尺寸，格式为：宽 x 高；默认：200 x 150</td>
                <td width="*"><input type="text" name="modcfg[thumb_width]" value="<?=$modcfg['thumb_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[thumb_height]" value="<?=$modcfg['thumb_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的产品使用主题风格:</strong>对已经关联了主题的产品（列表闻内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>允许主题管理员管理产品评论:</strong>允许管理员对自己主题的产品的评论</td>
                <td><?=form_bool('modcfg[manage_comment]',$modcfg['manage_comment']);?></td>
            </tr>
            -->
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>