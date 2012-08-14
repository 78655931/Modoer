<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_exchange_gift extends ms_model {

    var $table = 'dbpre_exchange_gifts';
    var $key = 'giftid';
    var $model_flag = 'exchange';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'exchange';
        $this->modcfg = $this->variable('config');
        $this->init_field();
    }

    function msm_exchange_gift() {
        $this->__construct();
    }

    function init_field() {
        $this->add_field('catid,city_id,sid,name,sort,pattern,reviewed,starttime,endtime,randomcodelen,randomcode,available,displayorder,description,price,point,point3,point4,num,pointtype,pointtype2,pointtype3,pointtype4,allowtime,timenum,usergroup');
        $this->add_field_fun('catid,sid,city_id,sort,pattern,available,reviewed,starttime,endtime,randomcodelen,randomcode,displayorder,price,point,point3,point4,num,timenum', 'intval');
        $this->add_field_fun('name,pointtype,pointtype2,pointtype3,pointtype4,allowtime,usergroup', '_T');
        $this->add_field_fun('description', '_HTML');
    }

    function save($post,$giftid=null) {
        $edit = $giftid != null;
        if($edit) {
            if(!$detail = $this->read($giftid)) redirect('exchange_gift_empty');
        }
        //上传图片部分
        if(!empty($_FILES['picture']['name'])) {
            $this->loader->lib('upload_image', NULL, FALSE);
            $img = new ms_upload_image('picture', $this->global['cfg']['picture_ext']);
            $this->upload_thumb($img);
            $post['picture'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $post['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
        }
        $post['starttime'] = strtotime($post['starttime']);
        $post['endtime'] = strtotime($post['endtime']);
        is_array($post['allowtime'])?$post['allowtime'] = ','.implode(',',$post['allowtime']).',':$post['allowtime'] = '';
		if($post['sort']=='2'&&!$_POST['serial'] && !$giftid) redirect('exchangecp_gift_serial_empty');
        $giftid = parent::save($post, $giftid, false, true, true);
        if($edit && $post['picture']) {
            @unlink(MUDDER_ROOT . $detail['picture']);
            @unlink(MUDDER_ROOT . $detail['thumb']);
        }
		if($post['sort']=='2' && $_POST['do']!='edit') {
			$SE =& $this->loader->model('exchange:serial');
			$num = $SE->save($giftid,$_POST['serial'],false);
		}
		$this->cat_num($post['catid']);
        return $giftid;
    }

    function delete($giftids) {
        if(!$giftids = parent::get_keyids($giftids)) return;
        $where = array();
        $where['giftid'] = $giftids;
        $this->_delete($where, true);
    }

    function delete_catids($catid) {
        if(!$catid) return;
        $where = array();
        $where['catid'] = $catid;
        $this->_delete($where, false);
    }

    function _delete($where, $update_cate=true) {
        $q = $this->db->from($this->table)->where($where)->select('giftid,catid,sort,pattern,thumb,picture')->get();
        if(!$q) return;
        $giftids = $del_serial = $del_lottery = $up_cate = $del_file = array();
        while ($v = $q->fetch_array()) {
            $giftids[] = $v['giftid'];
            if($v['sort'] == '2') $del_serial = $v['giftid'];
            if($v['pattern'] == '2') $del_lottery = $v['giftid'];
            if($update_cate && $v['catid']>0) {
                if(isset($up_cate[$v['catid']])) {
                    $up_cate[$v['catid']]++;
                } else {
                    $up_cate[$v['catid']]=1;
                }
            }
            //删除图片
            if($v['thumb']) $del_file[] = $v['thumb'];
            if($v['picture']) $del_file[] = $v['picture'];
        }
        $q->free_result();
        //删除虚拟卡系信息
        if($del_serial) $this->loader->model('exchange:serial')->delete_gift($del_serial);
        //删除抽奖数据
        if($del_lottery) $this->loader->model('exchange:lottery')->delete_gift($del_lottery);
        //删除图片
        if($del_file) foreach($del_file as $file) if(is_file(MUDDER_ROOT.$file)) @unlink(MUDDER_ROOT.$file);
        //删除礼品
        parent::delete($giftids);
        //更新分类统计
        if($up_cate) {
            $cate = $this->loader->model('exchange:category');
            foreach ($up_cate as $catid => $num) {
                $cate->updatenum($catid, -$num);
            }
        }
    }

	function update_num($giftid,$num) {
		$this->db->from($this->table);
		$this->db->where('giftid',$giftid);
		$this->db->set('num',$num);
		$this->db->update();
	}

	function cat_num($catid,$num=1) {
		$this->db->from('dbpre_exchange_category');
		$this->db->where('catid',$catid);
		$this->db->set_add('num',$num);
		$this->db->update();
	}

    function update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $id => $val) {
            $val['available'] = (int)$val['available'];
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('giftid', $id);
            $this->db->update();
        }
    }

    //上传图片
    function upload_thumb(& $img) {
        $thumb_w = $this->modcfg['thumb_w'] ? $this->modcfg['thumb_w'] : 160;
        $thumb_h = $this->modcfg['thumb_h'] ? $this->modcfg['thumb_h'] : 100;

        $img->set_max_size($this->global['cfg']['picture_upload_size']);
        $img->userWatermark = $this->global['cfg']['watermark'];
        $img->watermark_postion = $this->global['cfg']['watermark_postion'];
        $img->thumb_mod = $this->global['cfg']['picture_createthumb_mod'];
        //$img->limit_ext = array('jpg','png','gif');
        $img->set_ext($this->global['cfg']['picture_ext']);
        $img->set_thumb_level($this->global['cfg']['picture_createthumb_level']);
        $img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h);
        $img->upload('exchange');
    }

    function check_post(& $post, $edit = false) {
        if(!$post['name']) redirect('exchangecp_gift_name_empty');
        if(!$post['price']) redirect('exchangecp_gift_price_empty');
        if($post['sort']=='1'&&!$post['num']) redirect('exchangecp_gift_num_empty');
        if(!$post['picture'] && !$edit) redirect('exchangecp_gift_picture_empty');
        if(!$post['description']) redirect('exchangecp_gift_description_empty');
        if($post['point']&&!$post['pointtype2']) redirect('您选择了支持第二种积分兑换，却没选择第二种积分类型，请返回填写。');
        if($post['point']&&$post['pointtype'] == $post['pointtype2']) redirect('您选择的两种积分类型相同，请返回重新选择。');
    }

    //销售
    function salevolume($giftid,$num=1) {
        if(!$giftid || !$num) return;
        $this->db->from($this->table);
        if($num > 0) {
            $this->db->set_add('salevolume',$num);
            $this->db->set_dec('num',$num);
        } else {
            $this->db->set_dec('salevolume',abs($num));
            $this->db->set_add('num',abs($num));
        }
        $this->db->where('giftid',$giftid);
        $this->db->update();
    }

    //人气
    function pageview($giftid,$num=1) {
        $this->db->from($this->table);
        $this->db->set_add('pageview',$num);
        $this->db->where('giftid',$giftid);
        $this->db->update();
    }

    //生成随机字符串
    function randnum($len) {
        $chars="123456789";
        $randnum="";
        for(;$len >= 1;$len--) {
            $position=rand()%strlen($chars);
            $randnum.=substr($chars,$position,1);
        }
        return $randnum;
    }

    //检测是否已点评
    function r_exists($sid) {
        $this->db->from('dbpre_review');
        $this->db->where('uid',$this->global['user']->uid);
        $this->db->where('id', $sid);
        return $this->db->count() >= 1;
    }

}
?>