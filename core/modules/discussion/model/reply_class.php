<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_discussion_reply extends ms_model {

    var $table = 'dbpre_discussion_reply';
    var $key = 'rpid';

    var $modcfg = '';
    var $topic = null;
    var $model_flag = 'discussion';

    var $post_topic = array();

    function __construct() {
        parent::__construct();
        $this->init_field();
        $this->modcfg = $this->variable('config');
        $this->topic = $this->loader->model('discussion:topic');
    }

    function init_field() {
        $this->add_field('tpid,content');
        $this->add_field_fun('tpid', 'intval');
        $this->add_field_fun('content', '_TA');
    }

    function save($post,$rpid=null) {
        $this->loader->helper('msubb');
        $edit = $rpid!=null;
        if($edit) {
            $detail = $this->read($rpid);
            if(empty($detail)) redirect('discussion_reply_empty');
            if(!$this->in_admin) {
                if($detail['uid']!=$this->global['user']->uid) redirect('discussion_reply_post_access');
                $post['status'] = $detail['status'];
            } else {
                if(!isset($post['status'])) $post['status'] = $detail['status'];
            }
        } else {
            $post['status'] = $this->modcfg['reply_check'] ?  0 : 1;
            $post['dateline'] = $this->timestamp;
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
        }
        $post['pictures'] = ''; //暂时没有图片上传
        $rpid = parent::save($post,$rpid,$edit);
        //提醒主题作者
        if(!$edit && $post['status']) {
            if($users = msubb::get_username($post['content'])) {
                $this->_notice_reply_at($users, $post['tpid'], $this->post_topic['subject'], 
                    array($post['uid']=>$post['username']));
            } else {
                $this->_notice_reply_lz($this->post_topic['uid'], $post['tpid'], $this->post_topic['subject'], 
                    array($post['uid']=>$post['username']));
            }
            //更新话题统计
            $this->db->from('dbpre_discussion_topic')
                ->set_add('replies', 1)
                ->set('replytime',$post['dateline'])
                ->where('tpid',$post['tpid'])
                ->update();
        }
        if(!$this->in_admin && !$post['status']) define('RETURN_EVENT_ID', 'CHECK');
        return $rpid;
    }

    function check_post(& $post, $isedit = FALSE) {
        $content = $post['content'] ? msubb::clear($post['content']) : '';
        if(!$content) redirect('discussion_reply_post_content_empty');

        $this->modcfg['reply_content_max'] = 10000;
        $this->modcfg['reply_content_min'] = 10;
        if(strlen_ex($post['content']) > $this->modcfg['reply_content_max'] || strlen_ex($post['content']) < $this->modcfg['reply_content_min']) {
            redirect(lang('discussion_reply_post_content_strlen',array($this->modcfg['reply_content_min'],$this->modcfg['reply_content_max'])));
        }

        if(!$isedit) {
            if(!$post['tpid']) redirect(lang('global_sql_keyid_invalid', 'tpid'));
            $this->post_topic = $this->topic->read($post['tpid']);
            if(!$this->post_topic['status']) redirect('discussion_reply_topic_not_audit');
            if(empty($this->post_topic)) redirect('discussion_reply_post_topic_empty');
            $isownerpost = $this->loader->model('item:subject')->is_mysubject($this->post_topic['sid'], $this->global['user']->uid);
            $post['isownerpost'] = (int) $isownerpost;
        }
        return $post;
    }

    function checkup($rpids) {
        $ids = parent::get_keyids($rpids);
        $r = $this->db->from($this->table)->where('rpid',$ids)->where('status',0)->get();
        if(!$r) return;
        $tpids = $notice = array();
        while ($v=$r->fetch_array()) {
            if(isset($tpids[$v['tpid']])) {
                $tpids[$v['tpid']]['count']++;
                if($v['dateline']>$tpids[$v['tpid']]['replytime']) {
                    $tpids[$v['tpid']]['replytime'] = $v['dateline'];
                }
            } else {
                $tpids[$v['tpid']]['count'] = 1;
                $tpids[$v['tpid']]['replytime'] = $v['dateline'];
            }
            //用于提醒话题作者
            $tpids[$v['tpid']]['notice'][$val['uid']] = $val['username'];
        }
        //更新状态
        $this->db->from($this->table)
            ->where('rpid',$ids)->set('status',1)->update();
        //话题数量和提醒
        if($tpids) {
            foreach ($tpids as $tpid => $value) {
                $topic = $this->topic->read($tpid, 'tpid,uid,subject');
                if(empty($topic)) continue;
                //主题回应增加数量和最后回应时间
                $this->db->from('dbpre_discussion_topic')
                ->set_add('replies', $value['count'])
                ->set('replytime',$value['replytime'])
                ->where('tpid',$tpid)
                ->update();
                //提醒主题作者
                $this->_notice_reply_lz($topic['uid'], $tpid, $topic['subject'], $value['notice']);
            }
        }
    }

    function delete_tpid($tpids) {
        $this->db->from($this->table)->where('tpid',$tpids)->delete();
    }

    function delete($rpids) {
        $ids = parent::get_keyids($rpids);
        $q = $this->db->from($this->table)->where('rpid',$ids)->get();
        if(!$q) return;
        $s = $d = array();
        while ($v = $q->fetch_array()) {
            $d[] = $v['rpid'];
            if(!$v['status']) continue;
            if(isset($s[$v['tpid']])) {
                $s[$v['tpid']]++;
            } else {
                $s[$v['tpid']] = 1;
            }
        }
        $q->free_result();
        if($d) parent::delete($d);
        if($s) foreach ($s as $tpid => $num) {
            $this->db->from('dbpre_discussion_topic')->where('tpid',$tpid)->set_dec('replies',$num)->update();
        }
    }

    //提醒
    //xxx 回应了 你的话题 xxx
    function _notice_reply_lz($uid, $tpid, $subject, $author) {
        if(!$uid||!$tpid||!$author) return;

        $c_username = '';
        foreach ($author as $xuid => $xusername) {
            if($xuid==$uid) continue;
            $c_username .= ',<a href="'.url("space/index/uid/$xuid").'" target="_blank">'.$xusername.'</a>';
        }
        if(!$c_username) return;
        $c_username = substr($c_username, 1);
        $c_subject = '<a href="'.url("discussion/topic/id/$tpid").'" target="_blank">'.$subject.'</a>';
        $note = lang('discussion_notice_new_reply',array($c_username, $c_subject));

        $this->loader->model('member:notice')->save($uid, $this->model_flag, 'reply', $note);
    }

    //提醒
    // xxx 在话题 yyy 中回复了你
    function _notice_reply_at($usernames, $tpid, $subject, $author) {
        $list=$this->db->from('dbpre_members')->where('username',$usernames)->select('uid')->get();
        if(!$list) return;
        while ($v=$list->fetch()) {
            if(isset($author[$v['uid']])) continue;
            $uids[] = $v['uid'];
        }
        $list->free();
        if(!$uids) return;
        foreach ($author as $xuid => $xusername) $c_username = '<a href="'.url("space/index/uid/$xuid").'" target="_blank">'.$xusername.'</a>';
        $c_subject = '<a href="'.url("discussion/topic/id/$tpid").'" target="_blank">'.$subject.'</a>';
        $note = lang('discussion_notice_new_reply_at',array($c_username, $c_subject));

        $this->loader->model('member:notice')->save($uids, $this->model_flag, 'reply', $note);
    }

}
?>