<?php
!defined('IN_MUDDER') && exit('Access Denied');

$_G['dns'] = array();
// ���ݿ����������(host:port)
$_G['dns']['dbhost'] = 'localhost';
// ���ݿ��ʺ�
$_G['dns']['dbuser'] = 'root';
// ���ݿ�����
$_G['dns']['dbpw'] = 'root';
// ���ݿ�����
$_G['dns']['dbname'] = 'modoer';
// ���ݱ�ǰ׺
$_G['dns']['dbpre'] = 'modoer_';
// ���ݿ�־����� 0=�ر�, 1=��
$_G['dns']['pconnect'] = 0;
// ���ݿ����
$_G['dns']['dbcharset'] = 'gbk';
// ��ѯ���ݿ�ǰ����Ping������������ݿ����Ӷ�ʧ����
$_G['dns']['ping'] = FALSE;
// ��ҳ����
$_G['charset'] = 'gb2312';
// Cookieǰ׺
$_G['cookiepre'] = 'ATWXU_';
// Cookie��Ч·��
$_G['cookiepath'] = '/';
// Cookie������
$_G['cookiedomain'] = '';
// �ɷ�ֹ�����ķ�����������ɵľܾ����񹥻�
$_G['attackevasive'] = 0;
// �����ʱ������ʱ�䣬����ʱ��Ϊ8���� PHP5 ��Ч
$_G['timezone'] = 8;
// 0=�ر�, 1=cookie ˢ������, 2=���ƴ������, 3=cookie+��������
$_G['admincp'] = array();
// ��̨��������Ƿ���֤����Ա��IP,1=��[��ȫ],0=����ڹ���Ա�޷���½��̨ʱ����0��
$_G['admincp']['checkip'] = 1;
// ��̨�����ļ������ļ���
$_G['admincp']['dir'] = 'admin';
// Ĭ��ʵ�����԰�
$_G['lang_directory'] = 'CH';
// �Ƿ����������޸�ģ��
$_G['modify_template'] = FALSE;
