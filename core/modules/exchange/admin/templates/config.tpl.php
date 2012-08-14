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
                <td class="altbg1"><strong>礼品缩略图尺寸限制:</strong>礼品图片上传后自动截取最大尺寸，宽度 x 高度，默认160×100</td>
                <td><input type="text" name="modcfg[thumb_w]" value="<?=$modcfg['thumb_w']?$modcfg['thumb_w']:160?>" class="txtbox5" /> x <input type="text" name="modcfg[thumb_h]" value="<?=$modcfg['thumb_h']?$modcfg['thumb_h']:100?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>兑换提交显示验证码:</strong>提交兑换表单时，显示验证码</td>
                <td width="*"><?=form_bool('modcfg[exchange_seccode]', $modcfg['exchange_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>财富榜积分字段</strong>前台显示的财富榜的关联积分类型</td>
                <td>
                    <select name="modcfg[pointgroup]">
                        <option value="">选择积分类型</option>
                        <?=form_member_pointgroup($modcfg['pointgroup']);?>
                    </select>
                </td>
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