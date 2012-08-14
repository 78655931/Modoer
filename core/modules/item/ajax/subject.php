<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$allow_ops = array( 'search', 'exists', 'myfavorite', 'myreviewed', 'get_membereffect', 'post_membereffect', 'post_map', 'post_log', 
    'clear_cookie_subject','sids', 'get_taoke_detail', 'get_sub_cats');
$login_ops = array( 'post_membereffect', 'add_favorite' );

$op = empty($op) || !in_array($op, $allow_ops) ? '' : $op;

if(!$op) {
    redirect('global_op_unkown');
} elseif(!$user->isLogin && in_array($op, $login_ops)) {
    redirect('member_not_login');
}

switch($op) {

case 'sids':

    $sids = _post('sids', null, MF_TEXT);
    if(!$sids) redirect(lang('global_sql_keyid_invalid','sids'));
    $where = array();
    $where['sid'] = explode(',',$sids);
    $S =& $_G['loader']->model('item:subject');
    list(,$list) = $S->find('sid,pid,catid,city_id,aid,name,subname',$where,'sid',0,0);
    if($list) {
        while($val = $list->fetch_array()) {
            $result[] = seatch_to_array($val);
        }
        $list->free_result();
        echo search_to_xml($result);
    }
    output();

case 'search':

    $SEARCH =& $_G['loader']->model('item:search');
    $keyword = trim($_POST['keyword']);
    if($_G['charset'] != 'utf-8') {
        $_G['loader']->lib('chinese', NULL, FALSE);
        $CHS = new ms_chinese('utf-8', $_G['charset']);
        $keyword = $keyword ? $CHS->Convert($keyword) : '';
    }
    $_GET['keyword'] = $keyword;
    $_GET['catid'] = is_numeric($_GET['pid']) ? (int)$_GET['pid'] : 0;
    $_GET['ordersort'] = 'addtime';
    $_GET['ordertype'] = 'desc';
    list($total, $list) = $SEARCH->search();
    if(!$total) {
        redirect('item_search_result_empty');
    } else {
        $category = $SEARCH->variable('category');
        $result = array();
        while($val = $list->fetch_array()) {
            $pre = ($val['city_id'] ? template_print('modoer','area',array('aid'=>$val['city_id'])) : '' );
            $name = $val['name'] . ($val['subname']?"($val[subname])":'');
            $cat_name = $category[$val['pid']]['name'];
            $result[] = array (
                'sid' => $val['sid'],
                'pid' => $val['pid'],
                'catid' => $val['catid'],
                'aid' => $val['city_id'],
                'city_id' => $val['city_id'],
                'name' => $name,
                'city_name' => display("modoer:area", "aid/$val[city_id]"),
                'cat_name' => $cat_name,
                'title' => '['.$pre.$cat_name.'] ' . $name,
            );
        }
        echo search_to_xml($result);
        output();
    }
    break;

case 'exists':

	$city_id = _post('city_id','0','intval');
	$pid = _post('name','0','intval');
    $name = _post('name','','trim');
    if($_G['charset'] != 'utf-8') {
        $_G['loader']->lib('chinese', NULL, FALSE);
        $CHS = new ms_chinese('utf-8', $_G['charset']);
        $name = $name ? $CHS->Convert($name) : '';
    }
	if(!$name) redirect(lang('item_search_keyword_empty'));
	$S =& $_G['loader']->model('item:subject');
	$where = array();
	if($city_id) $where['city_id'] = $city_id;
	if($pid) $where['pid'] = $pid;
	$where['name'] = $name;
	list(,$list) = $S->find('sid,name,subname',$where,'sid',0,0);
	if(!$list) {
        redirect('item_search_result_empty');
    } else {
		while($val=$list->fetch_array()) {
			$content .= "\t<option value=\"$val[sid]\">$val[name]".($val['subname']?"($val[subname])":'')."</option>\r\n";
		}
		$list->free_result();
		echo $content;
		output();
	}
	break;

case 'myfavorite':

    $FAV =& $_G['loader']->model('item:favorite');
    $select = 'f.*,s.name,s.subname,s.sid,s.city_id,s.aid,s.pid,s.catid';
    $where = array();
    $where['f.idtype'] = 'subject';
    $where['f.uid'] = $user->uid;
    $start = get_start($_GET['page'], $offset = 50);
    list($total, $list) = $FAV->find($select, $where, $start, $offset);
    if(!$total) {
        redirect('item_search_result_empty');
    } else {
        while($val = $list->fetch_array()) {
            $result[] = seatch_to_array($val);
        }
        $list->free_result();
        echo search_to_xml($result);
        output();
    }
    break;

case 'myreviewed':

    $R =& $_G['loader']->model(':review');
    $start = get_start($_GET['page'], $offset = 50);
    list($total, $list) = $R->myreviewed($user->uid, $start, $offset);
    if(!$total) {
        redirect('item_search_result_empty');
    } else {
        while($val = $list->fetch_array()) {
            $val['sid'] = $val['id'];
            $val['pid'] = $val['pcatid'];
            $val['name'] = $val['subject'];
            $result[] = seatch_to_array($val);
        }
        $list->free_result();
        echo search_to_xml($result);
        output();
    }
    break;

case 'get_membereffect':

    if(!$sid = _input('sid', 0, 'intval')) redirect(lang('global_sql_keyid_invalid', 'sid'));
    if(!$effect = _input('effect','',MF_TEXT)) redirect(lang('member_effect_unkown_effect'));

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid,'pid,name,subname,pid,status',false)) redirect(lang('item_empty'));
    if(!$model = $S->get_model($subject['pid'], TRUE)) redirect('item_model_empty');

    $idtype = $model['tablename'];

    $M =& $_G['loader']->model('member:membereffect');
    $M->add_idtype($idtype, 'subject', 'sid');
    $member = _input('member', NULL);

    if($member=='Y') {
        if($list = $M->get_member($sid, $idtype, $effect)) {
            while($val = $list->fetch_array()) {
                echo '<li><div><a title="'.$val['username'].'" href="'.url("space/index/uid/$val[uid]").'" target="_blank"><img src="'.get_face($val['uid']).'" />'.$val['username'].'</a></div></li>';
            }
        } else {
            redirect('global_empty_info');
        }
    } else {
        $totals = $M->total($sid, $idtype);
        if($totals) {
            foreach($totals as $key => $val) {
                if(substr($key, 0, 6) == 'effect') {
                    echo $split . $val;
                    $split = '|';
                }
            }
        } else {
            echo '0|0';
        }
    }
    output();
    break;

case 'post_membereffect':

    if(!$sid = _post('sid', 0, 'intval')) redirect(lang('global_sql_keyid_invalid', 'sid'));
    if(!isset($_POST['effect'])) redirect(lang('member_effect_unkown_effect'));

    $S =& $_G['loader']->model('item:subject');
    if(!$subject = $S->read($sid,'pid,name,subname,pid,status',false)) redirect(lang('item_empty'));
    if(!$model = $S->get_model($subject['pid'], TRUE)) redirect('item_model_empty');

    $idtype = $model['tablename'];
    $effect = $_POST['effect'];

    $M =& $_G['loader']->model('member:membereffect');
    $M->add_idtype($idtype, 'subject', 'sid');

    $M->save($sid, $idtype, $effect);

    if($totals = $M->total($sid, $idtype)) {
        foreach($totals as $key => $val) {
            if(substr($key, 0, 6) == 'effect') {
                echo $split . $val;
                $split = '|';
            }
        }
    } else {
        echo '0|0';
    }
    output();
    break;

case 'post_map':

    if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));
    $I =& $_G['loader']->model(MOD_FLAG.':subject');
    if(!$item = $I->read($sid)) {
        redirect(lang('item_empty'));
    }

    if($_POST['dosubmit']) {

        if($item['mappoint']) {
            $SL =& $_G['loader']->model(MOD_FLAG.':subjectlog');
            $_POST['ismappoint'] = 1;
            $_POST['upcontent'] = $_POST['p1'] . ',' . $_POST['p2'];
            if(!$user->isLogin) {
                $_POST['username'] = 'guest';
                $_POST['email'] = 'guest@guest.com';
            }
            $SL->save();
        } else {
            $I->mappoint($sid, $_POST['p1'].','.$_POST['p2']);
        }

        redirect('global_op_succeed');

    } else {

        include template('item_ajax_post_map');
        output();

    }

    break;

case 'post_log':

    if(!$sid = (int)$_POST['sid']) redirect(lang('global_sql_keyid_invalid', 'sid'));

    $I =& $_G['loader']->model(MOD_FLAG.':subject');
    if(!$item = $I->read($sid)) {
        redirect(lang('item_empty'));
    }

    if($_POST['dosubmit']) {

        $SL =& $_G['loader']->model(MOD_FLAG.':subjectlog');
        $_POST['ismappoint'] = 0;
        $SL->save();

        redirect('item_log_succeed');

    } else {

        include template('item_ajax_post_log');
        output();
    }

    break;

case 'clear_cookie_subject':

    del_cookie('subject_' . $_POST['pid']);
    break;

case 'get_taoke_detail':

    $sid = _input('sid', null, MF_INT_KEY);
    $S =& $_G['loader']->model('item:subject');
    if(!$detail = $S->read($sid)) redirect('item_subject_empty');
    $category = $S->get_category($detail['catid']);
    if(!$pid = $category['catid']) {
        redirect('item_cat_empty');
    }
    $modelid = $S->category['modelid'];
    $taoke_product_field = $S->get_taoke_product_field($modelid);

    if($detail['templateid']) {
        include template('item_subject_detail_taoke', 'item', $detail['templateid']);
    } else {
        include template('item_subject_detail_taoke');
    }
    output();

case 'get_sub_cats':
    
    $catid = _input('catid',null,MF_INT_KEY);
    $sid = _input('sid',null,MF_INT_KEY);
    $sub_catids = array();
    if($sid) {
        $I =& $_G['loader']->model(MOD_FLAG.':subject');
        $subject = $I->read($sid);
        $subject['sub_catids'] && $sub_catids = explode('|', $subject['sub_catids']);
    }

    $_G['loader']->helper('form','item');
    $content = form_item_category_sub($catid, $sub_catids);
    echo $content;
    output();

    break;

default:

    redirect('global_op_unkown');

}

function seatch_to_array(&$val) {
    $city_name = $val['city_id'] ? display("modoer:area", "aid/$val[city_id]") : '';
    $cat_name =  display("item:category", "catid/$val[pid]");
    $name = $val['name'] . ($val['subname']?"($val[subname])":'');
    return array (
        'sid'   => $val['sid'],
        'pid'   => $val['pid'],
        'catid' => $val['catid'],
        'aid'   => $val['city_id'],
        'city_id' => $val['city_id'],
        'name'  => $name,
        'city_name' => city_name,
        'cat_name'  => $cat_name,
        'title' => '[' . $city_name . $cat_name . '] ' . $name,
    );
}

function search_to_xml($result) {
    if(!$result) return '';
    $content = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
    $content .= "<root>\n";
    foreach($result as $val) {
        $content .= "<subject>\n";
        foreach($val as $k=>$v) {
            $htmlon = preg_match("/<[a-z]+\s+.+\\>/i", $v);
            $content .= "\t<$k>".($htmlon ? '<![CDATA[' : '').$v.($htmlon ? ']]>' : '')."</$k>\n";
        }
        $content .= "</subject>\n";
    }
    $content .= "</root>";
    return $content;
}
?>