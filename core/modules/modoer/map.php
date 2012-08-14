<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

define('SCRIPTNAV', 'map');

if(isset($_GET['p']) && preg_match("/^[a-z0-9\-\.]+[,][a-z0-9\-\.]+$/", $_GET['p'])) {
    list($_GET['p1'], $_GET['p2']) = explode(',', $_GET['p']);
}

$areacode = _get('areacode','','_T');//_T($_GET['areacode']);
$aid = _get('aid',null,MF_INT);
if(!$aid||$aid<1) $aid = null;
$p1 = _get('p1','','_T');//_T($_GET['p1']);
$p2 = _get('p2','','_T');//_T($_GET['p2']);
if($p1 == 0) $p1 = '';
if($p2 == 0) $p2 = '';
$mark = _get('mark') ? 1 : 0;
$show = (_get('show') && $p1 && $p2) ? 1 : 0;
$title = _get('title','');
if($_G['charset'] != 'utf-8' && $title && (isset($_GET['Rewrite'])||isset($_GET['Pathinfo'])) && $_G['cfg']['utf8url']) {
    $title = charset_convert($title,'utf-8',$_G['charset']);
}
$title = _T($title);
$level = is_numeric($_GET['level']) ? $_GET['level'] : (is_numeric($_CFG['map_view_level']) ? $_CFG['map_view_level'] : 10);

$width = _get('width','500','intval'); //is_numeric($_GET['width']) ? $_GET['width'] : 500;
$height = _get('height','500','intval'); //is_numeric($_GET['height']) ? $_GET['height'] : 500;

if(!$p1 || !$p2) {
    $city_id = null;
    $city = $_G['loader']->variable('area');
    if($aid) {
        $A =& $_G['loader']->model('area');
        if($city_id = $A->get_parent_aid($aid)) {
            if($city_id != $aid) {
                $area = $_G['loader']->variable('area_'.$city_id,'',false);
                $i=0;
                $need_aid = $aid;
                do {
                    $mappoint = $area[$need_aid]['mappoint'];
                    $need_aid = $area[$need_aid]['pid'];
                    if(++$i>3) break;
                } while($mappoint == '' && $area[$need_aid]['level'] > 1);
                
            }
            if(!$mappoint) $mappoint = $city[$city_id]['mappoint'];
        }
    }
    if(!$city_id && $city) {
        $city_id = _get('city_id') > 0 ? _get('city_id') : $_CITY['aid'];
        $mappoint = $city[$city_id]['mappoint'];
    }
    if($mappoint) list($p1,$p2) = explode(',', $mappoint);
}

$mapflag = $_G['cfg']['mapflag'] ? $_G['cfg']['mapflag'] : '51ditu';
$version = 1.2;
include template('modoer_map');
?>