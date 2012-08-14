<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td><b>本功能只适合 Discuz 6.0或以下版本，Discuz 6.1 UC版请使用 UCenter 整合。</b><br />启用了通行证后，会员注册、登录、退出、更改密码将不使用Mudder的会员系统，如果官方没有提供整某系统的接口文件，请参考本系统的通行证接口的说明文档，在第三方系统的这几个功能模块中加入调用本系统提供的远程API接口即可，Mudder的反向接口允许整合系统与主站在不同的服务器，但必须用相同的主域名，即是：bbs.abc.com 和 www.abc.com 这样的关系。<br />
    </td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">通行证设置(反向整合)</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%" class="altbg1">启用通行证：</td>
                <td width="50%"><input type="radio" name="passport[enable]" value="1" <?=$passport_enable[1]?> /> 是&nbsp;&nbsp;<input type="radio" name="passport[enable]" value="0" <?=$passport_enable[0]?> /> 否</td>
            </tr>
            <tr>
                <td class="altbg1">通行证私有密匙：</td>
                <td><?=$_config['authkey']?></td>
            </tr>
            <tr>
                <td class="altbg1">Cookie前缀：</td>
                <td><?=$cookiepre?></td>
            </tr>
            <tr>
                <td class="altbg1">整合系统名称：</td>
                <td><input type="text" name="passport[systemname]" class="txtbox" value="<?=$systemname?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">整合系统主页：</td>
                <td><input type="text" name="passport[index_url]" class="txtbox" value="<?=$index_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员注册网址：</td>
                <td><input type="text" name="passport[reg_url]" class="txtbox" value="<?=$reg_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员登陆网址：</td>
                <td><input type="text" name="passport[login_url]" class="txtbox" value="<?=$login_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员退出网址：</td>
                <td><input type="text" name="passport[logout_url]" class="txtbox" value="<?=$logout_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">更改密码网址：</td>
                <td><input type="text" name="passport[cpwd_url]" class="txtbox" value="<?=$cpwd_url?>" /></td>
            </tr>
        </table>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    </div>
<?=form_end()?>
</div>