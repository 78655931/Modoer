<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function display(d) {
    $('#tr_gbook_guest').css("display",d);
    $('#tr_seccode').css("display",d);
}
</script>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>如果您开启了 Modoer 个人空间跳转到 UCHome 的个人空间时，本模块功能将失效。</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - 参数设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">显示配置</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>个人空间的菜单组:</strong>设置前台个人空间的导航菜单；<span class="font_2">模板仅支持 1 级分类，地址内支持参数<span class="font_1">(uid)</span>替换成访问空间的真实uid号</span></td>
                <td width="*">
                <select name="modcfg[space_menuid]">
                    <option value="">==选择菜单组==</option>
                    <?=form_menu_main($modcfg['space_menuid'])?>
                </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>个人空间默认风格:</strong>会员注册时默认设置的个人空间风格</td>
                <td><select name="modcfg[templateid]">
                    <option value="0">无(不使用风格)</option>
                    <?=form_template('space', $modcfg['templateid'])?>
                </select></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>启用游客记录:</strong>当启动游客记录功能，个人空间将记录游客的ID（前提是该游客已登录网站）</td>
                <td width="*"><?=form_bool('modcfg[recordguest]', $modcfg['recordguest'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>空间默认标题</strong>可用变量说明：网站名：{sitename}；注册会员名：{username}</td>
                <td><input type="text" name="modcfg[spacename]" value="<?=$modcfg['spacename']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>空间默认说明</strong>可用变量同上</td>
                <td><input type="text" name="modcfg[spacedescribe]" value="<?=$modcfg['spacedescribe']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>首页点评显示条数目:</strong>在个人空间首页中，显示的点评数目</td>
                <td><?=form_radio('modcfg[index_reviews]',array('5'=>'5条','10'=>'10条','20'=>'20条'),$modcfg['index_reviews'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>首页留言显示条数目:</strong>在个人空间首页中，显示的留言数目</td>
                <td><?=form_radio('modcfg[index_gbooks]',array('5'=>'5条','10'=>'10条','20'=>'20条'),$modcfg['index_gbooks'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>点评页面显示条数目:</strong>在个人空间点评栏目中，显示的点评数目</td>
                <td><?=form_radio('modcfg[reviews]',array('10'=>'10条','20'=>'20条','40'=>'40条'),$modcfg['reviews'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>留言页面显示条数目:</strong>在个人空间点评栏目中，显示的留言数目</td>
                <td><?=form_radio('modcfg[gbooks]',array('10'=>'10条','20'=>'20条','40'=>'40条'),$modcfg['gbooks'])?></td>
            </tr>
        </table>
    </div>

    <center>
        <input type="submit" name="dosubmit" value=" 提交 " class="btn" />
    </center>
<?=form_end()?>
</div>