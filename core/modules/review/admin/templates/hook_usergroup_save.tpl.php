<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>限制本组会员点评数量:</strong>留空或者为0表示不限制，-1为不允许发表。</td>
    <td><?=form_input("access[review_num]",$access['review_num'],'txtbox4')?></td>
</tr>
<tr>
    <td class="altbg1"><strong>限制本组会员重复点评:</strong></td>
    <td><?=form_bool("access[review_repeat]",$access['review_repeat'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员查看精华点评:</strong></td>
    <td><?=form_bool("access[review_viewdigest]",$access['review_viewdigest'])?></td>
</tr>