<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="space">
        <div class="subtitle">���Žӿڹ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25"><center>ʹ��</center></td>
                <td width="200">����</td>
                <td width="*">����</td>
                <td width="160">����</td>
            </tr>
            <?foreach($APIS as $key => $API) {?>
            <tr>
                <td><center><?=$API->is_used()?'��':'��'?></center></td>
                <td><?=$API->get_name()?></td>
                <td><?=$API->get_descrption()?></td>
                <td>
                    <a href="<?=cpurl($module, $act, 'config', array('api'=>$key))?>">����</a>
                    <?if(!$API->is_used()):?>
                    <a href="<?=cpurl($module, $act, 'use', array('api'=>$key))?>">����</a>
                    <?endif;?>
                    <a href="<?=cpurl($module, $act, 'send', array('api'=>$key))?>">���Ͳ���</a>
                </td>
            </tr>
            <?}?>
        </table>
    </div>
</div>