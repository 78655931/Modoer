<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
//ȫ��������һЩĬ�ϲ�������
$get = array();
$get['catid'] = '0';
$get['keyword'] = trim($_GET['keyword']);
//��ת���Լ�������ҳ��
$search_file = get_url('article', 'list', $get,'',1,0);
location($search_file);
exit;
?>