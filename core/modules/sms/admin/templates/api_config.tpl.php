<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <form method="post" action="<?=cpurl($module,$act,'progress_config')?>" name="myform">
    <div class="space">
        <div class="subtitle">���Žӿڹ��� - <?=$API->get_name()?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="45%">˵��</td>
                <td width="*">����</td>
            </tr>
            <?=$API->get_form()?>
        </table>
    </div>
    <center>
        <input type="hidden" name="api" value="<?=$API->get_apiname()?>">
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
        <button type="button" class="btn" onclick="history.go(-1);">����</button>
    </center>
</form>
</div>