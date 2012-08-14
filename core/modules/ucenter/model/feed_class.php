<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('member:feed',false, NULL, false);
class msm_ucenter_feed extends msm_member_feed {

    var $cfg = array();

    function __construct() {
        parent::__construct();
        $this->model_flag = 'ucenter';
        $this->cfg = $this->variable('config');
    }

    //检测本功能是否开启
    function enabled() {
        return ($this->modcfg['feed_enable'] || $this->cfg['uc_feed']);
    }

    function read($feedids) {
    }

    function find() {
    }

    function all($limit = 100) {
        return uc_feed_get($limit);
    }

    function save($module, $city_id, $icon, $uid, $username, $content) {
        if($this->modcfg['feed_enable']) parent::save($module, $city_id, $icon, $uid, $username, $content);
        if(!$this->cfg['uc_feed']) return;
        return uc_feed_add($icon, $uid, $username, $content['title_template'], $content['title_data'], 
            $content['body_template'], $content['body_data'], $content['body_general'], '', $content['images']);
    }

    function save_ex($params, $content) {
        if($this->modcfg['feed_enable']) {          
            parent::save_ex($params, $content);
        }

        if(!$this->cfg['uc_feed']) return;
        return uc_feed_add($params['icon'], $params['uid'], $params['username'], $content['title_template'], 
            $content['title_data'], $content['body_template'], $content['body_data'], $content['body_general'], 
            '', $content['images']);
    }

}