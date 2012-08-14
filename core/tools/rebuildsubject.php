<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_rebuildsubject extends msm_tool {

    protected $name = '�ؽ������ͳ������';
    protected $descrption = '��������ͳ��������������������Լ�ͼƬ�����ȡ�';

    public function run() {
        $offset = _get('offset', 300, MF_INT_KEY);
        $start = _get('start', 0, MF_INT_KEY);
        $count = _G('db')->from('dbpre_subject')->count();
        $list = _G('db')->from('dbpre_subject')->select('sid')->order_by('sid')->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                $sids[] = $val['sid'];
            }
            $list->free_result();
            _G('loader')->model('item:subject')->rebuild($sids);
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '�����ؽ�����...'.($start).'-'.($this->params['start']);
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '���β�����������',
            'des' => '���˳���500��',
            'content' => form_input('offset', '300', 'txtbox4'),
        );
        return $elements;
    }

}
?>