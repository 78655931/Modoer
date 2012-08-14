<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_delmessage extends msm_tool {

    protected $name = '删除过期短信息';
    protected $descrption = '清理长期存在的用户短信息，提高数据查询效率。如您整合了uc，请进入uc控制台删除会员短信息。';

    public function run() {
        $dnot_del_unread = _get('dnot_del_unread', 100, MF_INT_KEY);
        $days_ago = _get('days_ago', 0, MF_INT);

        _G('db')->from('dbpre_pmsgs');
        if($dnot_del_unread) _G('db')->where('new', '0');
        if($days_ago > 0) {
            $datetime = strtotime("-{$days_ago} days", _G('timestamp'));
            _G('db')->where_less('posttime', $datetime);
        }
        _G('db')->delete();
        $this->message = "共删除 " . _G('db')->affected_rows() . " 条短信息。";
        $this->completed = true;
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '不删除未读短信息',
            'des' => '',
            'content' => form_bool('dnot_del_unread', 1),
        );
        $elements[] = 
            array(
            'title' => '删除多少天以前的短信息',
            'des' => '不限制时间请输入 0',
            'content' => form_input('days_ago', '30', 'txtbox4'),
        );
        return $elements;
    }

}
?>