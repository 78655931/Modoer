<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;模块配置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">功能设置</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">界面设置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>关闭全部评论:</strong>一次性关闭所有评论，可用于特殊情况，特殊时期。</td>
                <td><?=form_bool('modcfg[disable_comment]',$modcfg['disable_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>关闭所有显示的评论:</strong>关闭所有显示的评论信息。</td>
                <td><?=form_bool('modcfg[hidden_comment]',$modcfg['hidden_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>是否开启游客评论:</strong>允许游客进行点评</td>
                <td><?=form_bool('modcfg[guest_comment]',$modcfg['guest_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>开启评论审核:</strong>提交的评论只有经过后台审核才能显示在网站前台。</td>
                <td><?=form_bool('modcfg[check_comment]',$modcfg['check_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>使用关键词过滤:</strong>过滤的关键词库管理位置在 网站管理=&gt;词语过滤管理</td>
                <td><?=form_bool('modcfg[filter_word]',$modcfg['filter_word']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>单页评论时间间隔:</strong>设置单页内多次评论之间的时间间隔，默认为 10 秒，不能低于 5 秒。</td>
                <td><input name="modcfg[comment_interval]" value="<?=$modcfg['comment_interval']?$modcfg['comment_interval']:10?>" class="txtbox5" />&nbsp;秒</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>发布评论显示验证码:</strong>发布评论时，必须填写验证码 </td>
                <td width="*">
                    <div>会员:<?=form_bool('modcfg[member_seccode]', $modcfg['member_seccode'])?></div>
                    <div>游客:<?=form_bool('modcfg[guest_seccode]', $modcfg['guest_seccode'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>评论内容字数限制 </strong>定义评论内容的字符限制</td>
                <td>
                    <input type="text" name="modcfg[content_min]" value="<?=$modcfg['content_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[content_max]" value="<?=$modcfg['content_max']?>" class="txtbox5" />
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>评论单页显示数量:</strong>设置页面的评论显示最大数量</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('5'=>'5条','10'=>'10条','20'=>'20条','40'=>'40条','50'=>'50条'),$modcfg['list_num'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>评论排序方式:</strong>设置评论显示顺序</td>
                <td><?=form_radio('modcfg[listorder]',array('asc'=>'最早评论在前','desc'=>'最新评论在前'),$modcfg['addtime']?$modcfg['addtime']:'asc')?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>