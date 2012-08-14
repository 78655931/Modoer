<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_repairsubject extends msm_tool {

    protected $name = '修复异常主题的数据';
    protected $descrption = '修复主题无法出现在列表页前台的问题，本工具仅系统出现问题时使用，使用前请备份数据库，以防出现问题。';

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
                //分类
                $catids = trim(trim($detail['sub_catids'],'|') . '|' . trim($detail['minor_catids'],'|'), '|');
                if(!$catids) {
                    $catids = array($detail['catid']);
                } else {
                    $catids = explode('|', $catids);
                }
                $S->save_att_category($sid,$catids, $detail['status']);
                //地区
                $S->save_att_area($sid, $detail['aid'], $detail['status']);
            }
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '正在修复主题...'.($start).'-'.($this->params['start']);
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '单次操作主题数量',
            'des' => '由于每一个主题处理步骤较多，不宜超过300个',
            'content' => form_input('offset', '100', 'txtbox4'),
        );
        return $elements;
    }

}
?>