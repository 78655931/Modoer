<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_favorite extends msm_item_itembase {

    var $table = 'dbpre_favorites';
    var $key = 'fid';

    function __construct() {
        parent::__construct();
        $this->init_field();
    }

    function init_field() {
        $this->add_field('id');
        $this->add_field_fun('id', 'intval');
    }

    function find($select, $where, $start, $offset, $total = TRUE) {
        $this->db->join($this->table,'f.id',$this->subject_table,'s.sid');
        $this->db->where('idtype','subject');
        $this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
        $this->db->select($select?$select:'f.fid,f.id,f.uid,f.username,f.addtime,s.name,s.subname,s.pid,s.catid');
        $this->db->order_by('f.addtime', 'DESC');
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function get_uids($sid) {
        $r = $this->db->from($this->table)
            ->where('idtype','subject')
            ->where('id',$sid)->get();
        if(!$r) return;
        $result = array();
        while($val=$r->fetch_array()) {
            $result[] = $val['uid'];
        }
        $r->free_result();
        return $result;
    }

    function save($post) {
        $this->check_post($post);
        if($this->submitted($this->global['user']->uid, $post['id'])) {
            redirect('item_favorite_submitted');
        }

        $post['uid'] = $this->global['user']->uid;
        $post['username'] = $this->global['user']->username;
        $post['idtype'] = 'subject';
        $post['addtime'] = $this->global['timestamp'];

        $fid = parent::save($post, null, FALSE, FALSE, FALSE);
        $this->subject_total($post['id']);
        $this->_feed($post['id']);
        return $fid;
    }

    function submitted($uid, $sid) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('idtype', 'subject');
        $this->db->where('id', $sid);
        return $this->db->count() >= 1;
    }

    function check_post(& $post, $isedit = FALSE) {
        if($isedit && !is_numeric($post['id'])) {
            redirect(lang('global_sql_keyid_invalid', 'id'));
        }
    }

    function delete($fids, $update_total = TRUE) {
        $fids = parent::get_keyids($fids);
        $where = array('fid'=>$fids);
        $this->_delete($where, $update_total);
    }

    function delete_sids($sids, $update_total = FALSE) {
        $sids = parent::get_keyids($sids);
        $where = array('idtype'=>'subject','id'=>$sids);
        $this->_delete($where, $update_total);
    }

    function subject_total($sid, $num=1) {
        if(!$num) return;
        $fun = $num > 0 ? 'set_add' : 'set_dec';
        $num = abs($num);
        $this->db->from('dbpre_subject');
        $this->db->where('sid', $sid);
        $this->db->$fun('favorites',$num);
        $this->db->update();
    }

    function _delete($where, $update_total=TRUE) {
        $this->db->from($this->table);
        $this->db->where('idtype','subject');
        $this->db->where($where);
        if(!$q = $this->db->get()) return ;
        $delids = $sids = array();
        while($v=$q->fetch_array()) {
            $delids[] = $v['fid'];
            if(!$update_total) continue;
            if(!isset($sids[$v['sid']])) {
                $sids[$v['id']]=0;
            }
            $sids[$v['id']]++;
        }
        $q->free_result();
        if($update_total && $sids) {
            foreach($sids as $sid=>$num) {
                $this->subject_total($sid,-$num);
            }
        }
        $this->db->from($this->table);
        $this->db->where('fid', $delids);
        $this->db->delete();
    }

    function _feed($sid) {
        if(!$sid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        $S =& $this->loader->model('item:subject');
        if(!$detail = $S->read($sid,'*', FALSE)) return;
        $model = $this->get_model($detail['pid'], TRUE);

        $feed = array();
        $feed['icon'] = lang('item_favorite_feed_icon');
        $feed['title_template'] = lang('item_favorite_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/".$this->global['user']->uid).'">' . $this->global['user']->username . '</a>',
            'item_unit' => $model['item_unit'],
            'item_name' => $model['item_name'],
        );
        $feed['body_template'] = lang('item_favorite_feed_body_template');
        $title = $detail['name'] . ($detail['subname'] ? "($detail[subname])" : '');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("item/detail/id/$detail[sid]").'">'.$title.'</a>',
            'review' => '<a href="'.url("review/member/ac/add/type/item_subject/id/$detail[sid]").'">'.lang('item_review').'</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $this->global['user']->uid, $this->global['user']->username, $feed);
    }
}
?>