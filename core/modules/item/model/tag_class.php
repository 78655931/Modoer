<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_item_tag extends ms_model {
    
    var $table = 'dbpre_tags';
    var $key = 'tagid';
    var $data_table = 'dbpre_tag_data';
    var $model_flag = 'item';

    function __construct() {
        parent::__construct();
    }

    function msm_item_tag() {
        $this->__construct();
    }

    function read($key, $city_id=null, $is_name = FALSE) {
        $this->db->from($this->table);
        if($city_id) $this->db->where('city_id', $city_id);
        $this->db->where($is_name?'tagname':'tagid', $key);
        return $this->db->get_one();
    }

    function find($where=null,$orderby=array('total'=>'DESC'),$start=0,$offset=100,$total = FALSE) {

        $this->db->from($this->table);
        $where && $this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        
		$this->db->select('tagid,tagname,total,closed,dateline');
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    //验证标签，并转换成数组返回
    function check_post($alow_tgids, $post, $isedit = FALSE) {
        if(empty($post)) return;
        if(!$tgids = @array_keys($post)) return;

        //删除未开启的标签组
        foreach($tgids as $tgid) {
            if(!in_array($tgid, $alow_tgids)) {
                unset($post[$tgid]);
            }
        }
        if(empty($post)) return;

        $result = array();
        //取得标签组数据
        $taggroups = $this->variable('taggroup');
        foreach($post as $tgid => $val) {
            if(!$info = $taggroups[$tgid]) continue;
            if(empty($val)) continue;
            $tmp_tags = array();
            //自由填写的标签
            if($info['sort'] == '1') {
                if(!$tags = $this->parse($val)) continue;
                $tags = array_unique($tags);
                foreach($tags as $tagname) {
                    if(!$tagname = trim($tagname)) continue;
                    if(strlen($tagname) > 25) {
                        redirect(lang('item_tag_charlen',array($tagname, 25)));
                    }
                    $tmp_tags[] = $tagname;
                }
            } elseif($info['sort'] == '2') {
                //固定标签则要和进行验证
                if(!$sec_tags = $this->parse($info['options'])) continue;
                if(!is_array($val)) $val = array($val);
                $val = array_unique($val);
                foreach($val as $tagname) {
                    if(!in_array($tagname, $sec_tags)) continue;
                    $tmp_tags[] = $tagname;
                }
            }
            if($tmp_tags) $result[$tgid] = $tmp_tags;
        }
        return $result;
    }

    //验证单独的，返回数组
    function check_post_single($tgid, $post, $isedit = FALSE) {
        $result = array();
        $taggroups = $this->variable('taggroup');
        if(!$info = $taggroups[$tgid]) return $result;
        //自由填写的标签
        if($info['sort'] == '1') {
            if(!$tags = $this->parse($post)) continue;
            $tags = array_unique($tags);
            foreach($tags as $tagname) {
                if(!$tagname = trim($tagname)) continue;
                if(strlen($tagname) > 25) {
                    redirect(lang('item_tag_charlen',array($tagname, 25)));
                }
                $tmp_tags[] = $tagname;
            }
        } elseif($info['sort'] == '2') {
            //固定标签则要和进行验证
            if(!$sec_tags = $this->parse($info['options'])) return $result;
            if(!is_array($post)) $post = array($post);
            $post = array_unique($post);
            foreach($post as $tagname) {
                if(!in_array($tagname, $sec_tags)) continue;
                $tmp_tags[] = $tagname;
            }
        }
        if($tmp_tags) $result = $tmp_tags;
        return $result;
    }

    //对标签进行解析
    function parse($strtag) {
        if(!$strtag) return;
        $modcfg = $this->variable('config');
        $split = ","; // 标签分隔符号
        $match = "/(，|、|\/|\\\|\||——|=|\s+)/is"; // 过滤正则
        if(!$modcfg['tag_split_sp']) $match = str_replace('|\s+','', $match); //是否兼容空格分隔标签
        $str = preg_replace($match, $split, $strtag);
        return explode($split, $str);
    }

    //写入标签
    function add($city_id, $groupid, $id, $tags) {
        if(empty($tags)) return '';
        $result = $this->_add($city_id, $tags);
        $this->_add_data($groupid, $result, $id);
        return $result;
    }

    //写入标签，批量
    function add_batch($city_id, $id, $tags) {
        $result = array();
        if(!$tags || !is_array($tags)) return;
        foreach($tags as $tgid => $val) {
            if(empty($val)) continue;
            $result[$tgid] = $this->_add($city_id, $val);
            $this->_add_data($tgid, $result[$tgid], $id);
        }
        return $result;
    }

    //替换老标签
    function replace($city_id, $groupid, $id, $new_tags, $old_tags) {
        $new = $old = true;
        if(!$new_tags || !is_array($new_tags)) $new = false;
        if(!$old_tags || !is_array($old_tags)) $old = false;
        if(!$new && !$old) return;
        if(!$new && $old) {
            $this->delete($city_id, $id, $old_tags);
            return;
        } elseif($new && !$old) {
            $this->add($city_id, $groupid, $id, $new_tags);
            return;
        }
        //对比新的和旧的是否有存在相同的，如果存在相同则不删除也不添加
        $addtags = $keeps = array();
        foreach($new_tags as $_key => $_val) {
            //新的_key是数组序号下标，旧的则是tagid
            if(!in_array($_val, $old_tags)) {
                $addtags[] = $_val;
            }
        }
        $deltags = array();
        foreach($old_tags as $_key => $_val) {
            //新的_key是数组序号下标，旧的则是tagid
            if(!in_array($_val, $new_tags)) {
                $deltags[] = $_val;
            }
        }
        //如果没有变化，则返回旧数据
        if(empty($deltags) && empty($addtags)) {
            return $old_tags;
        }
        $this->delete($city_id, $groupid, $id, $deltags);
        $result = $this->add($city_id, $groupid, $id, $addtags);
    }

    //替换老标签
    function replace_batch($city_id, $id, $new_tags, $old_tags) {
        $new = $old = true;
        if(!$new_tags || !is_array($new_tags)) {
            $new = false;
        }
        if(!$old_tags || !is_array($old_tags)) {
            $old = false;
        }
        if(!$new && !$old) return '';
        if(!$new && $old) {
            $this->delete($city_id, $id, $old_tags);
            return '';
        }
        if($new && !$old) {
            return $this->add_batch($city_id, $id, $new_tags);
        }
        //对比新的和旧的是否有存在相同的，如果存在相同则不删除也不添加
        $addtags = array();
        foreach($new_tags as $key => $val) {
            if(!isset($old_tags[$key])) {
                $addtags[$key] = $val;
                continue;
            }
            foreach($new_tags[$key] as $_key => $_val) {
                //新的_key是数组序号下标，旧的则是tagid
                if(!in_array($_val, $old_tags[$key])) {
                    $addtags[$key][] = $_val;
                }
            }
        }
        $deltags = array();
        foreach($old_tags as $key => $val) {
            if(!isset($old_tags[$key])) {
                $deltags[$key] = $val;
                continue;
            }
            foreach($old_tags[$key] as $_key => $_val) {
                //新的_key是数组序号下标，旧的则是tagid
                if(!in_array($_val, $new_tags[$key])) {
                    $deltags[$key][$_key] = $_val;
                }
            }
        }
        //如果没有变化，则返回旧数据
        if(empty($deltags) && empty($addtags)) {
            return $old_tags;
        }
        $this->delete_batch($city_id, $id, $old_tags);
        return $this->add_batch($city_id, $id, $new_tags);
    }

    //编辑标签
    function edit($tagname, $tagid, $merge = TRUE) {
        if(!$detail = $this->read($tagid)) {
            redirect('item_tag_empty_tagid');
        }
        if($tagname == $detail['tagname']) return;

        $this->db->from($this->table);
        $this->db->where('tagname', $tagname);
        if(!$src = $this->db->get_one()) {
            $this->db->from($this->table);
            $this->db->set('tagname', $tagname);
            $this->db->where('tagid', $tagid);
            $this->db->update();

            $this->db->from($this->data_table);
            $this->db->set('tagname', $tagname);
            $this->db->where('tagid', $tagid);
            $this->db->update();
            return;
        }
        //合并
        $this->db->from($this->table);
        $this->db->set_add('total', $detail['total']);
        $this->db->where('tagid', $src['tagid']);
        $this->db->update();
        //删除源
        $this->db->sql_roll_back('from');
        $this->db->where('tagid',$tagid);
        $this->db->delete();
        //更新数据
        $del_stids = $up_stids = array();
        $this->db->from($this->data_table);
        $this->db->where('tagid', $tagid);
        if($query = $this->db->get()) {
            //查询需要合并的数据
            while($val = $query->fetch_array()) {
                $this->db->sql_roll_back('from');
                $this->db->where('tagid', $src['tagid']);
                $this->db->where('tgid', $val['tgid']);
                $this->db->where('id', $val['id']);
                //找到合并源
                if($s = $this->db->get_one()) {
                    //合并
                    $this->db->sql_roll_back('from');
                    $this->db->set_add('total', $val['total']);
                    $this->db->where('stid', $s['stid']);
                    $this->db->update();
                    //加入删除集合
                    $del_stids[] = $val['stid'];
                } else {
                    //没有合并源，则加入更新集合
                    $up_stids[] = $val['stid'];
                }
            }
        }
        if($del_stids) {
            $this->db->sql_roll_back('from');
            $this->db->where_in('stid', $del_stids);
            $this->db->delete();
        }
        if($up_stids) {
            $this->db->sql_roll_back('from');
            $this->db->set('tagid', $src['tagid']);
            $this->db->set('tagname', $tagname);
            $this->db->where_in('stid', $up_stids);
            $this->db->update();
        }
    }

    //关闭一些标签
    function close($tagids, $closed) {
        $closed = $closed?1:0;
        if(is_numeric($tagids) && $tagids > 0) $tagids = array($tagids);
        if(!$tagids || !is_array($tagids)) return;

        $this->db->from($this->table);
        $this->db->set('closed', $closed);
        $this->db->where_in('tagid', $tagids);
        $this->db->update();
    }

    //移动标签到新的地区id
    function move_city($sid,$groupid,$new_city_id,$newtags=null) {
        $this->db->from($this->data_table);
        $this->db->where('tgid',$groupid);
        $this->db->where('id', $sid);
        if(!$q = $this->db->get()) return;
        $tagids = $stids = $inserttags = $updatetags = array();
        while($val = $q->fetch_array()) {
            if(!isset($tagids[$val['tagid']])) $tagids[$val['tagid']] = array('tagname'=>$val['tagname'],'total'=>$val['total']);
            if($newtags) {
                if(in_array($val['tagname'], $newtags)) {
                    $updatetags[$val['tagname']] = $val['stid'];
                } else {
                    $stids[] = $val['stid'];
                }
            }
        }
        $q->free_result();
        //删除旧数据
        if($stids) {
            $this->db->from($this->data_table);
            $this->db->where_in('stid', $stids);
            $this->db->delete();
        }
        //删除旧城市数据
        if($tagids) {
            foreach($tagids as $tagid => $val) {
                $this->db->from($this->table);
                $this->db->set_dec('total', $val['total']);
                $this->db->where('tagid', $tagid);
                $this->db->update();
            }
            //删除无效的
            $this->db->from($this->table);
            $this->db->where('total', 0);
            $this->db->where_more('total', '4000000000', 1, 'OR');
            $this->db->delete();
        }
        //增加新城市数据
        if($newtags) {
            if($tags = $this->_add($new_city_id, $newtags)) {
            	if(is_array($updatetags)) foreach ($tags as $tagid => $name) {
		            //更新已存在数据的tagid
		            if($stid = (int)$updatetags[$name]) {
		                $this->db->from($this->data_table);
		                $this->db->set('tagid', $tagid);
		                $this->db->set_dec('total', 1);
		                $this->db->where('stid', $stid);
		                $this->db->update();
		            }
            	}
            	$this->_add_data($groupid, $tags, $sid);
            }
        }
    }

    //删除一组标签
    function delete($city_id, $groupid, $id, $tags) {
        if(empty($tags) || !is_array($tags)) return;
        $this->_delete($city_id, $tags);
        $this->_delete_data($groupid, $tags, $id);
        return '';
    }

    function delete_batch($city_id, $id, $tags) {
        if(empty($tags) || !is_array($tags)) return;
        foreach($tags as $key => $val) {
            $this->_delete($city_id, $val);
            $this->_delete_data($key, $val, $id);
        }
    }

    function delete_ids($ids) {
        if(is_numeric($ids)) $ids = array($ids);
        if(empty($ids) || !is_array($ids)) return;

        $this->db->from($this->data_table);
        $this->db->where_in('id', $ids);
        
        if($query = $this->db->get()) {
            $tagids = $stids = array();
            while($val = $query->fetch_array()) {
                $stids[] = $val['stid'];
                if(!in_array($val['tagid'], $tagids)) $tagids[] = $val['tagid'];
            }
            $query->free_result();
        }

        if($stids) {
            $this->db->from($this->data_table);
            $this->db->where_in('stid', $stids);
            $this->db->delete();

            if($tagids) {
                $this->db->from($this->table);
                $this->db->set_dec('total', 1);
                $this->db->where_in('tagid', $tagids);
                $this->db->update();

                $this->db->sql_roll_back('from');
                $this->db->where('total', 0);
                $this->db->where_more('total', '4000000000', 1, 'OR');
                $this->db->delete();
            }
        }
    }

    function delete_tagids($tagids) {
        if(is_numeric($tagids) && $tagids > 0) $tagids = array($tagids);
        if(!$tagids || !is_array($tagids)) return;

        $this->db->from($this->table);
        $this->db->where_in('tagid', $tagids);
        $this->db->delete();

        $this->db->from($this->data_table);
        $this->db->where_in('tagid', $tagids);
        $this->db->delete();
    }

    //加入tag索引表
    function _add($city_id, $tags) {
        if(!$tags) return;
        $result = array();
        if(!is_array($tags)) return;
        
        $this->db->from($this->table);
        $this->db->where('city_id', $city_id);
        $this->db->where_in('tagname', $tags);

        if($query = $this->db->get()) {
            while($val = $query->fetch_array()) {
                $upids[] = $val['tagid'];
                $result[$val['tagid']] = $val['tagname'];
                foreach($tags as $key => $name) {
                    if($name == $val['tagname']) unset($tags[$key]);
                }
            }

            if($upids) {
                $this->db->from($this->table);
                $this->db->set_add('total', 1);
                $this->db->set_add('dateline', $this->global['timestamp']);
                $this->db->where_in('tagid', $upids);
                $this->db->update();
            }
        }

        if($tags) {
            foreach($tags as $name) {
                $this->db->from($this->table);
                $this->db->set('city_id', $city_id);
                $this->db->set('tagname', $name);
                $this->db->set('total', 1);
                $this->db->set('dateline', $this->global['timestamp']);
                $this->db->insert();
                $id = $this->db->insert_id();
                $result[$id] = $name;
            }
        }

        return $result;
    }

    //加入tag_data数据表
    function _add_data($tgid, $tags, $id) {

        $tagids = array_keys($tags);
        $this->db->from($this->data_table);
        $this->db->where_in('tagid', $tagids);
        $this->db->where('tgid', $tgid);
        $this->db->where('id', $id);

        if($query = $this->db->get()) {
            $stids = array();
            while($val = $query->fetch_array()) {
                $stids[] = $val['stid'];
                unset($tags[$val['tagid']]);
            }

            if($stids) {
                $this->db->from($this->data_table);
                $this->db->set_add('total', 1);
                $this->db->where_in('stid', $stids);
                $this->db->update();
            }
        }

        if($tags) {
            foreach($tags as $key => $val) {
                $this->db->from($this->data_table);
                $this->db->set('id', $id);
                $this->db->set('tagid', $key);
                $this->db->set('tagname', $val);
                $this->db->set('tgid', $tgid);
                $this->db->set('total', 1);
                $this->db->insert();
            }
        }
    }

    //删除tag索引表
    function _delete($city_id, $tags) {
        if(!$tags || !is_array($tags)) return;

        $this->db->from($this->table);
        $this->db->set_dec('total', 1);
        $this->db->where('city_id', $city_id);
        $this->db->where_in('tagname', $tags);
        $this->db->update();

        $this->db->sql_roll_back('from');
        $this->db->where('total', 0);
        $this->db->where_more('total', '4000000000', 1, 'OR');
        $this->db->delete();
    }

    //删除tag_data数据表
    function _delete_data($tgid, $tags, $id) {
        if(!$tags || !is_array($tags)) return;

        $this->db->from($this->data_table);
        $this->db->set_dec('total', 1);
        $this->db->where_in('tagname', $tags);
        $this->db->where('tgid', $tgid);
        $this->db->where('id', $id);
        $this->db->update();

        $this->db->sql_roll_back('from');
        $this->db->where('total', 0);
        $this->db->where_more('total', '4000000000', 1, 'OR');
        $this->db->delete();
    }
}
?>