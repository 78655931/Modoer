<?php
/**
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_review_flower {

    var $flag = 'review:flower';
    var $title = 'review_task_flower_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    // ���ɺ�̨������������ı�
    function form($cfg) {
        global $_G;
        $result = array();
        $_G['loader']->helper('form','item');
        $result[] = array(
            'title' => '�����ʻ�����',
            'des' => '������Ҫ���Ա�����������ʻ�������Ĭ��Ϊ 1 ��',
            'content' => form_input('config[num]',$cfg['num']>0?$cfg['num']:1,'txtbox4'),
        );
        $result[] = array(
            'title' => 'ʱ�����ƣ�Сʱ��',
            'des' => '���û�Ա������������������ʱ�����ƣ���Ա�ڴ�ʱ����δ���������������ȡ�������������ʧ�ܣ�0 ������Ϊ������',
            'content' => form_input('config[time_limit]',$cfg['time_limit'],'txtbox4'),
        );
        return $result; 
    }

    // ����������������ύ���
    function form_post($cfg) { 
        if(!$cfg['num'] || !is_numeric($cfg['num']) || $cfg['num'] < 1) redirect('�����������δ��дһ����Ч�������ʻ�������');
        if($cfg['time_limit'] && (!is_numeric($cfg['time_limit'])||$cfg['time_limit']<0)) redirect('�����������δ��дһ����Ч��ʱ�����ơ�');
        return true; 
    }

    // ���������� 0-100
    function progress(& $task_detail) {
        global $_G;
        $db =& $_G['db'];
        $taskid = $task_detail['taskid'];
        $db->from('dbpre_mytask');
        $db->where('uid',$_G['user']->uid);
        $db->where('taskid',$taskid);
        $mytask = $db->get_one();
        if(!$mytask) return 0;

        $cfg = @unserialize($task_detail['config']);
        if(!$cfg) return 0;

        if($cfg['time_limit']>0) {
            $timelimit = $cfg['time_limit'] * 3600;
            if($mytask['applytime'] + $timelimit < $_G['timestamp']) return -1;//����ʧ��
        }

        $db->from('dbpre_flowers');
        $db->where('uid', $_G['user']->uid);
        $db->where_more('dateline', $mytask['applytime']);
        if(!$total = $db->count()) return 0;
        $min = min($cfg['num'], $total);
        return round($min / $cfg['num'] * 100);
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