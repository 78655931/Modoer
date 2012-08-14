<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_discussion_topic extends ms_model {

    var $table = 'dbpre_discussion_topic';
	var $key = 'tpid';

    var $modcfg = '';
    var $subject = null;
    var $model_flag = 'discussion';

	function __construct() {
		parent::__construct();
		$this->init_field();
        $this->modcfg = $this->variable('config');
        $this->subject = $this->loader->model('item:subject');
	}

	function init_field() {
		$this->add_field('sid,subject,uid,username,content,pictures');
		$this->add_field_fun('sid,uid', 'intval');
		$this->add_field_fun('content', '_TA');
	}

    function save($post,$tpid=null) {
        $edit = $tpid!=null;
        if($edit) {
            $detail = $this->read($tpid);
            if(empty($detail)) redirect('discussion_topic_empty');
            if(!$this->in_admin) {
                if($detail['uid']!=$this->global['user']->uid) redirect('global_op_access');
                $post['status'] = $detail['status'];
            } else {
                if(!isset($post['status'])) $post['status'] = $detail['status'];
            }
        } else {
            $post['status'] = $this->modcfg['topic_check'] ? 0 : 1;
            $post['replytime'] = $post['dateline']  = $this->timestamp;
            $post['uid'] = $this->global['user']->uid;
            $post['username'] = $this->global['user']->username;
        }
        $post['pictures'] = $this->post_image($post['pictures'], $detail['pictures']);
        if(!$post['pictures']) 
            $post['pictures'] = '';
        else
            $post['pictures'] = serialize($post['pictures']);
        $tpid = parent::save($post, $tpid, $edit);
        if(!$this->in_admin && !$post['status']) define('RETURN_EVENT_ID', 'CHECK');
        if($post['status']==1 && !$edit) $this->_feed($tpid);
        return $tpid;
    }

    function post_image($pics, $old = null) {
        if(!$pics && !$old) return null;
        if($old) {
            if(is_serialized($old)) $old = unserialize($old);
            if(is_array($old)) foreach ($old as $key => $value) {
                if(!isset($pics[$key])) $this->delete_image($value);
            }
        }
        $result = array();
        if($pics) {
            foreach ($pics as $key => $value) {
                if(!is_image(MUDDER_ROOT . $value)) continue;
                if(strposex($value, '/temp/')) {
                    $file = $this->move_image($value);
                    if($file) $result[_T(pathinfo($file, PATHINFO_FILENAME))] = $file;
                } elseif(strposex($value, '/discussion/')) {
                    if(is_file(MUDDER_ROOT . $value)) {
                        $result[_T(pathinfo($value, PATHINFO_FILENAME))] = $value;
                    }
                }
            }
        }
        return $result;
    }

    function delete_image($file) {
        if(is_array($file)) {
            foreach ($file as $value) {
                $this->delete_image($value);
            }
        } else {
            if(is_file(MUDDER_ROOT . $file) && (strposex($file, '/discussion/') || strposex($file, '/temp/'))) {
                @unlink(MUDDER_ROOT . $file);
            }
        }
    }

    function move_image($pic) {
        $sorcuefile = MUDDER_ROOT . $pic;
        if(!is_file($sorcuefile)) {
            return false;
        }
        if(function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
            @unlink($sorcuefile);
            return false;
        }

        $this->loader->lib('image', null, false);
        $IMG = new ms_image();
        $IMG->watermark_postion = $this->global['cfg']['watermark_postion'];
        $IMG->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $IMG->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $wtext = $this->global['cfg']['watermark_text'] ? $this->global['cfg']['watermark_text'] : $this->global['cfg']['sitename'];
        if($this->global['user']->username) {
            $IMG->set_watermark_text(lang('item_picture_wtext',array($wtext, $this->global['user']->username)));
        } else {
            $IMG->set_watermark_text($this->global['cfg']['sitename']);
        }

        $name = basename($sorcuefile);
        $path = 'uploads';

        if($this->global['cfg']['picture_dir_mod'] == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($this->global['cfg']['picture_dir_mod'] == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } else {
            $subdir = date('Y-m', _G('timestamp'));
        }

        $subdir = 'discussion' . DS . $subdir;
        $dirs = explode(DS, $subdir);
        foreach ($dirs as $val) {
            $path .= DS . $val;
            if(!@is_dir(MUDDER_ROOT . $path)) {
                if(!mkdir(MUDDER_ROOT . $path, 0777)) {
                    show_error(lang('global_mkdir_no_access',$path));
                }
            }
        }
        $result = array();
        $filename = $path . DS . $name;
        $picture = str_replace(DS, '/', $filename);
        if(!copy($sorcuefile, MUDDER_ROOT . $filename)) {
            return false;
        }
        if($this->global['cfg']['watermark']) {
            $wfile = MUDDER_ROOT . 'static' . DS . 'images' . DS . 'watermark.png';
            $IMG->watermark(MUDDER_ROOT.$filename, MUDDER_ROOT.$filename, $wfile);
        }
        if(!DEBUG) @unlink($sorcuefile);
        return $picture;
    }

/*
if($post['picture'] && strposex($post['picture'], '/temp/')) {
            if($pic = $this->upload_thumb($post['picture'])) {
                $post['havepic'] = 1;
                $post['picture'] = $pic['picture'];
                $post['thumb'] = $pic['thumb'];
            }
}
*/

    function check_post(& $post, $isedit = FALSE) {
        if(!$post['subject']) redirect('discussion_topic_post_subject_empty');
        if(!$post['content']) redirect('discussion_topic_post_content_empty');

        $this->modcfg['topic_content_max'] = 10000;
        $this->modcfg['topic_content_min'] = 10;
        if(strlen($post['content']) > $this->modcfg['topic_content_max'] || strlen($post['content']) < $this->modcfg['topic_content_min']) {
            redirect(lang('discussion_topic_post_content_strlen', array($this->modcfg['topic_content_min'],$this->modcfg['topic_content_max'])));
        }

        if(!$isedit) {
            if(!$post['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
            $subject = $this->subject->read($post['sid'],'*',FALSE);
            if(empty($subject)) redirect('discussion_topic_subject_empty');
            $post['isownerpost'] = (int) $this->subject->is_mysubject($post['sid'],$this->global['user']->uid);
        }
        return $post;
    }

    function checkup($tpids) {
        $ids = parent::get_keyids($tpids);
        $q = $this->db->from($this->table)->where('tpid',$ids)->where('status',0)->get();
        if(!$q) return;
        $upids = array();
        while ($v = $q->fetch_array()) {
            $upids[] = $v['tpid'];
            $this->_feed($topic);
        }
        $q->free_result();
        if($upids) $this->db->from($this->table)->set('status',1)->where('tpid',$upids)->update();
    }

    function delete($tpids) {
        $ids = parent::get_keyids($tpids);
        $this->loader->model('discussion:reply')->delete_tpid($ids);
        $this->db->from($this->table);
        $this->db->where('tpid',$ids);
        $this->db->select('pictures');
        $q = $this->db->get();
        if(!$q) return;
        while ($val = $q->fetch_array()) {
            if(!$val['pictures']) content;
            $this->delete_image($val['pictures']);
        }
        $q->free_result();
        parent::delete($ids);
    }

    // feed
    function _feed($topic) {
        if(!$topic) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(is_numeric($topic)) {
            if(!$detail = $this->read($topic)) return;
        } elseif(is_array($topic)) {
            $detail =& $topic;
        } else {
            return;
        }
        
        if(!$detail['uid']) return;

        $feed = array();
        $feed['icon'] = lang('discussion_topic_feed_icon');
        $feed['title_template'] = lang('discussion_topic_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[uid]").'">' . $detail['username'] . '</a>',
        );
        $feed['body_template'] = lang('discussion_topic_feed_body_template');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("discussion/topic/id/$detail[tpid]").'">' . $detail['subject'] . '</a>',
        );
        $feed['body_general'] = trimmed_title(strip_tags(preg_replace("/\[.+?\]/is", '', $detail['content'])), 150);

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['uid'], $detail['username'], $feed);
    }

}
?>