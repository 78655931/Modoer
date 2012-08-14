<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subjectsetting extends ms_model {

    var $table = 'dbpre_subjectsetting';
	var $key = 'sid';
    var $model_flag = 'item';

    var $subject = null;
    var $default_setting = null;

    var $s = null;

	function __construct() {
		parent::__construct();
		$this->init_field();
        $this->s =& $this->loader->model('item:subject');
	}

	function init_field() {
		$this->add_field('sid,variable,value');
		$this->add_field_fun('sid', 'intval');
        $this->add_field_fun('variable', '_T');
	}

    function read($sid,$variable=null) {
        $this->db->from($this->table);
        $this->db->where('sid', $sid);
        if($variable) {
            $this->db->select('value');
            $this->db->where('variable',$variable);
            return $this->db->get_value();
        }
        if(!$q = $this->db->get()) return false;
        $r = array();
        while($v = $q->fetch_array()) {
            $r[$v['variable']] = $v['value'];
        }
        $q->free_result();
        return $r;
    }

    function save($sid,$variable,$value) {
        $this->db->from($this->table);
        $this->db->set('sid',$sid);
        $this->db->set('variable',$variable);
        is_array($value) && $value = serialize($value);
        $this->db->set('value',$value);
        $this->db->replace();
    }

    function change_banner() {
        $sid = _post('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');
        //picture size
        $width = $this->get_dsetting('banner_width',900);
        $height = $this->get_dsetting('banner_height',150);
        //get old banner
        $o_b = $this->read($sid, 'banner');
        //uploading banner
		$this->loader->lib('upload_image', NULL, FALSE);
		$img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
        $img->userWatermark = false;
        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->set_picture_limit_size($width, $height);
        if(!$img->upload('item')) redirect($img->errormsg);
        $picture = str_replace(DS, '/', $img->path . '/' . $img->filename);
        $this->save($sid, 'banner', $picture);
        //delete old banner
        if($o_b && is_file(MUDDER_ROOT.$o_b)) @unlink($o_b);
    }

    function delete_banner() {
        $sid = _post('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        $this->db->where('variable','banner');
        $this->db->delete();
    }

    function save_title() {
        $sid = _post('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');

        $top = _post('top',null,MF_INT);
        $left = _post('left',null,MF_INT);

        $color = str_replace(' ','',strtolower(_post('color',null,MF_TEXT)));  //rgb(50,50,50)
        if($color && preg_match("/^rgb\([0-9]+,[0-9]+,[0-9]+\)$/", $color)) {
            $color = $this->_rgb2hex($color);
        }
        if(!$color||!preg_match("/^#[a-z0-9]{3,6}$/i",$color)) {
            $color = '';
        }
        if($color) $this->save($sid, 'title_color', $color);

        if($left > 0 && $top > 0) {
            $value = $top .','. $left;
            $this->save($sid, 'title_offset', $value);
        }
    }

    function bcastr_list_post() {
        $sid = _post('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');
        $pt_bcastrs = _post('bcastrs', null, MF_INT_KEY);
        if(!$pt_bcastrs) return;
        $bcastrs = $this->read($sid, 'bcastr');
        if($bcastrs) $bcastrs = unserialize($bcastrs);
        if(!$bcastrs) return;
        foreach($pt_bcastrs as $k => $v) {
            if($v) foreach($v as $_k => $_v) {
                $bcastrs[$k][$_k] = $_v;
            }
        }
        uasort($bcastrs, array($this,'bcastr_listorder_cmp'));
        $this->save($sid, 'bcastr', $bcastrs);
    }

    function bcastr_delete() {
        $sid = _input('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');
        $flag = _input('flag', '', MF_TEXT);
        if($flag=='') redirect('未指定删除的图片。');
        $bcastrs = $this->read($sid, 'bcastr');
        if($bcastrs) $bcastrs = unserialize($bcastrs);
        if(!$bcastrs||!isset($bcastrs[$flag])) return;
        if(!empty($bcastrs[$flag]['picture']) && is_file(MUDDER_ROOT.$bcastrs[$flag]['picture'])) {
            if(strlen($bcastrs[$flag]['picture'])>15) @unlink(MUDDER_ROOT.$bcastrs[$flag]['picture']);
        }
        unset($bcastrs[$flag]);
        uasort($bcastrs, array($this,'bcastr_listorder_cmp'));
        $this->save($sid, 'bcastr', $bcastrs);
    }

    function bcastr_listorder_cmp($a,$b) {
        $a_listorder = (int) $a['listorder'];
        $b_listorder = (int) $b['listorder'];
        return strcmp($a_listorder, $b_listorder);
    }

    function bcastr_save_post() {
        $sid = _post('sid', null, MF_INT_KEY);
        if(!$this->check_access($sid)) redirect('global_op_access');
        $flag = _post('flag',null, MF_TEXT);
        !$flag && $flag = uniqid();
        $bcastrs = $this->read($sid, 'bcastr');
        if($bcastrs) $bcastrs = unserialize($bcastrs);
        $isedit = isset($bcastrs[$flag]);
        if(!$bcastrs) $bcastrs = array();
        $post['listorder'] = trim(_post('listorder', '0', MF_INT_KEY));
        $post['title'] = trim(_post('title', '', MF_TEXT));
        $post['url'] = trim(_post('url', '', MF_TEXT));
        if(!$post['title']) redirect('对不起，您未填写图片标题。');
        if(!$post['url']||$post['url']=='http://') redirect('对不起，您未填写图片链接。');
        if(strtolower(substr($post['url'],0,7)!='http://')) redirect('对不起，您填写的图片链接有误。');
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $img->userWatermark = false;
            $img->set_max_size($this->global['cfg']['picture_upload_size']);
            $img->set_picture_limit_size($this->get_dsetting('bcastr_width',740), $this->get_dsetting('bcastr_height',200));
            if(!$img->upload('item')) redirect($img->errormsg);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
        } elseif(!$isedit) {
            redirect('对不起，您未上传橱窗图片。');
        } else {
            $post['picture'] = $bcastrs[$flag]['picture'];
        }
        $bcastrs[$flag] = $post;

        $this->save($sid, 'bcastr', $bcastrs);
    }

    function check_access($sid) {
    
        $S =& $this->loader->model('item:subject');
        $this->subject = $S->read($sid);
        if(empty($this->subject)) redirect('item_empty');
        if(!$this->subject['templateid']) redirect('item_not_used_template');
        $this->_load_default_setting();

        if($this->in_admin) return true;
        return $this->s->is_mysubject($sid, $this->global['user']->uid);
    }

    function get_dsetting($key,$dv = '') {
        if(isset($this->default_setting[$key])) return $this->default_setting[$key];
        return $dv;
    }

    function _load_default_setting() {
        if(!$this->subject) return;
        if(!$this->subject['templateid']) return;
        $tpid = (int)$this->subject['templateid'];
        $templates = $this->loader->variable('templates');
        $type_dir = isset($templates['item'][$tpid]) ? $templates['item'][$tpid]['directory'] : 'default';
        $file =  MUDDER_ROOT . 'templates' . DS . 'item' . DS . $type_dir . DS . 'default_setting.php';
        if(is_file($file)) $this->default_setting = read_cache($file);
    }

    function _rgb2hex($rgb) {
        $rgb = explode(',',str_replace('rgb','(',')', $rgb));
        if(!$rgb || !is_array($rgb) || count($rgb)!=3) return false;

        $hex = "#";
        foreach ($rgb as $value) {
            if(!is_numeric($value) || $value < 0 || $value > 255) return false;
            $hex .= str_pad(dechex($value), 2, "0", STR_PAD_LEFT);
        }
        return $hex;
    }

}
?>