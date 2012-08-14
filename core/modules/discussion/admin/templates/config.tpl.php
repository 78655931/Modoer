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
                <td class="altbg1" width="45%"><strong>模块首页标题:</strong>&lt;title&gt;中显示的标题</td>
                <td width="*"><input type="text" name="modcfg[title]" value="<?=$modcfg['title']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>话题发布审核:</strong>前台用户发布话题时，需要经过后台审核。</td>
                <td width="*"><?=form_bool('modcfg[topic_check]',$modcfg['topic_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>话题回复审核:</strong>前台用户在回复话题时，需要经过后台审核。</td>
                <td width="*"><?=form_bool('modcfg[reply_check]',$modcfg['reply_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>话题发布验证码:</strong>前台用户发布话题时，需要填写话验证码。</td>
                <td width="*"><?=form_bool('modcfg[topic_seccode]',$modcfg['topic_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>话题回复验证码:</strong>前台用户在回复话题时，需要填写验证码。</td>
                <td width="*"><?=form_bool('modcfg[reply_seccode]',$modcfg['reply_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>话题内容字数控制:</strong>设置前台发布讨论话题时的字数数量限制。</td>
                <td width="*">
                    <input type="text" name="modcfg[topic_content_min]" value="<?=$modcfg['topic_content_min']>0?$modcfg['topic_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[topic_content_max]" value="<?=$modcfg['topic_content_max']>0?$modcfg['topic_content_max']:5000?>" class="txtbox5" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>回应内容字数控制:</strong>设置前台发在回应话题时的字数数量限制。</td>
                <td>
                    <input type="text" name="modcfg[reply_content_min]" value="<?=$modcfg['reply_content_min']>0?$modcfg['reply_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[reply_content_max]" value="<?=$modcfg['reply_content_max']>0?$modcfg['reply_content_max']:1000?>" class="txtbox5" />
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>