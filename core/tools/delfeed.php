<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delfeed extends msm_tool {

    protected $name = '删除Feed事件记录';
    protected $descrption = '清理系统内过期的Feed记录，提高数据查询效率。';

    public function run() {
        $days_ago = _get('days_ago', 0, MF_INT);
        _G('db')->from('dbpre_member_feed');
        if($days_ago > 0) {
            $datetime = strtotime("-{$days_ago} days", _G('timestamp'));
            _G('db')->where_less('dateline', $datetime);
        }
        _G('db')->delete();
        $this->message = "共删除 " . _G('db')->affected_rows() . " 条用户提醒。";
        $this->completed = true;
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '删除多少天以前的Feed记录',
            'des' => '不限制时间请输入 0',
            'content' => form_input('days_ago', '90', 'txtbox4'),
        );
        return $elements;
    }

}
?>