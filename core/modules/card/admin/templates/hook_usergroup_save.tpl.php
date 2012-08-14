<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员申请会员卡:</strong>开启本功能后，本组会员便可以在前台申请会员卡</td>
    <td><?=form_bool('access[card_apply]', $access['card_apply'])?></td>
</tr>