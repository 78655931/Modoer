<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">安全设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>网站加密码:</strong>Cookie和通行证加密码，修改后，会员和后台用户需要重新登录，无特殊情况请不要修改。</td>
                <td width="*"><?=form_input("setting[authkey]", $config['authkey'], "txtbox")?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>IP 访问列表:</strong>只有当用户处于本列表中的 IP 地址时才可以访问本网站，列表以外的地址访问将视为 IP 被禁止，仅适用于诸如企业、学校内部网站等极个别场合。本功能对管理员没有特例，如果管理员不在此列表范围内将同样不能登录，请务必慎重使用本功能。每个 IP 一行，请输入完整地址，例如 "192.168.1.241"，留空为所有 IP 除明确禁止的以外均可访问</td>
                <td><textarea name="setting[useripaccess]" rows="8" cols="40" class="txtarea"><?=$config['useripaccess']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>管理员后台 IP 访问列表:</strong>只有当管理员处于本列表中的 IP 地址时才可以访问网站系统设置，列表以外的地址访问将无法访问，但仍可访问网站前端用户界面，请务必慎重使用本功能。每个 IP 一行，请输入完整地址，例如 "192.168.1.241"，留空为所有 IP 除明确禁止的以外均可访问系统设置</td>
                <td><textarea name="setting[adminipaccess]" rows="6" cols="40" class="txtarea"><?=$config['adminipaccess']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>禁止 IP 访问列表:</strong>当游客处于本列表中的 IP 地址时将无法访问网站。每个 IP 一行，请输入完整地址，例如 "192.168.1.241"，留空为不禁止的任何 IP 访问</td>
                <td><textarea name="setting[ban_ip]" rows="6" cols="40" class="txtarea"><?=$config['ban_ip']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>