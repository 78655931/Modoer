<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//ȫ��������һЩĬ�ϲ�������
$get = array();
$get['keyword'] = _T($_GET['keyword']);
//��ת���Լ�������ҳ��
$search_file = get_url('coupon', 'index', $get,'',1,0);
location($search_file);
exit;
?>