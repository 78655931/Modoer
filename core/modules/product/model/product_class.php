<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_product extends ms_model {

    var $model_flag = 'product';
    var $table = 'dbpre_product';
    var $key = 'pid';

    var $modcfg = '';

    function __construct() {
        parent::__construct();
        $this->init_field();
        $this->modcfg = $this->variable('config');
    }

    function msm_product() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('modelid,sid,catid,dateline,uid,username,subject,thumb,price,description,closed_comment');
        $this->add_field_fun('modelid,sid,catid,dateline,uid,closed_comment', 'intval');
        $this->add_field_fun('username,subject,description,thumb', '_T');
    }

    function read($pid, $read_field = TRUE) {
        if(!is_numeric($pid) || $pid < 1) redirect(lang('global_sql_keyid_invalid', 'pid'));
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        if(!$result = $this->db->get_one()) return;
        if(!$read_field) return $result;
        $modelid = $result['modelid'];
        $model = $this->variable('model_'.$modelid, $this->model_flag);
        $table = 'dbpre_' . $model['tablename'];
        $this->db->from($table);
        $this->db->where('pid', $pid);
        if(!$result_field = $this->db->get_one()) return $result;
        $result = array_merge($result, $result_field);
        return $result;
    }

    function read_field($pid,$modelid,$select='*') {
        $model = $this->variable('model_'.$modelid, $this->model_flag);
        $table = 'dbpre_' . $model['tablename'];
        $this->db->from($table);
        $this->db->where('pid', $pid);
        return $this->db->get_one();
    }

    function find($select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null, $atts = NULL) {
        if($select_subject) {
            $this->db->join($this->table, 'p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        } else {
            $this->db->from($this->table, 'p');
        }
        if($atts) {
            foreach($atts as $att_catid => $attid) {
                $this->db->where_exist("SELECT 1 FROM dbpre_productatt pt WHERE p.pid=pt.pid AND attid=$attid");
            }
        }
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function find_list($modelid, $select, $where, $order_by, $start, $offset, $total = TRUE, $select_subject=null, $atts = NULL) {
        $model = $this->variable('model_' . $modelid);
        $data_table = 'dbpre_' . $model['tablename'];
        $this->db->join($this->table, 'p.pid', $data_table, 'pd.pid', 'LEFT JOIN');
        if($select_subject) {
            $this->db->join_together($this->table, 'p.sid', 'dbpre_subject', 's.sid', 'LEFT JOIN');
        }
        if($atts) {
            foreach($atts as $att_catid => $attid) {
                $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid=$attid");
            }
        }
        $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select($select);
        $select_subject && $this->db->select($select_subject);
        $this->db->order_by($order_by);
        $this->db->limit($start,$offset);
        $result[1] = $this->db->get();
        return $result;
    }

    function save($post, $pid = null) {
        $edit = $pid != null;
        $this->check_post($post, $edit);
        if($edit) {
            if(!$detail = $this->read($pid)) redirect('product_empty');
            if(!$this->in_admin && isset($post['status'])) unset($post['status']);
            if($this->in_admin) unset($post['catid']);
        } else {
			if(!$this->in_admin) {
				$post['uid'] = $this->global['user']->uid;
				$post['username'] = $this->global['user']->username;
				$post['status'] = $this->modcfg['check_product'] ? 0 : 1;
			} else {
				$post['status'] = 1;
			}
            $post['dateline'] = $this->global['timestamp'];
        }
        $S =& $this->loader->model('item:subject');
        if(!$subject = $S->read($post['sid'],'sid,pid,name,subname,status',FALSE)) redirect('item_empty');
        //ǰ̨��Ȩ���ж�
        if(!$this->in_admin && !$S->is_mysubject($post['sid'], $this->global['user']->uid)) redirect('global_op_access');
        $field_data = $post['field_data'];
        $F =& $this->loader->model($this->model_flag.':field');
        $F->class_flag = $this->model_flag;
        if(!$modelid = $this->get_modelid($subject['pid'])) redirect('product_model_empty');
        $post['modelid'] = $modelid;
        $data = $F->validator($post['modelid'], $field_data);
		foreach(array('price') as $key) {
			if(isset($data[$key])) {
				$post[$key] = $data[$key];
				unset($data[$key]);
			}
		}
        unset($post['field_data']);
        $model = $this->variable('model_' . $post['modelid']);

        //�Ա�ɾ������Ҫ���µ��ֶ�
        if($edit) {
            foreach($detail as $k => $v) {
                if(isset($post[$k]) && $v == $post[$k]) {
                    unset($post[$k]);
                } elseif(isset($data[$k]) && $v == $data[$k]) {
                    unset($data[$k]);
                }
            }
        }

        //�ϴ�ͼƬ����
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }

        if($post) { $pid = parent::save($post, $pid, 0, 0); }

        $status = FALSE; //��Ʒ�Ƿ�����״̬
        if($edit) {
            if($post['status'] == '1') {
                $status = TRUE;
                $this->subject_num_add($post['sid']);
            } elseif($post['status'] == '2') {
                if($detail['staus'] == '1') $this->subject_num_dec($post['sid']);
            } elseif(isset($post['status'])) {
                if($detail['staus'] == '1') $this->subject_num_dec($post['sid']);
            } else {
                $status = $detail['status'] == '1';
            }
        } else {
            if($post['status'] == '1') {
                $status = TRUE;
                $this->subject_num_add($post['sid']);
            }
        }
        define('RETURN_EVENT_ID', $status ? 'global_op_succeed' : 'global_op_succeed_check');
        if(!$data) return $pid;

        $data_table = 'dbpre_' . $model['tablename'];
        $this->db->from($data_table);
        $this->db->set($data);
        if($edit) {
            $this->db->where('pid', $pid);
            $this->db->update();
        } else {
            $this->db->set('pid', $pid);
            $this->db->insert();
        }

        //������Ҫ���ڴ���
        $data['status'] = $post['status'];
        $this->save_atts($pid, $modelid, $data, $detail, $edit);

        return $pid;
    }

    // ������������
    function save_atts($pid, $modelid, &$post, &$detail, $edit = false) {
        $fields = $this->variable('field_' . $modelid);
        $savedata = array();
        $AD =& $this->loader->model('product:att_data');
        foreach($fields as $val) {
            if($val['type'] != 'att') continue;
            if(!isset($post[$val['fieldname']])) continue; //������棬���ʾ���ݺ;͵���ͬ���Ѿ���ע��
            $newatts = $post[$val['fieldname']];
            if(!$catid = $val['config']['catid']) continue;

            //ɾ���ɵ�
            $AD->delete_pid_catid($sid,$catid);
            if(!$newatts) continue;

            if(!$edit) {
                if($post['status'] == 1 && $newatts) $AD->add($catid, $pid, $newatts); //�½�
            } else {
                $oldatts = $detail[$val['fieldname']];
                if($detail['status'] != 1 && $post['status'] == 1) {
                    if($newatts) $AD->add($catid, $pid, $newatts); //�½�
                } elseif($detail['status'] == 1 && ($post['status'] == 1||!isset($post['status']))) {
                    if($newatts) $AD->add($catid, $pid, $newatts); //�½�
                    //if($oldatts && $newatts) $AD->replace($catid, $pid, $newatts, $oldatts); //ɾ�����滻�����
                    //if(!$oldatts && $newatts) $AD->add($catid, $pid, $newatts); //�½�
                    //if($oldatts && !$newatts) $AD->delete($catid, $pid, $oldatts); //ɾ��
                } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                    //if($oldatts)  $AD->delete($catid, $pid, $oldatts); //ɾ��
                }
            }
        }
    }

    function checkup($pids) {
        if(is_numeric($pids) && $pids > 0) $pids = array($pids);
        if(!$pids || !is_array($pids)) redirect('global_op_unselect');
        $this->db->from($this->table);
        $this->db->select('pid,sid,status,modelid');
        $this->db->where_in('pid', $pids);
        $this->db->where('status',0);
        if(!$r = $this->db->get()) return;
        $uppids = $sids = $atts = array();
        while($v=$r->fetch_array()) {
            $uppids[] = $v['pid'];
            if(isset($sids[$v['sid']])) {
                $sids[$v['sid']]++;
            } else {
                $sids[$v['sid']]=1;
            }
            $fielddata = $this->read_field($v['pid'],$v['modelid']);
            $fielddata = array_merge($fielddata,$v);
            $fielddata['status']=1;
            $atts[] = $fielddata;
        }
        $r->free_result();
        if($sids) {
            foreach($sids as $sid => $num) {
                $this->subject_num_add($sid, $num);
            }
        }
        $this->db->from($this->table);
        $this->db->set('status',1);
        $this->db->where_in('pid', $uppids);
        $this->db->update();
        //����������
        if($atts) {
            $detail = array();
            foreach($atts as $val) $this->save_atts($val['pid'], $val['modelid'], $val, $detail, false);
        }
    }

    //��PIDɾ��
    function delete($pids) {
        if(is_numeric($pids) && $pids > 0) $pids = array($pids);
        if(!$pids || !is_array($pids)) redirect('global_op_unselect');
        $where = array('pid' => $pids);
        $this->_delete($where);
    }

    //��SIDɾ��
    function delete_sids($sids) {
        if(is_numeric($sids) && $sids > 0) $sids = array($sids);
        if(!$sids || !is_array($sids)) redirect('global_op_unselect');
        $where = array('sid' => $sids);
        $this->_delete($where);
    }

    //��CATIDɾ��
    function delete_catid($catid) {
        if(is_numeric($catid) && $catid > 0) $catid = array($catid);
        if(!$catid || !is_array($catid)) redirect('global_op_unselect');
        $where = array('catid' => $catid);
        $this->_delete($where);
    }

    function _delete($where) {
        if(!$this->in_admin) {
            //ǰ̨ɾ�������ж�Ȩ��
            $S =& $this->loader->model('item:subject');
            if(!$mysubjects = $S->mysubject($this->global['user']->uid)) redirect('global_op_access');
        }

        $this->db->from($this->table);
        $this->db->select('pid,modelid,sid,picture,thumb,status');
        $this->db->where($where);
        if(!$r = $this->db->get()) return;
        $delpids = $delpics = $decsids = array();
        while($v=$r->fetch_array()) {
            if(!$this->in_admin && !in_array($v['sid'], $mysubjects)) redirect('global_op_access');
            $delpids[$v['modelid']][] = $v['pid'];
            $delpics[] = $v['picture'];
            $delpics[] = $v['thumb'];
            //����ɾ����������Ĳ�Ʒ
            if(!isset($where['sid'])) {
                if(in_array($v['sid'], $decsids)) {
                    $decsids[$v['sid']]++;
                } else {
                    $decsids[$v['sid']] = 1;
                }
            }
        }
        //ɾ����Ʒ
        if($delpids) {
            foreach($delpids as $modelid => $pids) {
                $model = $this->variable('model_' . $modelid);
                $this->db->from('dbpre_' . $model['tablename']);
                $this->db->where_in('pid', $pids);
                $this->db->delete();
            }
            $pids = array_values($delpids);
            parent::delete($pids);
            //ɾ������
            $PAD =& $this->loader->model('product:att_data');
            $PAD->delete_pid($pids);
        }
        //ɾ��ͼƬ
        if($delpics) {
            foreach($delpics as $val) {
                if(strlen($val) < 10) continue;
                @unlink(MUDDER_ROOT . $val);
            }
        }
        //ɾ�������ͳ��
        if($decsids) {
            foreach($decsids as $sid => $num) {
                $this->subject_num_dec($sid, $num);
            }
        }
        //ɾ������
        if(check_module('comment')) {
            $CM =& $this->loader->model(':comment');
            $CM->delete_id('product', $ids, false, true);
        }
    }

    //���������
    function pageview($pid, $num=1) {
        $this->db->from($this->table);
        $this->db->set_add('pageview', $num);
        $this->db->where('pid', $pid);
        $this->db->update();
    }

    //�ύ����
    function check_post(&$post, $edit = false) {
        if(!$post['sid']) redirect('product_post_sid_empty');
        if(!$post['catid'] && !$this->in_admin) redirect('product_post_catid_empty');
        if(!$post['subject'] && !$this->in_admin) redirect('product_post_subject_empty');
    }

    //�ϴ�ͼƬ
    function upload_thumb(& $img) {
        $config = $this->variable('config');

        $thumb_w = $this->modcfg['thumb_width'] ? $this->modcfg['thumb_width'] : 200;
        $thumb_h = $this->modcfg['thumb_height'] ? $this->modcfg['thumb_height'] : 150;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        $img->set_ext($this->global['cfg']['picture_ext']);
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 's_', $thumb_w, $thumb_h);
        $dir_mod = $this->global['cfg']['picture_upload_size'];
        $img->upload('product', $dir_mod);
    }

    //�����ύ��
    function create_from($modelid, $data = null, $style = null) {
        if(!$modelid) redirect('product_model_empty');
        if(!$fields = $this->variable('field_' . $modelid, $this->model_flag)) return '';
        $FF =& $this->loader->model($this->model_flag.':fieldform');
        $content = '';
        if($this->in_admin) {
            $FF->width = "100";
            $FF->class = "altbg1";
            $FF->align = $this->in_admin ? 'right':"left";
        }
        foreach($fields as $val) {
            if(!$this->in_admin && $val['isadminfield']) continue;
            $content .= $FF->form($val, $data ? $data[$val['fieldname']] : '', $data != null) . "\r\n";
        }
        return $content;
    }

    //�����б�
    function create_list($modelid, &$data, $style = null) {

    }

    //�������������ȡ��Ʒģ��ID
    function get_modelid($catid) {
        if(!$catid = (int) $catid) redirect(lang('global_sql_keyid_invalid','catid'));
        $category = $this->loader->variable('category', 'item');
        if(!isset($catid)) redirect('item_cat_empty');
        return (int) $category[$catid]['config']['product_modelid'];
    }

    //���������Ʒ����
    function subject_num_add($sid,$num=1) {
        if(!$sid || $sid < 1 || $num < 1) return;
        $this->db->from('dbpre_subject');
        $this->db->set_add('products', $num);
        $this->db->where('sid', $sid);
        $this->db->update();
    }

    //���������Ʒ����
    function subject_num_dec($sid,$num=1) {
        if(!$sid || $sid < 1 || $num < 1) return;
        if(!$sid || $sid < 1 || $num < 1) return;
        $this->db->from('dbpre_subject');
        $this->db->set_dec('products', $num);
        $this->db->where('sid', $sid);
        $this->db->update();
    }

    //ȡ��һ������Ĳ�Ʒ����
    function get_subject_total($sid) {
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('status',1);
        return $this->db->count();
    }
}
?>