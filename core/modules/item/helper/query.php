<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class query_item {

    function category($params) {
        extract($params);
        $loader =& _G('loader');
        $pid = (int) $pid;
        if($pid > 0) {
            $C =& $loader->model('item:category');
            $root_id = $C->get_parent_id($pid);
            if(!$category = $loader->variable('category_' . $root_id, 'item')) return '';
        } else {
            $category = $loader->variable('category','item');
        }
        $result = ''; $index = 0;
        foreach($category as $key => $val) {
            if($val['pid'] == $pid && $val['enabled']) {
                if($num>0 && ++$index > $num) break;
                //if($usearea && !$val['usearea']) continue;
                $result[] = $val;
            }
        }
        return $result;
    }

    function hotcategory($params) {
    }

    function subject($params) {
        extract($params);
        $loader =& _G('loader');
        $S =& $loader->model('item:subject');

        $S->db->select($select?$select:'*');
        $S->db->from($S->table);
		if($city_id) $S->db->where('city_id',explode(',',$city_id));
        if($pid>0) $S->db->where('pid', $pid);
        if($catid>0) $S->db->where('catid', $catid);
        if($aid>0) $S->db->where('aid', $aid);
        if($finer>0) $S->db->where_more('finer', $finer);
        $S->db->where('status', 1);
        $orderby && $S->db->order_by($orderby);
        $S->db->limit($start, $rows);
        if(!$r = $S->db->get()) { return null; }

        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    function review($params) {
        extract($params);
        $loader =& _G('loader');
        $db =& _G('db');
        if(!$select) $select = 'rid,pcatid,city_id,sid,uid,username,ip,title,content,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,price,best,posttime';
        foreach(explode(',', $select) as $v) {
            if(!trim($v)) continue; $db->select('r.' . $v);
        }
        if($select_subject) {
            $db->join('dbpre_review','r.sid','dbpre_subject','s.sid', 'LEFT JOIN');
            foreach(explode(',', $select_subject) as $v) {
                if(!trim($v)) continue; $db->select('s.' . $v);
            }
        } else {
            $db->from('dbpre_review','r');
        }
		if($city_id) $db->where('r.city_id',explode(',',$city_id));
        if($idtype) $db->where('r.idtype',explode(',',$idtype));
        if($pid>0) $db->where('r.pcatid', $pid);
        if($id>0) $db->where('r.id', $id);
        if($uid>0) $db->where('r.uid', $uid);
        if(isset($best)) $db->where('r.best', (int)$best);
        if(isset($digst)) $db->where('r.digst', (int)$digst);
        if(isset($havepic)) $db->where('r.havepic', (int)$havepic);
        $db->where('r.status', 1);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r = $db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    function picture($params) {
        extract($params);
        $loader =& _G('loader');

        $S =& $loader->model('item:picture');
        $S->db->from($S->table);
        $S->db->select($select?$select:'*');
		if($city_id) $S->db->where('city_id',explode(',',$city_id));
        if($sid>0) $S->db->where('sid', $sid);
        if($uid>0) $S->db->where('uid', $uid);
        $S->db->where('status', 1);
        $orderby && $S->db->order_by($orderby);
        $S->db->limit($start, $rows);

        if(!$r=$S->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    function tag($params) {
        $loader =& _G('loader');
        extract($params);

        $TAG =& $loader->model('item:tag');
        $TAG->db->select($select?$select:'*');
        $TAG->db->from($TAG->table);
		if($city_id) $TAG->db->where('city_id',explode(',',$city_id));
        $TAG->db->where('closed', 0);
        $orderby && $TAG->db->order_by($orderby);
        $TAG->db->limit($start, $rows);

        if(!$r=$TAG->db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    function options($params) {
        extract($params);
        if(!$value) return '';
        $loader =& _G('loader');
        if($catid > 0 && !$modelid) { 
            $pid = $loader->model('item:category')->get_parent_id($catid);
            $category = $loader->variable('category', 'item');
            $modelid = $category[$pid]['modelid'];
        }
        $fields = $loader->variable('field_'.$modelid, 'item', false);
        if(!$fields) return '';
        foreach($fields as $_val) {
            if($_val['fieldname'] == $fieldname && $_val['type'] == 'option') {
                $options = $_val['config']['value'];
            }
        }
        if(!$options) return $value;
        $result = array();
        $__val = explode(",", $value);
        $list = explode("\r\n", preg_replace("/\s*(\r\n|\n\r|\n|\r)\s*/", "\r\n", $options));
        foreach($list as $sval) {
            $v = explode("=",$sval);
            if($__val && in_array($v[0], $__val)) {
                $result[] = $v[1];
            }
        }
        return $result;
    }

    //catid
    function reviewopt($params) {
        extract($params);
        $loader =& _G('loader');
        $result = array();
        if($catid > 0 && !$modelid) {
            $category = $loader->variable('category', 'item');
            if(isset($category[$catid])) {
                if($category[$catid]['pid'] > 0) {
                    $pid = $category[$catid]['pid'];;
                } else {
                    $pid = $catid;
                }
            }
            $modelid = $category[$pid]['modelid'];
            $rogid = $category[$pid]['review_opt_gid'];
        }
        $result = $loader->variable('opt_' . $rogid, 'review', false);
        return $result;
    }

    //catid
    function attlist($params) {
        extract($params);
        $loader =& _G('loader');
        $result = array();
        if(!$catid = abs((int)$catid)) return null;
        $atts = $loader->variable('att_list_'.$catid, 'item');
        return $atts;
    }

    //sid,rows,cachetime,orderby
    function impress($params) {
        $loader =& _G('loader');
        $db =& _G('db');
        extract($params);

        $db->from('dbpre_subjectimpress');
        if($sid > 0) $db->where('sid', $sid);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

	//sid,albumid,orderby
	function album($params) {
        $loader =& _G('loader');
        $db =& _G('db');
        extract($params);

		if(!$subject_select) {
			$db->from('dbpre_album','a');
		} else {
			$db->join('dbpre_album','a.sid','dbpre_subject','s.sid');
		}
		$db->select($select?$select:'a.*');
		if($subject_select) $db->select($subject_select);
		if($city_id) $db->where('a.city_id', explode(',', $city_id));
        if($albumid >0) $db->where('a.albumid', $sid);
		if($sid > 0) $db->where('a.sid', $sid);
		if($pageview) $db->where_more('a.pageview', $pageview);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }
        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
	}

    //catid,field,totaltime,orderby
    function top($params) {

        $loader =& _G('loader');
        $db =& _G('db');
        extract($params);

        $D =& $loader->model('datacall');
        $I =& $loader->model('item:subject');

        if(!$totaltime || $totaltime<10) $totaltime=3000;
        $cachedir = $D->cachedir . 'tops' . DS;

        if(!is_dir(MUDDER_ROOT . $cachedir)) {
            if(@mkdir(MUDDER_ROOT . $cachedir, 0777)) {
                show_error(sprintf(lang('global_mkdir_no_access'), str_replace(MUDDER_ROOT,'./',$cachedir)));
            }
        }

        $file_exists = $compare = false;
        $result = array();

        $cachename = sprintf("subject_tops_%s", create_identifier($params));
        $lastcache =  MUDDER_ROOT . $cachedir . 'item_' . $cachename . '.php';
        $comparecache = MUDDER_ROOT . $cachedir . 'item_' . $cachename . '_old.php';

        if($file_exists = is_file($lastcache)) {
            if(!check_cache($lastcache, $totaltime)) {
                @unlink($comparecache);
                rename($lastcache, $comparecache);
                $compare = true;
            } else {
                $result = read_cache($lastcache);
            }
        }

        if(!$file_exists || $compare) {
            $cmp = read_cache($comparecache);
            $index = 1;
            $db->from($I->table);
            $db->select('sid,pid,catid,name,subname');
            $db->select($field,'value');
            if($city_id) $db->where('city_id',explode(',',$city_id));
            if(isset($pid) && $pid>0) $db->where('pid',$pid);
            if(isset($catid) && $catid>0) $db->where('catid',array_merge((array)$catid, $I->get_sub_catids($catid)));
            if(isset($aid) && $aid>0) $db->where('aid',$aid);
            $db->where('status',1);
            $orderby && $db->order_by($orderby);
            $db->limit($start, $rows);
            if($query = $db->get()) {
                while($row = $query->fetch_array()) {
                    $row['fullname'] = $I->get_subject($row);
                    $row['index'] = $index++;
                    if($compare && $tmp = $cmp[$row['sid']]) {
                        $row['trend'] = $row['index'] > $tmp['index'] ? 'up' : ($row['index'] === $tmp['index'] ? '' : 'down');
                    } else {
                        $row['trend'] = 'up';
                    }
                    $result[$row['sid']] = $row;
                }
            }
            if(count($result) > 0) {
                write_cache($cachename, arrayeval($result), $I->model_flag, 'return', $cachedir);
            }
        }

        return $result;

    }

    //fid,sid
    function related($params) {
        $loader =& _G('loader');
        $db =& _G('db');
        extract($params);

        $db->select('s.sid,s.name,s.subname,s.thumb,s.city_id,s.aid,s.catid,s.pid,s.domain,s.avgsort,
			s.reviews,s.best,s.description');
        $db->join('dbpre_subjectrelated','sr.r_sid','dbpre_subject','s.sid');
        $db->where('sr.fieldid',(int)$fieldid);
        $db->where('sr.sid',(int)$sid);
        $orderby && $db->order_by($orderby);
        $db->limit($start, $rows);

        if(!$r=$db->get()) { return null; }

        $result = array();
        while($v = $r->fetch_array()) {
            $result[] = $v;
        }
        $r->free_result();
        return $result;
    }

    //sid
    function subject_side($params) {
        $loader =& _G('loader');
        extract($params);
        if(!$sid) return;
        $I =& $loader->model('item:subject');
        $sids = explode(',', $sid);
        $result = array();
        foreach($sids as $sid) {
            if(!$val = $I->read($sid)) continue;
            $val['field_table'] = $I->display_listfield($val);
            $result[] = $val;
        }
        return $result;
    }

	function taoke_product($params) {
        $loader =& _G('loader');
        extract($params);

		$cfg = $loader->variable('config','item');
		$pid = $detail['pid'];
		$IB =& $loader->model('item:itembase');
		$modelid = $IB->get_modelid($pid);
		$sysfield = $loader->variable('field_'.$modelid, 'item');
		$nick_field = $field['config']['nick_field'];
		$nick = $detail[$sysfield[$nick_field]['fieldname']];
		
		if($field['config']['q_type']) {
			$q = '';
			if($field['config']['q_type']=='q_field') {
				$q_field = $field['config']['q_field'];
				$q = $detail[$sysfield[$q_field]['fieldname']];
			} else {
				$q = $detail[$field['fieldname']];
			}
			if($q || $field['config']['cid'] > 0) {
				return query_item::_taoke_product_q($field['config']['cid'], $q, $rows, $cfg, $field['config']);
			}
		}
		if($nick) return query_item::_taoke_product_shop($nick, $rows, $cfg, $field['config']);
		return;
	}

	function _taoke_product_shop($nick,$rows,&$modcfg,&$fieldcfg) {
		$loader =& _G('loader');

        $TB =& $loader->lib('taobao');
        $TB->set_appkey($modcfg['taoke_appkey'], $modcfg['taoke_appsecret'], $modcfg['taoke_sessionkey']);

		$Params = array();
		$Params['fields'] = 'num_iid,title,nick,pic_url,cid,price,type,delist_time,post_fee';
		$Params['nicks'] = $nick;
		$Params['page_size'] = $rows;
        $TaoData = $TB->set_method('taobao.items.get')
            ->set_params($Params)
            ->get_data();
		if($TaoData['items']['item']) {
			$num_iids = '';
			if($TaoData['items']['item']['num_iid']>0) {
				$num_iids = $TaoData['items']['item']['num_iid'];
			} else {
				foreach($TaoData['items']['item'] as $val) {
					$num_iids .= ($num_iids?',':'') . $val['num_iid'];
				}
			}
			if($num_iids) {
				$Params = array();
				$Params['method'] = 'taobao.taobaoke.items.detail.get';
				$Params['fields'] = 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume';
				$Params['num_iids'] = $num_iids;
				$Params['nick'] = $modcfg['taoke_nick'];
				$Params['page_size'] = $rows;
				$Params['sort'] = 'commissionRate_desc';
                $TaobaokeData = $TB->set_method('taobao.taobaoke.items.detail.get')
                    ->set_params($Params)
                    ->get_data();
				if($TaobaokeData['taobaoke_item_details']['taobaoke_item_detail']['item']) {
					$result = array($TaobaokeData['taobaoke_item_details']['taobaoke_item_detail']);
				} elseif($TaobaokeData['taobaoke_item_details']['taobaoke_item_detail']) {
					$result = $TaobaokeData['taobaoke_item_details']['taobaoke_item_detail'];
				}
			}
		}
		if($result) {
			foreach($result as $k=>$v) {
				$v = array_merge($v,$v['item']);
				unset($v['item']);
				$result[$k] = $v;
			}
			return $result;
		}
		return false;
	}
	
	function _taoke_product_q($cid,$q,$rows,&$modcfg,&$fieldcfg) {
		$loader =& _G('loader');

        $TB =& $loader->lib('taobao');
        $TB->set_appkey($modcfg['taoke_appkey'], $modcfg['taoke_appsecret'], $modcfg['taoke_sessionkey']);

		$Params = array();
		$Params['method'] = 'taobao.taobaoke.items.get';
		$Params['fields'] = 'num_iid,title,nick,pic_url,price,click_url,commission,commission_rate,commission_num,commission_volume,shop_click_url,seller_credit_score,item_location,volume ';
		if($cid) $Params['cid'] = $cid;
		if($q) $Params['keyword'] = $q;
		$Params['nick'] = $modcfg['taoke_nick'];
		$Params['page_size'] = $rows;
        $TaobaokeData = $TB->set_method('taobao.taobaoke.items.get')
            ->set_params($Params)
            ->get_data();

        if($TaobaokeData['taobaoke_items']['taobaoke_item']['click_url']) {
			return array($TaobaokeData['taobaoke_items']['taobaoke_item']);
		} elseif($TaobaokeData['taobaoke_items']['taobaoke_item']) {
			return $TaobaokeData['taobaoke_items']['taobaoke_item'];
		}
		return;
	}
}
?>