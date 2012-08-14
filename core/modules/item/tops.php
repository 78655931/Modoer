<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
defined('IN_MUDDER') or exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
define('SCRIPTNAV', 'item_subject_tops');

if($catid = _get('catid',null,MF_INT_KEY)) {
    $I->get_category($catid);
    if(!$pid = $I->category['catid']) {
        redirect('item_cat_empty');
    }
    $category = $_G['loader']->variable('category_' . $pid, 'item');
    //判断子分类是否禁用
    if(!$category[$catid]['enabled']) redirect('item_cat_disabled');
    if($catid>0) {
        $category_level = $category[$catid]['level'];
        $subcats = $category[$catid]['subcats'];
    }
    $mypid = $catid;
    $rogid = $I->category['review_opt_gid'];

    $tops = array();
    $tops[] = array (
        'title'  => '最佳' . $category[$catid]['name'],
        'name'  => '综合分',
        'params'=>array(
            'city_id' => _NULL_CITYID_,
            'catid' => $catid,
            'field' => 'avgsort',
            'orderby' => 'avgsort DESC',
            'rows' => 10,
        ),
    );
    $tops[] = array(
        'title'  => '本周热门',
        'name'  => '热门度',
        'params'=>array(
            'city_id'=>_NULL_CITYID_,
            'catid'=>$catid,
            'field'=>'pageviews',
            'orderby'=>'pageviews DESC',
            'rows'=>10,
        ),
    );
    $reviewpot = $_G['loader']->variable('opt_' . $rogid, 'review');
    foreach($reviewpot as $key => $val) {
        $tops[] = array(
            'title' => $val['name'] . '最佳',
            'name'  => '得分',
            'params'=>array(
                'city_id'=>_NULL_CITYID_,
                'catid' => $catid,
                'field' => $val['flag'],
                'orderby' => "{$val['flag']} DESC",
                'rows'=>10,
            ),
        );
    }
    $tops[] = array(
        'title' => '编辑推荐',
        'name'  => '推荐度',
        'params'=>array(
            'city_id'=>_NULL_CITYID_,
            'catid'=>$catid,
            'field'=>'finer',
            'orderby'=>'finer DESC',
            'rows'=>10,
        ),
    );
    $tops[] = array(
        'title' => '最新加入',
        'name'  => '浏览量',
        'params'=>array(
            'city_id'=>_NULL_CITYID_,
            'catid'=>$catid,
            'field'=>'pageviews',
            'orderby'=>'addtime DESC',
            'rows'=>10,
        ),
    );
    $tops[] = array(
        'title' => '随机推荐',
        'name'  => '点评量',
        'params'=>array(
            'city_id'=>_NULL_CITYID_,
            'catid'=>$catid,
            'field'=>'avgsort',
            'orderby'=>'rand()',
            'rows'=>10,
        ),
    );

}

$active['catid'][(int)$catid] = ' class="selected"';

//seo设置
$seo_tags = get_seo_tags();
$seo_tags['root_category_name'] = display('item:category',"catid/$pid");
$seo_tags['current_category_name'] = display('item:category',"catid/$catid");
$seo_tags['tops_title'] = lang('item_tops_title');

!$MOD['seo_tops_title'] && $MOD['seo_tops_title'] = '{current_category_name},{tops_title},{site_name}';
!$MOD['seo_tops_keywords'] && $MOD['seo_tops_keywords'] = '{site_keywords}';
!$MOD['seo_tops_description'] && $MOD['seo_tops_description'] = '{site_description}';

$_HEAD['title'] = parse_seo_tags($MOD['seo_tops_title'], $seo_tags);
$_HEAD['keywords'] = parse_seo_tags($MOD['seo_tops_keywords'], $seo_tags);
$_HEAD['description'] = parse_seo_tags($MOD['seo_tops_description'], $seo_tags);

$_G['show_sitename'] = FALSE;

include template('item_tops');
?>