<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<tr>
    <td class="altbg1"><strong>������༭�Լ���ӵ�����:</strong>ͬʱ��Ҫ���������������������������򿪱༭���⹦�ܡ�</td>
    <td><?=form_bool('access[item_allow_edit_subject]', $access['item_allow_edit_subject'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>�������Ա�ϴ�ͼƬʱ�½����:</strong>�������Ա�ϴ�ͼƬʱ�½���ᡣ</td>
    <td><?=form_bool('access[item_create_album]', $access['item_create_album'])?></td>
</tr>
<tr>
    <td class="altbg1"><strong>���Ʊ����Ա�����������:</strong>�����û������������������ջ���Ϊ0��ʾ�����ƣ�-1Ϊ��������ӡ�</td>
    <td><?=form_input("access[item_subjects]",$access['item_subjects'],'txtbox4')?></td>
</tr>
<tr>
    <td class="altbg1"><strong>���Ʊ����ԱͼƬ�ϴ�����:</strong>�����û��ϴ�����ͼƬ�����������ջ���Ϊ0��ʾ�����ƣ�-1Ϊ�������ϴ���</td>
    <td><?=form_input("access[item_pictures]",$access['item_pictures'],'txtbox4')?></td>
</tr>