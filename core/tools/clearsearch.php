<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool',FALSE);
class msm_tool_clearsearch extends msm_tool {

    protected $name = '������ڵ���������';
    protected $descrption = 'ɾ��search_cache�����Ѿ����ڵĻ����¼��';

    public function run() {
        $cfg = _G('loader')->variable('config');
        $search_life = (int) $cfg['search_life'];
        empty($search_life) && $search_life = 60;
        $time = $this->timestamp - $search_life * 60;
        _G('db')->from('dbpre_search_cache')->where_less('dateline', $time)->delete();
        $this->completed = true;
    }

}
?>