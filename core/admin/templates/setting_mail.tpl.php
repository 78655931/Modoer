<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">邮件设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>邮件发送调试功能:</strong>如果发现邮件发送失败时，可以打开本功能，既可以看到发送失败的具体问题。</td>
                <td width="*">
                    <?=form_bool('setting[mail_debug]',$config['mail_debug'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>是否启用 SMTP 方式发送邮件:</strong>如果服务器支持PHP的 mail 函数，则不需要进行设置</td>
                <td>
                    <?=form_bool('setting[mail_use_stmp]',$config['mail_use_stmp'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SMTP 服务器:</strong>尽量使用企业邮局，不要使用免费邮箱，一般都有发送限制</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp]" value="<?=$config['mail_stmp']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SMTP 服务器端口:</strong>默认为端口为25</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_port]" value="<?=($config['mail_stmp_port']?$config['mail_stmp_port']:'25')?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>用户邮箱:</strong>企业邮局信箱需填写完整，需要@域名后缀</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_email]" value="<?=$config['mail_stmp_email']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>用户帐号:</strong>一般用户邮箱和用户账号是相同的</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_username]" value="<?=$config['mail_stmp_username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>用户密码:</strong>填写邮箱账号密码</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_password]" value="<?=$config['mail_stmp_password']?'******':''?>" /></td>
            </tr>
        </table>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    </div>
<?=form_end()?>
</div>