<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>允许本组编辑自己添加的主题:</strong>同时还要在主题所属的主分类参数设置里打开编辑主题功能。</td>
    <td><?=form_bool('access[item_allow_edit_subject]', $access['item_allow_edit_subject'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>允许本组会员上传图片时新建相册:</strong>允许本组会员上传图片时新建相册。</td>
    <td><?=form_bool('access[item_create_album]', $access['item_create_album'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>限制本组会员添加主题数量:</strong>限制用户添加主题的数量，留空或者为0表示不限制，-1为不允许添加。</td>
    <td><?=form_input("access[item_subjects]",$access['item_subjects'],'txtbox4')?></td>
</tr>
<tr>
    <td class="altbg1"><strong>限制本组会员图片上传数量:</strong>限制用户上传主题图片的数量，留空或者为0表示不限制，-1为不允许上传。</td>
    <td><?=form_input("access[item_pictures]",$access['item_pictures'],'txtbox4')?></td>
</tr>