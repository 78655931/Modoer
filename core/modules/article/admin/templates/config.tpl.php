<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">界面设置</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">多城市功能设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords 项出现在页面头部的 Meta 标签中，用于记录本页面的关键字，多个关键字间请用半角逗号 "," 隔开</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description 出现在页面头部的 Meta 标签中，用于记录本页面的概要与描述。</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>发布文章时允许下载远程图片:</strong>前后台用户在发布文章时，后台自动对非本站的图片进行远程下载。<br /><span class="font_1">建议不在发布文章时下载图片，因为此功能会影响文章的发布速度（网络速度和图片数量会影响图片下载时间，甚至出现无法发布文章的问题）</font></td>
                <td>
                    <div>前台：<?=form_bool('modcfg[dwon_image_bf]', $modcfg['dwon_image_bf'])?></div>
                    <div>后台：<?=form_bool('modcfg[dwon_image_cp]', $modcfg['dwon_image_cp'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>启用文章RSS聚合服务:</strong>网站新闻提供RSS聚合服务。</td>
                <td width="*"><?=form_bool('modcfg[rss]', $modcfg['rss'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>开启文章图片上传功能:</strong>前台用户发布文章时，允许使用编辑器的图片上传功能。</td>
                <td><?=form_bool('modcfg[editor_image]', $modcfg['editor_image'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用文章内容过滤功能:</strong>对发布的文章内容进行词语过滤。<br />
                <span class="font_1">过滤词库请在 网站管理=&gt;词语过滤管理 中进行设置。</span></td>
                <td><?=form_bool('modcfg[post_filter]', $modcfg['post_filter'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用发布文章验证码功能:</strong>前台用户在发布文章时，需要填写验证码</td>
                <td><?=form_bool('modcfg[post_seccode]', $modcfg['post_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用文章审核功能:</strong>对前台用户（包含主题管理员）发布的文章进行后台审核，只有经过审核的文章才能在前台显示。</td>
                <td><?=form_bool('modcfg[post_check]', $modcfg['post_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许主题管理员发布主题资讯:</strong>启用本功能后，主题管理员（认领人）就可以发布相关主题的资讯信息，并可在主题页面显示文章内容。<br /><span class="font_1">会员发布文章的权限请在 会员管理=>用户组=>权限管理 中进行设置。</span></td>
                <td><?=form_bool('modcfg[owner_post]', $modcfg['owner_post'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>指定主题管理员发布的资讯分类:</strong>给主题管理员发布的资讯提指定1个允许的主题，仅限于文章大类选择。</td>
                <td>
                    <select name="modcfg[owner_category]">
                        <option value="0">没有限制</option>
                        <?=form_article_category()?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>指定网站普通会员发布的资讯分类:</strong>给网站普通会员发布的资讯提指定1个允许的主题，仅限于文章大类选择。</td>
                <td>
                    <select name="modcfg[member_category]">
                        <option value="0">没有限制</option>
                        <?=form_article_category()?>
                    </select>
                </td>
            </tr>
            -->
            <tr>
                <td class="altbg1"><strong>允许普通会员关联主题:</strong>启用本功能后，普通会员在发布文章时，可以设置文章管理的关联主题。</td>
                <td><?=form_bool('modcfg[member_bysubject]', $modcfg['member_bysubject'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用文章评论功能:</strong>会员可以对文章进行（需要安装评论模块）</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>文章自定义属性设置:</strong>格式：1|热点推荐，说明：1表示att的值，|后面为att值的说明，每行一个，att的值不能重复。</td>
                <td>
                    <textarea name="modcfg[att_custom]" style="width:500px;height:100px;"><?=$modcfg['att_custom']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>文章内容字符限制：</strong>定义文章内容符限制，默认10-20000</td>
                <td><input type="text" name="modcfg[content_min]" value="<?=$modcfg['content_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[content_max]" value="<?=$modcfg['content_max']?>" class="txtbox5" /></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>文章内容页每页字符数量：</strong>定制文章内容每页限制最大字符，超出限定字符数量，则进行分页，如不选择分页，请留空或填写为0，默认每页最少为1000字符。PS：文章字符包含了HTML代码，因此建议设置值大于1000</td>
                <td><input type="text" name="modcfg[page_word]" value="<?=$modcfg['page_word']?>" class="txtbox4" /></td>
            </tr>
            -->
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>列表页显示数量:</strong>设置文章列表页，文章的显示数量，默认为10条。</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('10'=>'10条','20'=>'20条','40'=>'40条'),($modcfg['list_num']>0?$modcfg['list_num']:10))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许已关联主题的新闻使用主题风格:</strong>对已经关联了主题的新闻（新闻列表和新闻内容），采用主题设置的主题风格。</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>允许前台会员投稿时选择城市:</strong>前台会员在投稿时，可选择文章所属地，禁止则强制指定为当前正在访问分站城市。</td>
                <td width="*"><?=form_bool('modcfg[select_city]', $modcfg['select_city']);?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>