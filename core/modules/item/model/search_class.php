<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_search extends msm_item_itembase {

    var $table = 'dbpre_search_cache';
	var $key = 'ssid';

	function __construct() {
		parent::__construct();
		$this->init_field();
	}

	function msm_item_search() {
		$this->__construct();
	}

	function init_field() {
		parent::add_field('keyword,city_id,catid,ordersort,ordertype');
		parent::add_field_fun('catid', 'intval');
        parent::add_field_fun('keyword,ordersort,ordertype', 'trim');
	}

    function read_k($keyword, $catid=0, $city_id=null) {
        $this->db->from($this->table);
        is_numeric($catid) && $this->db->where('catid', $catid);
        $city_id && $this->db->where('city_id', implode(',',$city_id));
        $this->db->where('keyword', $keyword);
        return $this->db->get_one();
    }

    function get_sql() {
        $post = $this->get_post($_GET);
        $this->check_post($post);

        $search_life = (int) _G('cfg','search_life');
        $search_life = $search_life > 0 ? ($search_life * 60) : 3600; //秒
        $detail = $this->read_k($post['keyword'], $post['catid']);
        //检测搜索缓存
        if($detail && $detail['dateline'] > $this->global['timestamp'] - $search_life) {
            if(!$detail['total']) return '';
        }

        $post = $this->convert_post($post);

        //在没有搜索缓存或者缓存中有结果时，对结果进行搜索
        $this->db->from('dbpre_subject');
        $this->db->select('*');
        if($post['city_id']) $this->db->where('city_id', $post['city_id']);
        if($post['catid']>0) $this->db->where('pid', $post['catid']);
        //$this->db->where_like('name', "%{$post['keyword']}%", 'AND', '(');
        //$this->db->where_like('subname', "%{$post['keyword']}%", 'OR' , ')');
        $this->db->where_concat_like('name,subname', "%{$post['keyword']}%");
        $this->db->where('status', 1);
        $this->db->order_by($post['ordersort'], $post['ordertype']);
        return $this->db->get_sql();
    }

    function search($start=0, $offset=0, $life=TRUE) {
        $post = $this->get_post($_GET); //获取
        $this->check_post($post); //验证
        $post = $this->convert_post($post); //转换
        $result = array(0, ''); //结果
        if($life) {
            $config = $this->variable('config');
            $search_life = $config['search_life'] > 0 ? ($config['search_life'] * 60) : 3600; //秒
        }
        if($detail = $this->read_k($post['keyword'], $post['catid'], $post['city_id'])) {
            $ssid = $detail['ssid'];
            if($life && $detail['dateline'] >= $this->global['timestamp'] - $search_life) {
                if($detail['total']) {
                    $result[0] = $detail['total'];
                } else {
                    return $result;
                }
            }
        }

        //在没有搜索缓存或者缓存中有结果时，对结果进行搜索
        $this->db->from('dbpre_subject');
        $this->db->select('*');
        if($post['city_id']) $this->db->where('city_id', $post['city_id']);
        if($post['catid']>0) $this->db->where('pid', $post['catid']);
        $this->db->where_concat_like('name,subname', "%{$post['keyword']}%");
        $this->db->where('status', 1);

        if(!$result[0]) {
            if($result[0] = $this->db->count()) {
                $this->db->sql_roll_back('from,select,where');
            }
        }
        if($result[0]) {
            $orderby = array('finer'=>'DESC',$post['ordersort']=>$post['ordertype']);
            $this->db->order_by($orderby);
            $this->db->limit($start, $offset);
            $result[1] = $this->db->get();
        }
        $this->db->clear();

        if($ssid) {
            $this->update_result($detail['ssid'], $result[0]);
        } else {
            $this->save_result($post, $result[0]);
        }
        return $result;
    }

    function update_result($ssid, $total=-1) {
        $this->db->from($this->table);
        $this->db->set_add('count');
        $total>-1 && $this->db->set('total', $total);
        $this->db->where('ssid', $ssid);
        $this->db->update();
    }

    function save_result($post, $total) {
        $this->db->from($this->table);
        $this->db->set('keyword', $post['keyword']);
        $this->db->set('catid', (int) $post['catid']);
        if($post['city_id']) $this->db->set('city_id', implode(',',$post['city_id']));
        $this->db->set('count', 1);
        $this->db->set('dateline', $this->global['timestamp']);
        $this->db->set('total', $total);
        $this->db->insert();
        return $this->db->insert_id();
    }

    function check_post($post) {

        $order_arr = array('addtime','avgsort','reviews','pageviews');
        $ordertype_arr = array('desc','asc');

        if(trim($post['keyword'])=='') redirect(lang('item_search_keyword_empty'));
        if(strlen($post['keyword']) < 2) redirect(lang('item_search_keyword_len'));
        if(!in_array($post['ordersort'], $order_arr)) redirect(lang('global_sql_where_invalid', 'ordersort'));
        if(!in_array($post['ordertype'], $ordertype_arr)) redirect(lang('global_sql_where_invalid', 'ordertype'));
        if(!is_numeric($post['catid'])) redirect(lang('global_sql_keyid_invalid', 'catid'));
    }

}
?>