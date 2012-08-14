<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'link');

$LK =& $_G['loader']->model('link:mylink');
$links = array();

list(,$list_logo) = $LK->find('*',array('ischeck'=>1,'nq_logo'=>1),'displayorder',0,0,0);
if($list_logo) {
    while($val=$list_logo->fetch_array()) {
        $links['logo'][] = $val;
    }
    $list_logo->free_result();
}
list(,$list_char) = $LK->find('*',array('ischeck'=>1,'logo'=>''),'displayorder',0,0,0);
if($list_char) {
    while($val=$list_char->fetch_array()) {
        $links['char'][] = $val;
    }
    $list_char->free_result();
}

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('link_index');
?>