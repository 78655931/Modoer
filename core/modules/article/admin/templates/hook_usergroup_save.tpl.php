<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组会员发布文章(投稿):</strong>开启本功能后，本组会员便可以在前台发布的文章</td>
    <td><?=form_bool('access[article_post]', $access['article_post'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员删除文章:</strong>开启本功能后，本组会员便可以在前台删除自己发布的文章</td>
    <td><?=form_bool('access[article_delete]', $access['article_delete'])?></td>
</tr>