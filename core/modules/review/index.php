<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$IB =& $_G['loader']->model('item:itembase');
//实例化点评类
$R =& $_G['loader']->model(':review');
define('SCRIPTNAV', 'review');

!$MOD['index_pk_rand_num'] && $MOD['index_pk_rand_num']=1;
!$MOD['index_digst_rand_num'] && $MOD['index_digst_rand_num']=2;

$maxrid = $R->get_max_rid(); //取得最大rid

$same_where = array(
    'r.city_id' => (int)$_CITY['aid'],
    'r.status' => 1,
);
$orderby = "rand()";
$select = 'r.rid,r.uid,r.username,r.idtype,r.id,r.subject,r.title,r.responds,r.flowers,r.content,s.thumb';
//随机好评
$where = array_merge(array('r.best'=>1), $same_where);
$review_best = $R->rand($select, $where, $MOD['index_pk_rand_num'], $maxrid);
//随机差评
$where = array_merge(array('r.best'=>0), $same_where);
$review_bad = $R->rand($select, $where, $MOD['index_pk_rand_num'], $maxrid);
////随机精华
$where = array_merge(array('r.digest'=>1), $same_where);
$review_digest = $R->rand($select, $where, $MOD['index_digst_rand_num'], $maxrid);
//点评内容
$itemcfg = $_G['loader']->variable('config','item');
foreach(array('index_review_pids','index_top_pids') as $keyname) {
    if(!$MOD[$keyname]) $MOD[$keyname] = $itemcfg['pid'];
    $MOD[$keyname] = explode(',', $MOD[$keyname]);
}
//点评获取
$MOD['index_review_gettype'] != 'rand' && $MOD['index_review_gettype']='new';
$orderbys = array('new'=>'posttime DESC','rand'=>'rand()');
$review_get_type = $orderbys[$MOD['index_review_gettype']];

$_HEAD['keywords'] = $MOD['meta_keywords'];
$_HEAD['description'] = $MOD['meta_description'];
include template('review_index');
?>