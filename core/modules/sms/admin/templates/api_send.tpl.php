<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <form method="post" action="<?=cpurl($module,$act,'progress_send')?>" name="myform">
    <div class="space">
        <div class="subtitle">短信发送 - <?=$API->get_name()?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr>
                <td class="altbg1" width="120">手机号</td>
                <td width="*"><input type="text" name="mobile" class="txtbox"></td>
            </tr>
            <tr>
                <td class="altbg1">短信内容</td>
                <td><textarea name="message" style="width:500px;height:80px;"></textarea></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="api" value="<?=$API->get_apiname()?>">
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
        <button type="button" class="btn" onclick="history.go(-1);">返回</button>
    </center>
</form>
</div>