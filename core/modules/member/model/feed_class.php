<?php
/**
* @author moufer<moufer@163.com>
* @package modoer
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_member_feed extends ms_model {

	var $table = 'dbpre_member_feed';
    var $key = 'id';

    var $modcfg = null;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->variable('config');
    }

    function msm_member_feed() {
		$this->__construct();
    }

    //检测本功能是否开启
    function enabled() {
        return $this->modcfg['feed_enable'];
    }

    function save($flag, $city_id, $icon, $uid, $username, $content) {
        $post['flag'] = $flag;
		$post['city_id'] = (int) $city_id;
        $post['icon'] = $icon;
        $post['uid'] = $uid;
        $post['username'] = $username;
        $post['dateline'] = $this->global['timestamp'];
        $post['title'] = $this->replace($content['title_template'], $content['title_data']);
        $post['body'] = $this->replace($content['body_template'], $content['body_data']);
        $post['images'] = ($content['images'] && is_array($content['images'])) ? serialize($content['images']) : '';
        return parent::save($post);
    }

    function save_ex($params, $content) {
        $post['flag'] = $params['flag'];
        $post['city_id'] = (int) $params['city_id'];
        $post['sid'] = (int) $params['sid'];
        $post['uid'] = (int) $params['uid'];
        $post['username'] = $params['username'];
        $post['module'] = _T($params['module']);
        $post['dateline'] = $this->global['timestamp'];
        $post['icon'] = $params['icon'];
        $post['title'] = $this->replace($content['title_template'], $content['title_data']);
        $post['body'] = $this->replace($content['body_template'], $content['body_data']);
        $post['images'] = ($content['images'] && is_array($content['images'])) ? serialize($content['images']) : '';
        return parent::save($post);
    }

    function replace($template,&$values) {
        if(!$values) return $template;
        $str = $template;
        foreach($values as $key => $val) {
            $str = str_replace('{'.$key.'}', $val, $str);
        }
        return $str;
    }

    function my_just_feed($uid, $flag) {
        $this->db->from($this->table);
        $this->db->where('uid', $uid);
        $this->db->where('flag', $flag);
        $this->db->order_by('id','DESC');
        return $this->db->get_one();
    }

}
?>