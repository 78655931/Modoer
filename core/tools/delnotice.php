<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delnotice extends msm_tool {

    protected $name = 'ɾ�������û�����';
    protected $descrption = '�����û��������ݣ�������ݲ�ѯЧ�ʡ�';

    public function run() {
        $dnot_del_unread = _get('dnot_del_unread', 100, MF_INT_KEY);
        $days_ago = _get('days_ago', 0, MF_INT);
        _G('db')->from('dbpre_notice');
        if($dnot_del_unread) _G('db')->where('isread', '0');
        if($days_ago > 0) {
            $datetime = strtotime("-{$days_ago} days", _G('timestamp'));
            _G('db')->where_less('dateline', $datetime);
        }
        _G('db')->delete();
        $this->message = "��ɾ�� " . _G('db')->affected_rows() . " ���û����ѡ�";
        $this->completed = true;
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '��ɾ��δ������',
            'des' => '',
            'content' => form_bool('dnot_del_unread', 1),
        );
        $elements[] = 
            array(
            'title' => 'ɾ����������ǰ���û�����',
            'des' => '������ʱ�������� 0',
            'content' => form_input('days_ago', '30', 'txtbox4'),
        );
        return $elements;
    }

}
?>