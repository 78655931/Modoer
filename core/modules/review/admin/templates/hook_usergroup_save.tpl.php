<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>���Ʊ����Ա��������:</strong>���ջ���Ϊ0��ʾ�����ƣ�-1Ϊ��������</td>
    <td><?=form_input("access[review_num]",$access['review_num'],'txtbox4')?></td>
</tr>
<tr>
    <td class="altbg1"><strong>���Ʊ����Ա�ظ�����:</strong></td>
    <td><?=form_bool("access[review_repeat]",$access['review_repeat'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>�������Ա�鿴��������:</strong></td>
    <td><?=form_bool("access[review_viewdigest]",$access['review_viewdigest'])?></td>
</tr>