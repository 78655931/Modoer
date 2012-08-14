<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('fielddetail', FALSE);
class msm_product_fielddetail extends msm_fielddetail {

    var $pid = 0;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'product';
    }

    function msm_product_fielddetail() {
        $this->__construct();
    }

    function detail($field, $val=null, $pid=0) {
        $this->pid = $pid;
        return parent::detail($field, $val);
    }

    function _att($val) {
        if(!$catid = $this->config['catid']) return '';
        $atts = $this->loader->variable('att_list_'.$catid, 'item');
        $content = '';
        if($val) $val = explode(',', $val);
        !$this->config['split'] && $this->config['split'] = ',';
        if($val) foreach($val as $attid) {
            if(!isset($atts[$attid])) continue;
            $name = $atts[$attid]['name'];
            $icon = $atts[$attid]['icon'];
            if($icon) $name = "<img src=\"".URLROOT."/static/images/att/$icon\" title=\"$name\">";
            if(defined('SUBJECT_CATID') && SUBJECT_CATID > 0) {
                $categorys = $this->loader->variable('category','item');
                if($categorys[SUBJECT_CATID]['level']==1) {
                    if(!empty($categorys[SUBJECT_CATID]['config']['attcat']) && in_array($catid, $categorys[SUBJECT_CATID]['config']['attcat'])) {
                        $name = sprintf("<span><a href=\"%s\">%s</a></span>", url("item/list/catid/".SUBJECT_CATID."/att/$catid.$attid"), $name);
                    }
                }
            }
            $content .= $split . $name;
            $split = $this->config['split'];
        }
        return sprintf($this->format, $this->field['title'], $content);
    }

}

?>