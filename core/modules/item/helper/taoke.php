<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

function taoke_item_root_cats($select='') {
    global $_G;
    $modcfg = $_G['loader']->variable('config','item'); 
    $TB =& $_G['loader']->lib('taobao');
    $TB->set_appkey($modcfg['taoke_appkey'], $modcfg['taoke_appsecret'], $modcfg['taoke_sessionkey']);
    $TaobaokeData = $TB->set_method('taobao.shopcats.list.get')
        ->set_param('fields','cid,parent_cid,name,is_parent')
		->set_param('parent_cid','0')
        ->get_data();
    $content = '';
    if($TaobaokeData['shop_cats']['shop_cat']) {
        $content = '';
        foreach($TaobaokeData['shop_cats']['shop_cat'] as $v) {
            $selected = $v['cid'] == $select ? ' selected' : '';
            $content .= "\t<option value=\"{$v['cid']}\"$selected>$v[name]</option>\r\n";
        }
    }
    return $content;
}

function taoke_item_shops() {
    global $_G;

    $modcfg = $_G['loader']->variable('config','item');
    $TB =& $_G['loader']->lib('taobao');
    $TB->set_appkey($modcfg['taoke_appkey'], $modcfg['taoke_appsecret'], $modcfg['taoke_sessionkey']);

    $_GET['cid'] = (int) $_GET['cid'];
    $_GET['keyword'] = _T($_GET['keyword']);
    if($_GET['cid']||$_GET['keyword']) {
        $_GET['cid'] && $ItemcatsParam['cid'] = $_GET['cid'];
        $_GET['keyword'] && $ItemcatsParam['keyword'] = $_GET['keyword'];
    } else {
        redirect('item_taobaoke_search_key_empty');
    }
    if($_GET['only_mall']) $ItemcatsParam['only_mall'] = 'true';
    if($_GET['start_credit']>0) $ItemcatsParam['start_credit'] = taoke_item_credit_string($_GET['start_credit']);
    if($_GET['end_credit']>0) $ItemcatsParam['end_credit'] = taoke_item_credit_string($_GET['end_credit']);
    if($_GET['start_commissionrate']>0) $ItemcatsParam['start_commissionrate'] = $_GET['start_commissionrate']*100;
    if($_GET['end_commissionrate']>0) $ItemcatsParam['end_commissionrate'] = $_GET['end_commissionrate']*100;
    if($_GET['start_auctioncount']>0) $ItemcatsParam['start_auctioncount'] = $_GET['start_auctioncount'];
    if($_GET['end_auctioncount']>0) $ItemcatsParam['end_auctioncount'] = $_GET['end_auctioncount'];
    if($_GET['start_totalaction']>0) $ItemcatsParam['start_totalaction'] = $_GET['start_totalaction'];
    if($_GET['end_totalaction']>0) $ItemcatsParam['end_totalaction'] = $_GET['end_totalaction'];
    $ItemcatsParam['page_no'] = $_GET['page']<99 ? $_GET['page'] : 99;
    $ItemcatsParam['page_size'] = $_GET['offset']<40 ? $_GET['offset'] : 40;
    $ItemcatsParam['nick'] = $modcfg['taoke_nick'];
    $ItemcatsParam['fields'] = 'user_id,click_url,shop_title,commission_rate,seller_credit,shop_type,auction_count,total_auction,nick';

    $TaobaokeData = $TB->set_method('taobao.taobaoke.shops.get')->set_params($ItemcatsParam)->get_data();
    if($TaobaokeData['total_results']) {
        return array($TaobaokeData['total_results'], $TaobaokeData['taobaoke_shops']['taobaoke_shop']);
    }
    return;
}

function taoke_item_credit_string($credit) {
    $credit = (int) $credit;
    if($credit<1) return '';
    if($credit<=5) return $credit.'heart';
    if($credit<=10) return ($credit-5).'diamond';
    if($credit<=15) return ($credit-10).'crown';
    if($credit<=20) return ($credit-15).'goldencrown';
    return '';
}

function taoke_item_credit_img($credit) {
    $credit = (int) $credit;
    if($credit<1) return URLROOT . '/static/images/rank/s_red_zero.gif';
    if($credit<=5) return URLROOT . '/static/images/rank/s_red_'.$credit.'.gif';
    if($credit<=10) return URLROOT . '/static/images/rank/s_blue_'.($credit-5).'.gif';
    if($credit<=15) return URLROOT . '/static/images/rank/s_cap_'.($credit-10).'.gif';
    if($credit<=20) return URLROOT . '/static/images/rank/s_crown_'.($credit-15).'.gif';
    return URLROOT . '/static/images/rank/s_crown_5.gif';
}

function taoke_item_shop_detail($user_id) {
	global $_G;

    $modcfg = $_G['loader']->variable('config','item');
    $TB =& $_G['loader']->lib('taobao');
    $TB->set_appkey($modcfg['taoke_appkey'], $modcfg['taoke_appsecret'], $modcfg['taoke_sessionkey']);

	$SP =& $_G['loader']->lib('snoopy');
	$SP->fetch("http://rate.taobao.com/user-rate-".$user_id.".htm");
	if(preg_match('/data-nick="(.*?)"\s+data-tnick/i', $SP->results, $match)) {
		$nick = trim($match[1]);
        if(strposex($SP->results,'text/html; charset=GBK') && $_G['charset'] == 'utf-8') {
            $nick = charset_convert($nick,'gbk','utf-8');
        }
		if(preg_match('/%[A-Z0-9]{2}/',$nick)) {
			$nick = rawurldecode($nick);
			if($_G['charset']=='gb2312') {
                $nick = charset_convert($nick,'utf-8','gbk');
            }
		}
		$Param = array();
		$Param['fields'] = 'sid,cid,title,nick,desc,bulletin,pic_path,created,modified';
		$Param['nick'] = $nick;
		$Data = $TB->set_method('taobao.shop.get')->set_params($Param)->get_data();
		if($Data['shop']) {
			$Data['shop']['pic_path'] && $Data['shop']['pic_path'] = 'http://logo.taobao.com/shop-logo' . $Data['shop']['pic_path'];
			return $Data['shop'];
		}
		return ;
	}
	return;
}

function taoke_check_field($cid, $fields) {
	global $_G;

	if(!$cid) redirect('item_taobaoke_import_catid_empty');
	if(!$fields) redirect('item_taobaoke_import_linkfiled_empty');
	$result = array();
	
	$IB =& $_G['loader']->model('item:itembase');
	if(!$model = $IB->get_model($cid, true)) redirect('item_taobaoke_import_cannot_model');
	$sysfields = $_G['loader']->variable('field_'.$model['modelid'], 'item');
	
	$need = 0;
	$es = null;
	foreach($fields as $k=>$v) {
		if(!$sysfields[$k]['allownull'] && !$v) {
			$need++;
			$es[] = $sysfields[$k]['title'].'['.$sysfields[$k]['fieldname'].']';
		} elseif($v) {
			$result[$k] = $v;
		}
	}
	if($es) redirect(implode('£¬',$es) . '"item_taobaoke_import_select_link_empty');
	return $result;
}

function taoke_item_save_store() {
	global $_G, $C;
	if(!$_POST) redirect('item_taobaoke_store_empty.');
	$user_id = _post('user_id', null, MF_INT);
	if(!$user_id || $user_id < 0) redirect('item_taobaoke_store_ownerid_invalid');
	if(!$result = taoke_item_shop_detail($user_id)) redirect(lang('item_taobaoke_store_getifo_invalid').'[user_id:'.$user_id.']');

	$mapping = array('content'=>'desc','wangwang'=>'nick');
	$data = array_merge($_POST, $result);
	foreach($mapping as $k=>$v) {
		$data[$k] =& $data[$v];
	}
	//if(is_array($data['content'])) {print_r($data['content']);exit;}
	$variable = $C->read('taoke_catid', MOD_FLAG);
	if(!$catid = $variable['value']) redirect('item_taobaoke_inport_cannot_catid');
	$IB =& $_G['loader']->model('item:itembase');
	if(!$model = $IB->get_model($catid, true)) redirect('item_taobaoke_import_cannot_model');
	$sysfields = $_G['loader']->variable('field_'.$model['modelid'], 'item');
	$fields = $C->read('taoke_link_fields', MOD_FLAG);
	$fields['value'] && $fields['value'] = unserialize($fields['value']);
	if(!$fields['value']) redirect('item_taobaoke_inport_cannot_field');
	$item = array();
	foreach($fields['value'] as $fieldid=>$key) {
		$name = $sysfields[$fieldid]['fieldname'];
		$item[$name] = isset($data[$key]) ? (empty($data[$key])?'':$data[$key]) : '';
	}
	$STK =& $_G['loader']->model('item:subjecttaoke');
	$exists = $STK->exists($user_id);
	if($exists && !$_POST['update']) redirect('item_taobaoke_store_exists');
	$item['catid'] = $catid;
	$item['status'] = 1;
	$S =& $_G['loader']->model('item:subject');
	//store info
	$item['content'] = preg_replace("/\s+style=\".*?\"/",'',$item['content']);
	$item['content'] = preg_replace("/<font(.*?)>/i","",$item['content']);
	$item['content'] = str_replace("</font>","",$item['content']);
	$item['content'] = preg_replace("/<a\s+href=\"#\">(.*?)<\/a>/","\\1",$item['content']);
	//import
	$sid = $S->save($item,$exists?$exists['sid']:null);
	if($sid > 0) {
		$STK->add($user_id,$sid,$data['nick']);
		//download image
		if($data['pic_path']) {
			if($ext = strtolower(pathinfo($data['pic_path'], PATHINFO_EXTENSION))) {
				$UP =& $_G['loader']->model('item:picture');
				$post['sid'] = $sid;
				$post['title'] = lang('item_taoke_logo_title');
				$post['download_src'] = $data['pic_path'];
				$UP->save($post,TRUE,TRUE);		
			}
		}
		return $data;
	}
}
?>