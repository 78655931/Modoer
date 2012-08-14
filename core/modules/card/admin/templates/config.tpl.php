<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">搜索引擎优化</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>关联主题模型:</strong>设置需要使用会员卡模块的主题模型。<span class="font_1">注意：每次改动设置，程序将在主题模型字段中增加或者删除会员卡信息字段，特别是取消关联，会直接删除整合会员卡信息字段。</span></td>
                <?php 
                    $models = $_G['loader']->variable('model','item');
                    $modcfg['modelids'] = !$modcfg['modelids'] ? array() : unserialize($modcfg['modelids']);
                ?>
                <td width="*"><?=form_check('modcfg[modelids][]',$models,$modcfg['modelids'],'','&nbsp;');?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>启用会员卡申请:</strong>会员可以在前台通过填写会员卡申请表单来领取<?=$MOD['name']?>。</td>
                <td width="*"><?=form_bool('modcfg[apply]',$modcfg['apply']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>开启申请验证码:</strong></td>
                <td><?=form_bool('modcfg[applyseccode]',$modcfg['applyseccode']);?></td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>申请会员卡扣除积分类型:</strong>设置积分类型。</td>
                <td>
                    <select name="modcfg[pointgroup]">
                        <option value="">选择积分类型</option>
                        <?=form_member_pointgroup($modcfg['pointgroup']);?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>申请会员卡扣除积分:</strong>当会员提交会员卡申请时，扣除会员的相应积分，如申请审核失败，则退还会员积分。</td>
                <td><input name="modcfg[coin]" type="text" class="txtbox5" value="<?=$modcfg['coin']?>" /></td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>申请数量限制:</strong>每个会员最多只能申请多少张会员卡。</td>
                <td><input name="modcfg[applynum]" type="text" class="txtbox5" value="<?=$modcfg['applynum']?>" /> 张</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>申请说明:</strong>会员提交申请时，显示给用户看的申请说明。</td>
                <td><textarea name="modcfg[applydes]" rows="5" cols="10"><?=$modcfg['applydes']?></textarea></td>
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