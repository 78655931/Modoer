<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delfeed extends msm_tool {

    protected $name = 'ɾ��Feed�¼���¼';
    protected $descrption = '����ϵͳ�ڹ��ڵ�Feed��¼��������ݲ�ѯЧ�ʡ�';

    public function run() {
        $days_ago = _get('days_ago', 0, MF_INT);
        _G('db')->from('dbpre_member_feed');
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
            'title' => 'ɾ����������ǰ��Feed��¼',
            'des' => '������ʱ�������� 0',
            'content' => form_input('days_ago', '90', 'txtbox4'),
        );
        return $elements;
    }

}
?>