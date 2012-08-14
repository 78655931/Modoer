<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_itembase extends ms_model {

    var $model_flag = 'item';
    var $category = '';
    var $model = '';

    var $subject_table = 'dbpre_subject';
    var $review_table = 'dbpre_review';

    function __construct() {
        parent::__construct();
    }

    function msm_item_itembase() {
        $this->__construct();
    }
    
    // 取当前(主)分类配置信息
    function get_category($catid, $get_parent = TRUE) {
        $C =& $this->loader->model('item:category');
        $pid = $C->get_parent_id($catid, 1);
        if($get_parent) {
            $cate = $this->variable('category');
            $this->category = $cate[$pid];
            return $this->category;
        }
        $cate = $this->variable('category_' . $pid);
        $this->category = $cate[$catid];
        return $this->category;
    }

    // 取得子分类的id列表
    function get_sub_catids($pid) {
        $C =& $this->loader->model('item:category');
        $root_id = $C->get_parent_id($pid, 1);
        $cate = $this->variable('category_'.$root_id);
        $ids = array();
        foreach($cate as $val) {
            if($val['pid'] == $pid) {
                $ids[] = $val['catid'];
            }
        }
        return $ids;
    }

    // 取当前模型信息
    function get_model($modelid, $iscatid = FALSE) {
        if($iscatid) {
            if(!$category = $this->get_category($modelid)) return;
            $modelid = $category['modelid'];
        }
        $this->model = $this->variable('model_' . $modelid);
        return $this->model;
    }

    //获取分类主ID
    function get_pid($catid, $get_level = 1) {
        if(!$rel = $this->variable('category_rel')) return false;
        if(!$rel[$catid]) return false;
        list($pid, $level) = explode(':', $rel[$catid]);
        if($level == $get_level) return $catid;
        if($level-1 == $get_level) return $pid;
        if($get_level < $level-1) {
            list($pid, $level) = explode(':', $rel[$pid]);
        }
        if($level-1 == $get_level) return $pid;
    }

    //获取模型ID
    function get_modelid($catid) {
        $pid = $this->get_pid($catid);
        $cate = $this->variable('category');
        return $cate[$catid]['modelid'];
    }

    //取得属性ID
    function get_attid($catid) {
        $pid = $this->get_pid($catid);
        $cats = $this->variable('category_'.$pid);
        return (int) $cats[$catid]['attid'];
    }

    //取得模型的附表
    function get_table($id, $isModelId = FALSE) {
        $modelid = $isModelId ? $isModelId : $this->get_modelid($id);
        $model = $this->variable('model_' . $modelid);
        return 'dbpre_' . $model['tablename'];
    }

    //判断是不是主分类
    function is_root_category($catid) {
        $cate = $this->variable('category');
        return $cate[$catid]['pid'] == 0;
    }

}
?>