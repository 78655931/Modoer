<?php
/**
 * ͷ�������������࿪��������
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_member_avatar {

    var $flag = 'member:avatar';
    var $title = 'member_task_avatar_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    // ���ɺ�̨������������ı�
    function form($cfg) { return; }

    // ����������������ύ���
    function form_post($cfg) { return true; }

    // ���������� 0-100
    function progress(& $task_detail) {
        global $_G;
        //���ͷ���Ƿ����
        return $_G['user']->check_avatar() ? 100 : 0;
    }

    // ��Ա��������ʱ����
    function apply($taskid) {}

    // ��̨ɾ������ʱ����
    function delete($taskid) {}

    // ��װ��������ʱ����
    function install() {}

    // ж�ر�������ʱ����
    function unstall() {}
}
?>