<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_comment extends ms_model {

    var $table = 'dbpre_comment';
	var $key = 'cid';
    var $model_flag = 'comment';

    var $typenames = array();
    var $typeurls = array();
    var $idtypes = array();

	function __construct() {
		parent::__construct();
        $this->model_flag = 'comment';
		$this->init_field();
        $this->load_hook();
        $this->modcfg = $this->variable('config');
	}

    function msm_comment() {
        $this->__construct();
    }

	function init_field() {
		$this->add_field('idtype,id,grade,title,content,username,status');
		$this->add_field_fun('id,grade', 'intval');
        $this->add_field_fun('idtype,title,username', '_T');
        $this->add_field_fun('content', '_TA');
	}

    function load_hook() {
        $modules =& $this->global['modules'];
        foreach($modules as $k => $v) {
            $hookfile = MUDDER_MODULE . $v['flag'] . DS . 'inc' . DS . 'comment_hook.php';
            if(!is_file($hookfile)) continue;
            if(!$tmp = read_cache($hookfile)) continue;
            foreach($tmp as $k2 => $v2) {
                $this->idtypes[$k2] = $v2;
            }
        }
    }

    function save($post, $cid = NULL) {
        $edit = $cid != null;
        if($this->modcfg['filter_word']) {
            $W =& $this->loader->model('word');
        }
        if($edit) {
            if(!$detail = $this->read($cid)) redirect('comment_empty');
        } else {
            !$this->in_admin && $this->global['user']->check_access('comment_disable',$this);
            if($this->global['user']->isLogin) {
                $post['uid'] = $this->global['user']->uid;
                $post['username'] = $this->global['user']->username;
            }
            $post['dateline'] = $this->global['timestamp'];
            $post['ip'] = $this->global['ip'];
            $post['status'] = $this->modcfg['check_comment'] ? 0 : 1;
            if($this->modcfg['filter_word']) {
                $W->check($post['content']) && $post['status'] = 0;
            }
        }
        if($this->modcfg['filter_word']) {
            $post['content'] = $W->filter($post['content']);
        }
        $this->check_post($post, $edit);
        if($edit) {
            foreach($detail as $k => $v) {
                if(isset($post[$k]) && $post[$k] == $v) {
                    unset($post[$k]);
                }
            }
            if($post && count($post) > 0) {
                $this->db->from($this->table);
                $this->db->set($post);
                $this->db->where('cid',$cid);
                $this->db->update();
                //更改了状态的需要重新统计主题的评论数量
                if($post['status']) {
                    define('RETURN_EVENT_ID', 'global_op_succeed');
                    $this->_add_total($detail['idtype'], $detail['id']);
                    $detail['uid'] && $this->_add_point($detail['uid']);
                } elseif(isset($post['status']) && ($detail['status'] && !$post['status'])) {
                    define('RETURN_EVENT_ID', 'global_op_succeed_check');
                    $this->_dec_total($detail['idtype'], $detail['id']);
                    $detail['uid'] && $this->_dec_point($detail['uid']);
                } else {
                    define('RETURN_EVENT_ID', $detail['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
                }
            }
        } else {
            $cid = parent::save($post,null,0,0);
            if($post['status']) {
                //不需要审核
                $this->_add_total($post['idtype'], $post['id']);
                $post['uid'] && $this->_add_point($post['uid']);
                define('RETURN_EVENT_ID', 'global_op_succeed');
            } else {
                define('RETURN_EVENT_ID', 'global_op_succeed_check');
            }
        }
        return $pid;
    }

    function checkup($cids) {
        if(is_numeric($cids) && $cids > 0) $cids = array($cids);
        if(!$cids||!is_array($cids)) redirect('global_op_unselect');
        $this->db->select('cid,idtype,id,uid');
        $this->db->from($this->table);
        $this->db->where('status',0);
        $this->db->where_in('cid',$cids);
        if(!$r = $this->db->get()) return;
        $upids = $upcids = $upuids = array();
        while($v=$r->fetch_array()) {
            $upcids[] = $v['cid'];
            $keyid = $v['idtype'].'-'.$v['id'];
            if(isset($upids[$keyid])) {
                $upids[$keyid]++;
            } else {
                $upids[$keyid] = 1;
            }
            if(!$v['uid'] || $v['uid'] < 1) continue;
            if(isset($upuids[$v['uid']])) {
                $upuids[$v['uid']]++;
            } else {
                $upuids[$v['uid']]=1;
            }
        }
        $r->free_result();

        $this->db->from($this->table);
        $this->db->set('status',1);
        $this->db->where_in('cid',$upcids);
        $this->db->update();

        foreach($upids as $idstr => $num) {
            list($idtype,$id) = explode('-', $idstr);
            $this->_add_total($idtype, $id, $num);
        }

        if($upuids) {
            foreach($upuids as $uid => $num) {
                $this->_add_point($uid, $num);
            }
        }
    }

    function delete($cids,$uppoint=false,$allow=false) {
        if(is_numeric($cids) && $cids > 0) $cids = array($cids);
        if(!$cids||!is_array($cids)) redirect('global_op_unselect');
        $where = array('cid'=>$cids);
        $this->_delete($where,1,$uppoint,$allow);
    }

    function delete_id($idtype,$id=0,$uppoint=false,$allow=false) {
        if(!$idtype) return;
        $where = array();
        $where['idtype'] = $idtype;
        $id && $where['id'] = $id;
        $this->_delete($where,0,$uppoint,$allow);
    }

    function check_post(& $post, $edit = false) {
        if(!$edit && !$post['username']) redirect('comment_post_username_empty');
        if(!$post['title']) redirect('comment_post_title_empty');
        if(!$post['content']) redirect('comment_post_content_empty');
        $this->modcfg['content_min'] = $this->modcfg['content_min']>0 ? $this->modcfg['content_min'] : 10;
        $this->modcfg['content_max'] = $this->modcfg['content_max']>0 ? $this->modcfg['content_max'] : 500;
        if(strlen($post['content']) > $this->modcfg['content_max'] || strlen($post['content']) < $this->modcfg['content_min']) {
            redirect(lang('comment_post_content_charlen',array($this->modcfg['content_min'],$this->modcfg['content_max'])));
        }
    }

    function get_url($idtype, $id) {
        return url(str_replace('_ID_', $id, $this->idtypes[$idtype]['detail_url']));
    }

    function check_idtype($idtype) {
        return isset($this->idtypes[$idtype]);
    }

    function check_id_exists($idtype,$id) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return false;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return false;
        $this->db->from($table_name);
        $this->db->where($key_name,$id);
        return $this->db->count() > 0;
    }

    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key=='comment_disable') {
            $value = (int) $value;
            if($value) {
                if(!$jump) return FALSE;
                redirect('comment_access_disable');
            }
        }
        return TRUE;
    }

    function _delete($where,$total=true,$uppoint=false,$allow=false) {
        if(!$where) return;
        $this->db->select('cid,idtype,id,uid,status');
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$r = $this->db->get()) return;
        $upids = $upcids = $upuids = array();
        while($v=$r->fetch_array()) {
            //权限判断
            if(!$allow && !defined('IN_ADMIN') && $this->global['user']->uid!=$v['uid']) redirect('global_op_access');
            $upcids[] = $v['cid'];
            if($total && $v['status']) {
                $keyid = $v['idtype'].'-'.$v['id'];
                if(isset($upids[$keyid])) {
                    $upids[$keyid]++;
                } else {
                    $upids[$keyid] = 1;
                }
            }
            if($uppoint && $v['status']) {
                if(!$v['uid'] || $v['uid'] < 1) continue;
                if(isset($upuids[$v['uid']])) {
                    $upuids[$v['uid']]++;
                } else {
                    $upuids[$v['uid']]=1;
                }
            }
        }
        $r->free_result();

        $this->db->from($this->table);
        $this->db->where_in('cid',$upcids);
        $this->db->delete();

        if($total && $upids) {
            foreach($upids as $idstr => $num) {
                list($idtype,$id) = explode('-', $idstr);
                $this->_dec_total($idtype, $id, $num);
            }
        }

        if($uppoint && $upuids) {
            foreach($upuids as $uid => $num) {
                $this->_dec_point($uid, $num);
            }
        }
    }

    function _filter_words(&$content) {
        return $content;
    }

    function _get_avg_grade($idtype, $id) {
        $this->db->from($this->table);
        $this->db->select('grade', 'grade', 'ROUND(AVG( ? ))');
        $this->db->where('idtype', $idtype);
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $result = $this->db->get_one();
        return (int) $result['grade'];
    }

    function _add_total($idtype, $id, $num=1, $up_grade = TRUE) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return;
        $grade = FALSE;
        if($up_grade) {
            if($grade_name = $this->idtypes[$idtype]['grade_name']) {
                $grade = $this->_get_avg_grade($idtype,$id);
            }
        }
        $this->db->from($table_name);
        $this->db->set_add($total_name, $num);
        $grade_name && $this->db->set($grade_name, $grade);
        $this->db->where($key_name, $id);
        $this->db->update();
    }

    function _dec_total($idtype, $id, $num=1, $up_grade = TRUE) {
        if(!$table_name = $this->idtypes[$idtype]['table_name']) return;
        if(!$key_name = $this->idtypes[$idtype]['key_name']) return;
        if(!$total_name = $this->idtypes[$idtype]['total_name']) return;
        $grade = FALSE;
        if($up_grade) {
            if($grade_name = $this->idtypes[$idtype]['grade_name']) {
                $grade = $this->_get_avg_grade($idtype,$id);
            }
        }
        $this->db->from($table_name);
        $this->db->set_dec($total_name, $num);
        $grade_name && $this->db->set($grade_name, $grade);
        $this->db->where($key_name, $id);
        $this->db->update();
    }

    function _add_point($uid, $num=1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $P->update_point($uid, 'add_comment', FALSE, $num);
    }

    function _dec_point($uid, $num=1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $P->update_point($uid, 'add_comment', TRUE, $num); //删除
    }

    function _ubb(&$content) {
        $searcharray = array('[u]','[/u]','[b]','[/b]','[i]','[/i]','[quote]','[/quote]','[h3]','[/h3]',);
        $replacearray = array('<u>','</u>','<b>','</b>','<i>','</i>','<div class="quote">','</div>','<h3>','</h3>');
        return str_replace($searcharray, $replacearray, $content);
    }
}
?>