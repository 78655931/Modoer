<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>禁止本组会员兑换礼品:</strong>关闭会员对进行评论（使用评论功能的模块）的功能。</td>
    <td><?=form_bool('access[exchange_disable]', $access['exchange_disable'])?></td>
</tr>