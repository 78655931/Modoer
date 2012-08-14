<?php
!defined('IN_MUDDER') && exit('Access Denied');
return array (
  5 => 
  array (
    'callid' => '5',
    'module' => 'item',
    'calltype' => 'sql',
    'name' => '主题_会员参与',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}membereffect_total mt LEFT JOIN {dbpre}subject s ON (mt.id=s.sid)',
      'select' => 'mt.{effect} as effect,s.sid,s.catid,s.name,s.subname',
      'where' => 'mt.idtype=\'{idtype}\' AND mt.{effect}>0 AND s.city_id IN ({city_id})',
      'other' => '',
      'orderby' => 'mt.{effect} DESC',
      'limit' => '0,10',
      'cachetime' => '1000',
    ),
    'tplname' => 'item_subject_effect_li',
    'empty_tplname' => 'empty_li',
    'closed' => '0',
    'hash' => '',
  ),
  6 => 
  array (
    'callid' => '6',
    'module' => 'item',
    'calltype' => 'sql',
    'name' => '主题_同类主题',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}subject',
      'select' => 'sid,pid,catid,name,subname,avgsort,pageviews,reviews',
      'where' => 'city_id IN ({city_id}) and catid={catid} and status=1 and sid!={sid}',
      'other' => '',
      'orderby' => 'addtime DESC',
      'limit' => '0,10',
      'cachetime' => '1000',
    ),
    'tplname' => 'item_subject_li',
    'empty_tplname' => 'empty_li',
    'closed' => '0',
    'hash' => '',
  ),
  7 => 
  array (
    'callid' => '7',
    'module' => 'item',
    'calltype' => 'sql',
    'name' => '主题_附近主题',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}subject',
      'select' => 'sid,pid,catid,name,subname,avgsort,pageviews,reviews',
      'where' => 'aid={aid} and status=1 and sid!={sid}',
      'other' => '',
      'orderby' => 'addtime DESC',
      'limit' => '0,10',
      'cachetime' => '1000',
    ),
    'tplname' => 'item_subject_li',
    'empty_tplname' => 'empty_li',
    'closed' => '0',
    'hash' => '',
  ),
  8 => 
  array (
    'callid' => '8',
    'module' => 'item',
    'calltype' => 'sql',
    'name' => '主题_相关主题',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}subject',
      'select' => 'sid,pid,catid,name,subname,avgsort,pageviews,reviews',
      'where' => 'city_id IN ({city_id}) and name=\'{name}\' and status=1 and sid!={sid}',
      'other' => '',
      'orderby' => 'addtime DESC',
      'limit' => '0,10',
      'cachetime' => '1000',
    ),
    'tplname' => 'item_subject_li',
    'empty_tplname' => 'empty_li',
    'closed' => '0',
    'hash' => '',
  ),
  11 => 
  array (
    'callid' => '11',
    'module' => 'item',
    'calltype' => 'sql',
    'name' => '首页_推荐主题',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}subject',
      'select' => 'sid,aid,name,subname,avgsort,thumb,description',
      'where' => 'city_id IN ({city_id}) AND pid={pid} AND status=1 AND finer>0',
      'other' => '',
      'orderby' => 'finer DESC',
      'limit' => '0,8',
      'cachetime' => '1000',
    ),
    'tplname' => 'index_subject_finer',
    'empty_tplname' => 'empty_div',
    'closed' => '0',
    'hash' => '',
  ),
  16 => 
  array (
    'callid' => '16',
    'module' => 'product',
    'calltype' => 'sql',
    'name' => '产品_主题产品',
    'fun' => 'sql',
    'var' => 'mydata',
    'cachetime' => '1000',
    'expression' => 
    array (
      'from' => '{dbpre}product',
      'select' => 'pid,catid,subject,grade,description,thumb,comments,pageview',
      'where' => 'sid={sid} AND status=1',
      'other' => '',
      'orderby' => 'grade DESC,comments DESC',
      'limit' => '0,10',
      'cachetime' => '1000',
    ),
    'tplname' => 'product_pic_li',
    'empty_tplname' => 'empty_li',
    'closed' => '0',
    'hash' => '',
  ),
); 
?>