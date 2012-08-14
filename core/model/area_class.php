<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_area extends ms_model {

    var $table = 'dbpre_area';
    var $key = 'aid';

    function __construct() {
        parent::__construct();
    }

    function msm_area() {
        $this->__construct();
    }

    function get_list($pid = 0, $order_initial = FALSE) {
        $result = array();
        $this->db->from($this->table);
        $this->db->where('pid', $pid);
        $orderby = $order_initial ? array('initial'=>'ASC','listorder'=>'ASC') : 'listorder';
        $this->db->order_by($orderby);

        if(!$row = $this->db->get()) {
            return $result;
        }
        while($value = $row->fetch_array()) {
            if($order_initial) {
                if(!$value['initial']) $value['initial'] = 'Z';
                $result[$value['initial']][$value['aid']] = $value;
            } else {
                $result[] = $value;
            }
        }
        return $result;
    }

    function read($aid,$select='*') {
        $result = parent::read($aid);
        if($result['level']==1 && $result['config']) {
            $result['config'] = unserialize($result['config']);
        }
        return $result;
    }

    function save($post, $aid = null) {
        $edit = $aid > 0;
        if($edit) {
            if(!$detail = $this->read($aid)) redirect('admincp_area_empty');
            $post['config'] = ($detail['level']!=1) ? '' : serialize($post['config']);
        } else {
            if(isset($post['pid'])) {
                if(!$post['pid']) {
                    $post['level'] = 1;
                } else {
                    if(!$parent = $this->read($post['pid'])) {
                        redirect('admincp_area_empty_pid');
                    } elseif($parent['level'] == '3') {
                        redirect('admincp_area_level_max');
                    }
                    $post['level'] = (int) $parent['level'] + 1;
                }
            }
            $post['config'] = ($post['level']!=1) ? '' : serialize($post['config']);
        }
        $aid = parent::save($post, $detail?$detail:$aid);
        if(!$edit) {
            $attid = $this->loader->model('item:att_list')->save($aid, $post['name'], 'area');
            if($attid) {
                $this->db->from($this->table)->set('attid',$attid)->where('aid',$aid)->update();
            }
        }
        return $aid;
    }

    function check_post(&$post, $edit = false) {
        if(!$post['name']) redirect('admincp_area_empty_name');
        if($post['level'] == 1) {
            if(!$post['mappoint']) redirect('admincp_area_empty_mappoint');
            if($post['domain'] && ($post['domain']=='index'||check_module($post['domain']))) {
                redirect('admincp_area_domain_invalid');
            }
        }
        if($post['mappoint'] && !preg_match("/^[a-z0-9\-\.]+,[a-z0-9\-\.]+$/i", $post['mappoint'])) {
            redirect('admincp_area_error_mappoint');
        }
    }

    function delete($id) {
        if(!$id) return;

        $where = array($id);
        $ids = $this->get_child_all_catids($id);
        if($ids) $where = array_merge($where, $ids);
        //delet att_list
        $this->loader->model('item:att_list')->delete_catid($where,'area');
        //delete
        parent::delete($where);
        /*
        $this->db->from($this->table);
        $this->db->where('aid',$id);
        $detail = $this->db->get_one();
        if($detail['level']=='3') {
            parent::delete(array($id));
        } elseif($detail['level']=='2') {
            $this->db->from($this->table);
            $this->db->where('pid',$id);
            $this->db->where_or('aid',$id);
            $this->db->delete();
        } elseif($detail['level']=='1') {
            $delids = array();
            $this->db->where('pid',$id);
            $this->db->from($this->table);
            if($q=$this->db->get()) {
                while($v=$q->fetch_array()) {
                    $delids[] = $v['aid'];
                }
                $q->free_result();
            }
            //delete level3
            $this->db->from($this->table);
            $this->db->where('pid',$delids);
            $delids[] = $id;
            $this->db->where_or('aid',$delids);
            $this->db->delete();
        }
        */
    }

    // 删除属性
    function delete_att($catid) {
        $where = array($catid);
        $catids = $this->get_child_all_catids($catid);
        if($catids) $where = array_merge($where, $catids);
        $this->loader->model('item:att_list')->delete_catid($where,'area');
    }

    function update($post) {
        if(!is_array($post)) redirect('global_op_unselect');
        foreach($post as $aid => $val) {
            $this->db->from($this->table);
            if(isset($val['initial'])) $val['enabled'] = (int) $val['enabled'];
            $val['listorder'] = (int) $val['listorder'];
            $this->db->set($val);
            $this->db->where('aid',$aid);
            $this->db->update();
        }
        $this->write_cache();
    }

    function get_sub_aids($aid) {
        if(!$rel = $this->variable('area_rel')) return false;
        if(!$rel[$aid]) return false;
        list($pid,$level) = explode(':', $rel[$aid]);
        if($level == 3) return $aid;
        if($level == 2) {
            $city_id = $pid;
        }
        $aids = array($aid);
        foreach($rel as $id => $val) {
            if($val == $aid.':3') {
                $aids[] = $id;
            }
        }
        return $aids;
    }

    function get_parent_aid($aid, $get_level = 1) {
        if(!$rel = $this->variable('area_rel')) return false;
        if(!$rel[$aid]) return false;
        list($pid, $level) = explode(':', $rel[$aid]);
        if($level == $get_level) return $aid;
        if($level-1 == $get_level) return $pid;
        if($get_level < $level-1) {
            list($pid, $level) = explode(':', $rel[$pid]);
        }
        if($level-1 == $get_level) return $pid;
    }

    //取得属性ID
    function get_attid($aid) {
        $pid = $this->get_parent_aid($aid);
        if($pid==$aid) {
            $cats = $this->variable('area');
        } else {
            $cats = $this->variable('area_'.$pid,'',false);
        }
        return (int) $cats[$aid]['attid'];
    }

    //取得属性ID以及父类属性ID
    function get_attids($aid) {
        $attids = array();
        $cats = $this->variable('area');
        $pid = $this->get_parent_aid($aid);
        if($pid==$aid) {
            $attids[] = $cats[$aid]['attid'];
        } else {
            $attids[] = $cats[$pid]['attid'];
            $cats = $this->variable('area_'.$pid,'',false);
            $attids[] = $cats[$aid]['attid'];
            if($cats[$aid]['pid']!=$pid) {
                $ppid = $cats[$aid]['pid'];
                $attids[] = $cats[$ppid]['attid'];
            }
        }
        return $attids;
    }

    function get_child_all_catids($pid) {
        $s = $this->get_child_catids($pid);
        if($s) {
            foreach($s as $id) {
                $ss=$this->get_child_all_catids($id);
                if($ss) $s=array_merge($s,$ss);
            }
        }
        return $s;
    }

    function get_child_catids($pid) {
        $list = $this->db->from($this->table)->where('pid',$pid)->get();
        if(!$list) return null;
        $r = array();
        while($val=$list->fetch_array()) {
            $r[]=$val['aid'];
        }
        $list->free_result();
        return $r;
    }

    function write_cache() {
        $this->_write_cache();
        //js地区文件更新标识
        $C =& $this->loader->model('config');
        $C->save(array('jscache_flag_area'=>rand(1,1000)), 'modoer');
    }

    //导出数据
    function export() {
		$content = $this->_to_xml();
		ob_end_clean();
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: no-cache, must-revalidate');
		header('Pragma: no-cache');
		header('Content-Encoding: none');
		header('Content-Length: '.strlen($content));
        $filename = 'modoer_area_'. date('Y-m-d', $this->global[timestamp]) .'.xml';
		header('Content-Disposition: attachment; filename='.$filename);
		header('Content-Type: '.(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? 'application/octetstream' : 
			'application/octet-stream'));
		echo $content;
		exit();
    }

    //导入数据
    function import() {
        $this->loader->lib('upload_file', NULL, FALSE);
        $UP = new ms_upload_file('area_import_file', 'xml');
        $xmlfile = $UP->get_filename();
        $this->loader->helper('mxml');
        if(!$areas = mxml::to_array($xmlfile)) redirect('admincp_area_importfile_invalid');
        $checkexist = _post('city_exists',null,MF_INT);
        $citys = 0;
        foreach($areas as $city) {
            unset($city['attid']);
            $data_1 = $city['data'];
            unset($city['data']);
            if($checkexist && $this->city_exists($city['name'])) continue;
            $aid_1 = $this->save($city);
            if($data_1) foreach($data_1 as $b) {
                $data_2 = $b['data'];
                $b['pid'] = $aid_1;
                unset($b['data']);
                $aid_2 = $this->save($b);
                if($data_2) foreach($data_2 as $s) {
                    $s['pid'] = $aid_2;
                    $aid_3 = $this->save($s);
                }
            }
            $citys++;
        }
        return $citys;
    }

    //判断是否这个城市
    function city_exists($name) {
        $this->db->from($this->table);
        $this->db->where('name',$name);
        $this->db->where('level',1);
        return $this->db->count()>=1;
    }

    //生成xml，用于导出和导入
    function _to_xml() {
        $this->db->from($this->table);
        $this->db->order_by(array('level'=>'ASC','listorder'=>'ASC'));
        if(!$query = $this->db->get()) return;
        $result = array();
        while($val = $query->fetch_array()) {
            unset($val['attid']);
            $aid = $val['aid'];
            if($val['level']=='1') { //城市
                if($val['config']) $val['config'] = unserialize($val['config']);
                unset($val['templateid'],$val['aid']);
                foreach(explode(',','templateid,aid,config') as $key) unset($val[$key]);
                $result[$aid] = $val;
            } elseif($val['level']=='2') {//区/县
                $pid = $val['pid'];
                $root[$aid] = $pid;
                foreach(explode(',','templateid,aid,pid,domain,initial,config') as $key) unset($val[$key]);
                $result[$pid]['data'][$aid] = $val;
            } elseif($val['level']=='3') {//街道
                $pid = $val['pid'];
                $ppid = $root[$pid];
                foreach(explode(',','templateid,aid,pid,domain,initial,config') as $key) unset($val[$key]);
                $result[$ppid]['data'][$pid]['data'][$aid] = $val;
            }
        }
        $query->free_result();
        $this->loader->helper('mxml');
        $xmlfile = mxml::from_array($result);
        return $xmlfile;
    }

    // 将分类写入缓存
    function _write_cache() {

		$js_data = "";
		$js_levle = array(1=>'',2=>'',3=>'');

        $this->db->from($this->table);
        $this->db->order_by(array('level'=>'ASC','listorder'=>'ASC'));
        if($query = $this->db->get()) {

            $i = 0;
            $result = $file = $level2 = $level3 = $rel = false;

            while($val = $query->fetch_array()) {
				$js_data .= $val['aid'] . ':"' . $val['name'] . '",';
                $rel[$val['aid']] = $val['pid'] . ':' . $val['level'];
                if($val['level']=='1') { //城市
					$js_levle[1][]= $val['aid'];
                    if($val['config']) $val['config'] = unserialize($val['config']);
                    $result[$val['aid']] = $val;
                } elseif($val['level']=='2') {//区/县
					$js_levle[2][$val['pid']][] = $val['aid'];
                    $file[$val['pid']][$val['aid']] = $val;
                } elseif($val['level']=='3') {//街道
					$js_levle[3][$val['pid']][] = $val['aid'];
                    if($file) foreach($file as $pkey => $pval) {
                        if(isset($pval[$val['pid']])) {
                            $file[$pkey][$val['aid']] = $val;
                        }
                    }
                }
            }

			$js_data = 'data:{' . substr($js_data, 0, -1) . '},level:[';
			if($js_levle[1]) {
				$js_data .= '{0:['.implode(',',$js_levle[1]).']},';
			} else {
				$js_data .= '{0:[0]},';
			}
			if($js_levle[2]) {
				$js_data .= '{';
				foreach($js_levle[2] as $k => $v) $js_data .= $k.':[' . implode(',', $v) . '],';
				$js_data = substr($js_data, 0, -1) . '},';
			} else {
				$js_data .= "{0:[0]},";
			}
			if($js_levle[3]) {
				$js_data .= '{';
				foreach($js_levle[3] as $k => $v) $js_data .= $k.':[' . implode(',', $v) . '],';
				$js_data = substr($js_data, 0, -1) . '}';
			} else {
				$js_data .= '{0:[0]}';
			}

			$js_data = 'var _area = {' . $js_data . ']};';

            write_cache('area', arrayeval($result), $this->model_flag);
            write_cache('area_rel', arrayeval($rel), $this->model_flag);
            foreach($file as $key => $val) {
                write_cache('area_' . $key, arrayeval($val), $this->model_flag);
            }
        }

        write_cache('area', $js_data, $this->model_flag, 'js');
    }
}
?>
