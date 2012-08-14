<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>
        <li>可用于 PHPWind 或者 Discuz6.0 及以下版本整合整合。</li>
        <li>提交后，系统会自动更新 ./api/mudder_passport_client.php 文件，然后请将这个文件复制到整合系统根目录下，随后需要修改整合系统注册和登录部分的代码。</li>
        <li>如果您更改了网址，请重新操作一次提交本页，复制到整合系统根目录的过程。</li>
    </td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">通信证反向整合</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="15%" class="altbg1">开启通信证整合：</td>
                <td width="80%"><?=form_bool('passport[enable]',$passport['enable'])?></td>
            </tr>
            <tr>
                <td class="altbg1">通行证私有密匙：</td>
                <td><?=$_G['cfg']['authkey']?></td>
            </tr>
            <tr>
                <td class="altbg1">Cookie前缀：</td>
                <td><?=$_G['cookiepre']?></td>
            </tr>
            <tr>
                <td class="altbg1">整合系统名称：</td>
                <td><input type="text" name="passport[systemname]" class="txtbox" value="<?=$passport['systemname']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">整合系统主页：</td>
                <td><input type="text" name="passport[index_url]" class="txtbox" value="<?=$passport['index_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员注册网址：</td>
                <td><input type="text" name="passport[reg_url]" class="txtbox" value="<?=$passport['reg_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员登陆网址：</td>
                <td><input type="text" name="passport[login_url]" class="txtbox" value="<?=$passport['login_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">会员退出网址：</td>
                <td><input type="text" name="passport[logout_url]" class="txtbox" value="<?=$passport['logout_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">更改密码网址：</td>
                <td><input type="text" name="passport[cpwd_url]" class="txtbox" value="<?=$passport['cpwd_url']?>" /></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>