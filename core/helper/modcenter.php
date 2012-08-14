<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class modcenter {

	function test($url=null) {
		global $_G;
		if($url) $_G['cfg']['forum_url'] = $url;
		if(!$data = modcenter::_get_open_url('test','index')) return false;
		if($data == 'OK') {
			 $MSG = 'admincp_forum_test_secceed';
		} elseif(!$MSG=lang('admincp_forum_' . strtolower($data))) {
			$MSG = 'admincp_forum_test_lost';
		}
		return $MSG;
	}

	function get_forums() {
		$data = modcenter::_get_open_url('forum','list');
		if(!$data) return;
		if(!modcenter::_pares_error($data)) {
			$data = modcenter::_pares_forums($data);
		}
		return $data;
	}

	function get_threads($forumid) {
		$data = modcenter::_get_open_url('thread','list',array('forumid'=>$forumid));
		if(!$data) return;
		if(!modcenter::_pares_error($data)) {
			$data = modcenter::_pares_threads($data);
		}
		return $data;
	}

	function _get_open_url($mod,$act,$params=null) {
		global $_G;
		$forum_type = $_G['cfg']['forum_type'] ? $_G['cfg']['forum_type'] : 'dz';
		$forumurl = $_G['cfg']['forum_url'] . lang('global_' . $forum_type);
		$openurl = $forumurl . '&authkey=' . $_G['cfg']['forum_key'] . '&mod=' . $mod . '&act=' . $act;
		//echo ($openurl); exit;
		if($params) foreach($params as $k=>$v) {
			$openurl .= '&'.rawurlencode($k).'='.rawurlencode($v);
		}
		if(!$openurl) return;
		return @file_get_contents($openurl);
	}

	function _pares_error(&$data) {
		if(substr($data,0,6) != 'ERROR_') return;
	}

	function _pares_forums(& $data) {
		if(!$list = explode("\n", $data)) return;
		$result = array();
		foreach($list as $val) {
			list($fid, $name) = explode("\t", $val);
			if(!$fid || !$name) continue;
			$result[$fid] = $name;
		}
		return $result;
	}

	function _pares_threads(& $data) {
		if(!$list = explode("\n", $data)) return;
		$result = array();
		foreach($list as $val) {
			list($tid,$tmp['fid'],$tmp['subject'],$tmp['views'],$tmp['replies'],$tmp['url']) = explode("\t", $val);
			$result[$tid] = $tmp;
		}
		return $result;
	}

}

?>