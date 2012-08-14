<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2012 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool', FALSE);
class msm_tool_rebuildmember extends msm_tool {

    protected $name = '重建会员统计数据';
    protected $descrption = '包括重新统计主会员点评总数，图片数量，主题数量。';

    public function run() {
        $users = _input('users','',MF_TEXT);
        if($users) {
            $this->rebuild_users($users);
        } else {
            $this->rebuild_all();
        }
    }

    public function create_form() {
        $this->loader->helper('form');
        $elements = array();
        $elements[] = 
            array(
            'title' => '指定用户账号',
            'des' => '<p>多个会员账号请使用逗号“,”进行分隔；留空表示重建全部会员统计数据。</p>',
            'content' => form_input('users', '', 'txtbox'),
        );
        return $elements;
    }

    private function rebuild_users($users) {
        $uids = $this->_get_uids($users);
        if(!$uids) {
            redirect('对不起，您指定的账号不存在。');
        }
        foreach ($uids as $uid) {
            $this->_rebuild($uid);
        }
        $this->completed = true;
    }

    private function rebuild_all() {
        $offset =  300;
        $start = _get('start', 0, MF_INT_KEY);
        $count = _G('db')->from('dbpre_members')->count();
        $list = _G('db')->from('dbpre_members')->select('uid')->order_by('uid')->limit($start, $offset)->get();
        if(!$list) {
            $this->completed = true;
        } else {
            $sids = array();
            while ($val=$list->fetch_array()) {
                $this->_rebuild($val[uid]);
            }
            $list->free_result();
            $this->params['start'] = $start + $offset;
            $this->params['offset'] = $offset;
            $this->message = '正在会员表统计数据...'.($start).'-'.($this->params['start']);
        }
    }

    private function _get_uids($users) {
        if(!$users) return;
        $db = _G('db');
        $users = explode(',', str_replace(array("，"," "),'',$users));
        $db->from('dbpre_members');
        $db->where('username',$users);
        $db->select('uid');
        $q = $db->get();
        if(!$q) return;
        $uids = array();
        while ($v = $q->fetch_array()) {
            $uids[] = $v['uid'];
        }
        $q->free_result();
        return $uids;
    }

    private function _rebuild($uid) {
        $reviews = $this->_get_reviews($uid);
        $pictures = $this->_get_pictures($uid);
        $subjects = $this->_get_subjects($uid);

        $db =& _G('db');
        $db->from('dbpre_members');
        $db->where('uid',$uid);
        $db->set('reviews',$reviews);
        $db->set('pictures',$pictures);
        $db->set('subjects',$subjects);
        $db->update();
    }

    private function _get_reviews($uid) {
        $db =& _G('db');
        $db->from('dbpre_review');
        $db->where('uid',$uid);
        $db->where('status',1);
        return $db->count();
    }

    private function _get_pictures($uid) {
        $db =& _G('db');
        $db->from('dbpre_pictures');
        $db->where('uid',$uid);
        $db->where('status',1);
        return $db->count();
    }

    private function _get_subjects($uid) {
        $db =& _G('db');
        $db->from('dbpre_subject');
        $db->where('cuid',$uid);
        $db->where('status',1);
        return $db->count();
    }

}
?>