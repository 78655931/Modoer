<?php
/**
 * ��Ա��Ϣ����
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_member_profile {

    var $flag = 'member:profile';
    var $title = 'member_task_profile_title';
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
        $result = array();
        $result[] = array(
            'title' => '��Ҫ���Ƶ��ֶ�',
            'des' => '��ѡ��Ҫ��Ա���Ƶ��ֶ�',
            'content' => form_check('config[fields][]',
                array(
                    'realname'=>'��ʵ����',
                    'mobile'=>'�ֻ�����',
                    'gender'=>'�Ա�',
                    'birthday'=>'����',
                    'alipay'=>'֧����',
                    'qq'=>'QQ',
                    'msn'=>'MSN',
                    'address'=>'��ַ',
                    'zipcode'=>'�ʱ�',
                ),
                $cfg['fields'],
                '',
                '&nbsp;'
            ),
        );
        return $result;
    }

    // ����������������ύ���
    function form_post($cfg) {
        if(!$cfg['fields']) redirect('��δѡ�û�����Ҫ���Ƶ��ֶΡ�');
        return true; 
    }

    // ���������� 0-100
    function progress(& $task_detail) {
        global $_G;

        $uid = $_G['user']->uid;
        $MP = $_G['loader']->model('member:profile');
        $profile = $MP->read($uid);

        $config = unserialize( $task_detail['config'] );
        if($config) $fields = $config['fields'];
        if(!$config) return 0;

        $s = 0;
        foreach($fields as $k) {
            if(!empty($_G['user']->$k)) $s++;
        }
        if(!$s) return 0;

        $p = round($s / count($fields) * 100);
        return $p;
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