<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>�������Ա��������(Ͷ��):</strong>���������ܺ󣬱����Ա�������ǰ̨����������</td>
    <td><?=form_bool('access[article_post]', $access['article_post'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>�������Աɾ������:</strong>���������ܺ󣬱����Ա�������ǰ̨ɾ���Լ�����������</td>
    <td><?=form_bool('access[article_delete]', $access['article_delete'])?></td>
</tr>