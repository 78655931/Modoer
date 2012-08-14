<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairsubject extends msm_tool {

    protected $name = '�޸��쳣���������';
    protected $descrption = '�޸������޷��������б�ҳǰ̨�����⣬�����߽�ϵͳ��������ʱʹ�ã�ʹ��ǰ�뱸�����ݿ⣬�Է��������⡣';

    public function run() {
        $offset = _get('offset', 100, MF_INT_KEY);
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
            $S =& _G('loader')->model('item:subject');
            foreach($sids as $sid) {
                $detail = $S->read($sid);
                //����
                $catids = trim(trim($detail['sub_catids'],'|') . '|' . trim($detail['minor_catids'],'|'), '|');
                if(!$catids) {
                    $catids = array($detail['catid']);
                } else {
                    $catids = explode('|', $catids);
                }
                $S->save_att_category($sid,$catids, $detail['status']);
                //����
                $S->save_att_area($sid, $detail['aid'], $detail['status']);
            }
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '�����޸�����...'.($start).'-'.($this->params['start']);
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '���β�����������',
            'des' => '����ÿһ�����⴦����϶࣬���˳���300��',
            'content' => form_input('offset', '100', 'txtbox4'),
        );
        return $elements;
    }

}
?>