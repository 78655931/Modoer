<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fieldvalidator', FALSE);
class msm_item_fieldvalidator extends msm_fieldvalidator {

    var $category = null;
    var $ismysubject = false;

    function __construct() {
        parent::__construct();
		$this->model_flag = 'item';
		$this->is_edit = defined('EDIT_SID');
		$category = $this->loader->variable('category','item');
		$this->category = $category[ITEM_PID];
    }

    function msm_item_fieldvalidator() {
        $this->__construct();
    }

    function _check_access(&$field) {
        if($this->in_admin) return true;
        if(!$field['isadminfield']) return true;
        //如果是主题管理员在编辑
        if($field['isadminfield']=='2' && $this->ismysubject) return true;
        return false;
    }

    function _category() {
        if($this->data && !is_numeric($this->data)) {
            redirect(lang('item_fieldvalidator_unselect', $this->field['title']));
        }
        $C =& $this->loader->model('item:category');
        $pid = $C->get_parent_id($this->data);
        if(!$pid) redirect(lang('item_fieldvalidator_not_exists', $this->field['title']));
		$category = $this->loader->variable('category_'.$pid,'item');
        $t_cat = $category[$this->data];
        empty($t_cat) && redirect(lang('item_fieldvalidator_not_exists', $this->field['title']));
        (empty($t_cat['pid'])&&!$t_cat['config']['relate_root']) && redirect(lang('item_fieldvalidator_invalid_subcat', $this->field['title']));
    }

    function _status() {
		$this->data = (int) $this->data;
		if(!$this->in_admin) {
			$this->data = $this->category['config']['itemcheck'] ? 0 : 1;
		}
		if($this->data < 0 || $this->data > 3) {
			redirect(lang('global_op_value_invalid', $this->field['title']));
		}
    }

    function _level() {
		$this->data = (int) $this->data;
		if($this->data < 0 || $this->data > 5) {
			redirect(lang('global_op_value_invalid', $this->field['title']));
		}
    }

    function _template() {
		if($this->data == '') {
			$this->data = (int) $this->category['config']['templateid'];
		} else {
			$this->data = (int) $this->data;
		}
		if($this->data > 0) { //使用了风格，需要进行检测
			$tpllist = $this->loader->variable('templates');
			$exists = false;
			empty($tpllist) && redirect(sprintf(lang('item_fieldvalidator_no_select_item'), $this->field['title']));
			if($tpllist['item']) foreach($tpllist['item'] as $val) {
				if($val['templateid'] == $this->data) {
					$exists = true;
					break;
				}
			}
			!$exists && redirect(lang('item_fieldvalidator_invalid_item', $this->field['title']));
		}
    }

    function _tag() {
		if(!$this->data) {
            $this->data = '';
        } elseif(!$groupid = $this->config['groupid']) {
            $this->data = '';
        } else {
            $TAG =& $this->loader->model('item:tag');
            //检测标签并返回符合的标签组
            if($this->data = $TAG->check_post_single($groupid, $this->data)) {
                if(is_array($this->data)) {
                    $max = $this->config['len'] > 0 ? $this->config['len'] : 5;
                    if(count($this->data) > $max) redirect(lang('item_fieldvalidator_tag_len', array($this->field['title'], $max)));
                }
                $this->data = serialize($this->data);
            } else {
                $this->data = '';
            }
        }
    }

    function _att() {
		if(!$this->data) {
            $this->data = '';
        } elseif(!$catid = $this->config['catid']) {
            $this->data = '';
        } else {
            $AD =& $this->loader->model('item:att_data');
            //检测标签并返回符合的标签组
            if($data = $AD->check_atts($catid, $this->data)) {
                $max = $this->config['len'] > 0 ? $this->config['len'] : 1;
                if(count(explode(',',$data)) > $max) redirect(lang('item_fieldvalidator_tag_len', array($this->field['title'], $max)));
                $this->data = $data;
            } else {
                $this->data = '';
            }
        }
    }

    function _subject() {
		if(!$this->data) {
            $this->data = '';
        } elseif(!$categorys = $this->config['categorys']) {
            $this->data = '';
        } else {
            if(!$subjects = $this->__subject_check($this->data)) {
                $this->data = '';
            } else {
                $max = $this->config['savelen'] > 0 ? $this->config['savelen'] : 5;
                $this->data = $this->__subject_timmed($subjects, $max);
                $this->global['define']['ITEM_'.strtoupper($this->field['fieldname']).'_SUBJECTS'] = $subjects;
                //vp($subjects);
                //vp($this->data);
                //vp($this->global['define']['ITEM_'.strtoupper($this->field['fieldname']).'_SUBJECTS']);
                //if(count(explode(',',$subjects)) > $max) redirect(lang('item_fieldvalidator_subject_len', array($this->field['title'], $max)));
            }
        }
    }

    function _member() {
		if(!$this->data) {
            $this->data = '';
            return;
        }
        $this->db->select('uid,username');
		$this->db->from('dbpre_members');
		$this->db->where('username', $this->data);
		if(!$t_mb = $this->db->get_one()) {
			redirect(lang('item_fieldvalidator_not_exists', $this->field['title']));
		}
		define('ITEM_'.strtoupper($this->field['fieldname']).'_UID', $t_mb['uid']);
    }

    function _mappoint() {
		if(!$this->data) {
            $this->data = '';
            return;
        }
		$this->data = _T($this->data);
		if(!preg_match('/^[A-Za-z0-9\.\-]+,[A-Za-z0-9\.\-]+$/', $this->data)) {
			redirect(lang('global_op_value_invalid', $this->field['title']) . $this->data);
		}
    }

    function _video() {
		if(!$this->data) {
            $this->data = '';
            return;
        }
    }
	
	function _taoke_product() {
		if(!$this->data) {
            $this->data = '';
            return;
        }
	}

    function __subject_check($str) {
        if(!$str) return;
        if(!$sids = explode(",",$str)) return;
        $result = array();
        $S =& $this->loader->model('item:subject');
        list(,$list) = $S->find('sid,pid,catid,name,subname',array('sid'=>$sids), 'sid', 0, count($sids), false);
        if($list) {
            while($v=$list->fetch_array()) {
                $result[$v['sid']] = $v['name'] . ($v['subname']?"($v[subname])":'');
            }
            $list->free_result();
        }
        /*
        foreach($subject as $key => $val) {
            list($sid,$name) = explode("\t",$val);
            $result[$sid] = $name;
        }
        */
        return $result;
    }

    function __subject_timmed($subjects,$num) {
        if(!$subjects) return '';
        if(count($subjects) > $num) {
            //删除多余的数组
            for($i=0;$i<count($subjects)-$num;$i++) {
                array_pop($subjects);
            }
        }
        $result = array();
        foreach($subjects as $key => $val) {
            $result[] = $key . "\t" . $val;//implode("\t",$val);
        }
        return implode("\r\n", $result);
    }

}

?>