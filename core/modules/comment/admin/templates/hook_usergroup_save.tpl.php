<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>禁止本组会员使用评论功能:</strong>关闭会员对进行评论（使用评论功能的模块）的功能。</td>
    <td><?=form_bool('access[comment_disable]', $access['comment_disable'])?></td>
</tr>