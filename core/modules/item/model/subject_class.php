<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('item:itembase', FALSE);
class msm_item_subject extends msm_item_itembase {

    var $table = 'dbpre_subject';
    var $key = 'sid';
    
    var $field = null;
    var $category = '';
    var $model = '';
    var $modcfg = '';

    var $style = null;

    function __construct() {
        parent::__construct();
        $this->field =& $this->loader->model('item:field');
        !$this->modcfg && $this->modcfg = $this->variable('config','item');
    }

    function msm_item_subject() {
        $this->__construct();
    }

    function init_field() {
        //����Ϊ�ں�̨�����Ĭ���ֶ�
        parent::add_field('domain,aid,pid,catid,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,avgprice,best,reviews,
		guestbooks,pictures,pageviews,level,finer,owner,cuid,creator,addtime,video,thumb,status,mappoint,description');
        parent::add_field_fun('aid,avgprice,best,reviews,guestbooks,pictures,pageviews,level,finer,cuid,addtime,status','intval');
        parent::add_field_fun('sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,avgsort','floatval');
        parent::add_field_fun('domain,thumb','_T');
    }

    // ȡ��������Ϣ
    function read($key, $select = '*', $read_field = TRUE, $is_domain = FALSE) {
        if(!$key) {
            redirect(lang('global_sql_keyid_invalid', 'sid'));
        }
        $result = '';
        $this->db->from($this->table);
        if($is_domain) {
            $this->db->where('domain', $key);
        } else {
            $this->db->where('sid', $key);
        }
        $this->db->select($select);
        if($read_field && $select != '*' && !strposex($read_field,'catid')) {
            $this->db->select('catid');
        }
        if(!$result = $this->db->get_one()) {
            return $result;
        }
        $sid = $result['sid'];
        //�����ֶδ���
        if(!num_empty($result['map_lng']) && !num_empty($result['map_lat'])) {
            $result['mappoint'] = $result['map_lng'] . ',' . $result['map_lat'];
        } else {
            $result['mappoint'] = '';
        }
        $result_field = array();
        if($read_field) {
            $result_field = $this->read_field($sid, $result['catid']);
        }
        if($result_field) {
            $result = array_merge($result, $result_field);
        }
        return $result;
    }

    function read_random($where=null) {
        $this->db->from($this->table);
        if($where) $this->db->where($where);
        $this->db->where('status',1);
        $this->db->order_by('rand()');
        $this->db->limit(0,1);
        return $this->db->get_one();
    }

    // ȡ�ø�������
    function read_field($sid, $catid, $select = '*') {
        $this->get_category($catid);
        $this->get_model($this->category['modelid']);
        $table = 'dbpre_' . $this->model['tablename'];
        $this->db->from($table);
        $this->db->where('sid', $sid);
        $this->db->select($select);
        $result = $this->db->get_one();
        return $result;
    }

    function getcount($where) {
        /*
        $this->db->join('dbpre_subjectatt', 'st.sid', $this->table, 's.sid', 'LEFT JOIN');
        $this->db->where($where);
        isset($keyword) && $this->db->where_concat_like(array('name','subname'), '%'.$keyword.'%');
        if(count($where['attid'])>1) {
            $this->db->group_by('st.sid');
            $this->db->having('COUNT(st.sid)='.count($where['attid']));
            $this->db->order_by('NULL');
        }
        return $this->db->count();
        */

        $atts = $where['attid'];
        unset($where['attid']);
        $this->db->from($this->table,'s');
        if($atts) {
            $attlist = array_values($atts);
            $num = count($attlist);
            foreach($attlist as $attid) {
                 $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid=$attid");
            }
        }
        $this->db->where($where);
        return $this->db->count();

    }

    // �б�ҳ��ѯ
    function getlist($pid, $select, $where, $orderby, $start, $offset, $total = TRUE) {
        $result = array(0,'');
        if($total) {
            $result[0] = $this->getcount($where);
        }
        $atts = $where['attid'];
        unset($where['attid']);
        
        $this->get_category($pid);
        $this->get_model($this->category['modelid']);
        $table = 'dbpre_' . $this->model['tablename'];

        $this->db->join($this->table, 's.sid', $table, 'sf.sid', 'LEFT JOIN');
        /*
        $this->db->join('dbpre_subjectatt', 'st.sid', $this->table, 's.sid', 'LEFT JOIN');
        $this->db->join_together('s.sid', $table, 'sf.sid', 'LEFT JOIN');
        if(count($where['attid'])>1) {
            $this->db->group_by('st.sid');
            $this->db->having('COUNT(st.sid)='.count($where['attid']));
        }
        */
        if($atts) {
            $attlist = array_values($atts);
            $num = count($attlist);
            foreach($attlist as $attid) {
                 $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid=$attid");
            }
        }
        $this->db->where($where);
        
        $this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        if($offset > 0) $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    // ɸѡ����
    function find($select, $where, $orderby, $start, $offset, $total = TRUE, $field_catid = NULL, $atts = NULL) {

        if($field_catid) {
            $this->get_category($field_catid);
            $this->get_model($this->category['modelid']);
            $table = 'dbpre_' . $this->model['tablename'];
            $this->db->join($this->table, 's.sid', $table, 'sf.sid', 'JOIN');
        } else {
            $this->db->from($this->table, 's');
        }

        if($atts) {
            $attlist = array_values($atts);
            $num = count($attlist);
            if($num>1) {
                $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid IN (".implode(',',$attlist).") GROUP BY st.sid HAVING COUNT(st.sid)=$num");
            } else {
                $this->db->where_exist("SELECT 1 FROM dbpre_subjectatt st WHERE s.sid=st.sid AND attid=".array_pop($attlist));
            }
        }

        if($where['aid']) {
            $AA =& $this->loader->model('area');
            $CITY_ID = $AA->get_parent_aid($where['aid']);
            $area = $this->loader->variable('area_'.$CITY_ID);
            if(!isset($area[$where['aid']])) {
                unset($where['aid']);
            } else {
                $area_slg = $area[$where['aid']];
                $adis = array((int)$where['aid']);
                //�����ǵڶ���ʱ����Ҫ�����һ�����Ҳ����
                if($area_slg['level'] == 2) {
                    foreach($area as $key => $val) {
                        if($val['pid'] == $where['aid']) $adis[] = $key;
                    }
                    $where['aid'] = $adis;
                }
                unset($area_slg, $adis);
            }
        }
        if($where['fn']) {
            $keyword = $where['fn'];
            unset($where['fn']);
        }
        $this->db->where($where);
        isset($keyword) && $this->db->where_concat_like(array('name','subname'), '%'.$keyword.'%');

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

        $this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        if($offset > 0) $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    // ������ǩ
    function find_tag($tagid, $where, $select, $orderby, $start, $offset, $total = TRUE) {
        $this->db->join('dbpre_tag_data', 'td.id', $this->table, 's.sid', 'LEFT JOIN');
        $this->db->where('tagid', $tagid);
        $this->db->where($where);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }

        $this->db->select($select ? $select : '*');
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    // ����ͼƬ
    function find_picture($where, $orderby, $start, $offset, $total = TRUE) {
        $this->db->from($this->table);
        if($where['aid']) {
            $AA =& $this->loader->model('area');
            $CITY_ID = $AA->get_parent_aid($where['aid']);
            $area = $this->loader->variable('area_'.$CITY_ID);
            if(!isset($area[$where['aid']])) {
                unset($where['aid']);
            } else {
                $area_slg = $area[$where['aid']];
                $adis = array((int)$where['aid']);
                if($area_slg['level'] == 2) {
                    foreach($area as $key => $val) {
                        if($val['pid'] == $where['aid']) {
                            $adis[] = $key;
                        }
                    }
                    $where['aid'] = $adis;
                }
                unset($area_slg, $adis);
            }
        }

        if($where['fn']) {
            $keyword = $where['fn'];
            unset($where['fn']);
        }
        $this->db->where($where);
        $this->db->where_more('pictures', 1);

        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
        $this->db->select("sid,pid,catid,name,subname,pictures,thumb");
        $this->db->order_by($orderby);
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();
        return $result;
    }

    // ��������
    function save($post, $sid = null) {

        $main_fields = array('sid','domain','city_id','aid','pid','catid','sub_catids','minor_catids','name','subname','avgsort',
		'sort1','sort2','sort3','sort4','sort5','sort6','sort7','sort8','avgprice','best','reviews',
		'guestbooks','pictures','pageviews','level','finer','owner','cuid','creator','addtime','video',
		'thumb','status','map_lng','map_lat','description');

        if($edit = $sid > 0) {
            if(!$detail = $this->read($sid)) {
                redirect('global_op_empty');
            }
            if(!$this->in_admin) {
                $cfg = $this->get_category($detail['pid']);
                $access_edit = $cfg['config']['allow_edit_subject'] && $this->global['user']->check_access('item_allow_edit_subject', $this, false);
                if(!$access_edit && $this->global['user']->username != $detail['owner']) {
                    redirect('global_op_access');
                }
            }
            define('EDIT_SID', $sid);
        }

        if(!isset($post['thumb'])) $post['thumb'] = '';
        if(!$catid = $post['catid']) $catid = $post['catid'] = $_POST['pid'];
        if(!$catid) redirect('item_cat_sub_empty');
        $this->get_category($catid);

        $this->get_model($this->category['modelid']);
        $table = 'dbpre_' . $this->model['tablename'];

        // �ֶμ��
        $modelid = $this->category['modelid'];
        $data = $this->field->validator($modelid, $post, $sid);
        $data['pid'] = $this->category['catid'];
        //�̶��ֶΣ����Զ����ڣ�
        if(isset($post['domain'])) $data['domain'] = $post['domain'];
        if(isset($post['owner'])) $data['owner'] = $post['owner'];
        if(isset($post['thumb'])) $data['thumb'] = _T($post['thumb']);
        if(isset($post['templateid'])) {
            $data['templateid'] = (int)$post['templateid'];
            if($edit && !$this->in_admin) $post['templateid'] = $detail['templateid'];
        }
        if(isset($post['status'])) {
            $data['status'] = (int)$post['status'];
            if($edit && !$this->in_admin) $post['status'] = $detail['status'];
        }

        //�����ֶ�
        if($data['aid']) {
            $AREA =& $this->loader->model('area');
            $data['city_id'] = $post['city_id'] = (int) $AREA->get_parent_aid($data['aid'],1);
        }
        //���
        $this->check_post($data, $edit, $sid);

        if(!$edit) {
            !$this->in_admin && $this->global['user']->check_access('item_subjects', $this);
            // ����Ǽ�/������
            if($post['cuid'] && isset($post['creator'])) {
                $data['cuid'] = $post['cuid'];
                $data['creator'] = _T($post['creator']);
            }
            $data['addtime'] = $this->global['timestamp'];
        }

        if(!$edit || $data['name'] . $data['subname'] != $detail['name'] . $detail['subname']) {
            if($this->exists($data['city_id'], $data['name'], $data['subname'], $sid)) {
                redirect(lang('item_post_exists_item', $this->model['item_name']));
            }
        }

        // ��ֹǰ̨�޸�״̬
        if($edit && !$this->in_admin) {
            unset($data['status']);
        } elseif(!$edit && !$this->in_admin) {
            $data['status'] = $this->category['config']['itemcheck'] ? 0 : 1;
            $data['domain'] = '';
        }

        $field_post = $main_post = array();
        foreach($data as $keyname => $val) {
            if(in_array($keyname, $main_fields)) {
				if(isset($data[$keyname])) {
					$main_post[$keyname] = $data[$keyname];
				}
            } else {
                $field_post[$keyname] = $data[$keyname];
            }
        }

        /*
        //��ѯ����Ա��Ϣ
        if($main_post['owner'] && $detail['owner'] != $main_post['owner']) {
            $this->db->select('uid,username');
            $this->db->from('dbpre_members');
            $this->db->where('username', $data['owner']);
            if(!$member = $this->db->get_one()) redirect('item_post_owner_invalid');
        }
        */

        //ȥ�������ֶ�
        $v_f = array('mappoint');
        foreach($v_f as $v) {
            if(isset($field_post[$v])) unset($field_post[$v]);
        }
		//�����ֶ�(����)
		$i_f = array('sub_catids','minor_catids');
        foreach($i_f as $v) {
            if(isset($post[$v])) $main_post[$v] = $post[$v];
        }
        //����
		$i_f = array('forumid');
        foreach($i_f as $v) {
            if(isset($post[$v])) $field_post[$v] = $post[$v];
        }

        //����sub_catids
        $catids = array();
        foreach(array('sub_catids','minor_catids') as $_keyid) {
            if($main_post[$_keyid]) {
                $_catids = $main_post[$_keyid];
                $main_post[$_keyid] = '';
                $catids = array_merge($catids, $_catids);
                foreach($_catids as $_catid) {
                    $main_post[$_keyid] .= '|'.$_catid;
                }
                $main_post[$_keyid] && $main_post[$_keyid] = $main_post[$_keyid] . '|';
                unset($_catids);
            }
        }
        if(!$catids) $catids = array($main_post['catid']);

        //����ģ��
        if($field_post['templateid']>0 && $edit && $sid>0) {
             $ST =& $this->loader->model('item:subjectstyle');
             $tpl = $ST->get_exists(array('sid'=>$sid,'templateid'=>$field_post['templateid']));
             if(empty($tpl)) $field_post['templateid'] = 0; //�����ڹ����¼��������Ϊ 0
        }

        if($edit) {
            //����
            foreach($main_post as $key => $val) {
                if($val == $detail[$key]) unset($main_post[$key]);
            }
            if($main_post) {
                $this->db->from($this->table);
                $this->db->set($main_post);
                $this->db->where('sid', $sid);
                $this->db->update();
            }
            //����
            foreach($field_post as $key => $val) {
                if($val == $detail[$key]) unset($field_post[$key]);
            }
            if($field_post) {
                $this->db->from($table);
                $this->db->set($field_post);
                $this->db->where('sid', $sid);
                $this->db->update();
            }
        } else {
            //����
            $this->db->from($this->table);
            $this->db->set($main_post);
            $this->db->insert();
            $sid = $this->db->insert_id();
            //����
            $this->db->from($table);
            $this->db->set('sid', $sid);
            $this->db->set($field_post);
            $this->db->insert();
        }

        if(!$edit) {
            define('RETURN_EVENT_ID', $main_post['status'] ? 'global_op_succeed' : 'global_op_succeed_check');
        } elseif($edit) {
            if($main_post['status'] || $detail['status']) define('RETURN_EVENT_ID', 'global_op_succeed');
            if(!$main_post['status'] || !$detail['status']) define('RETURN_EVENT_ID', 'global_op_succeed_check');
        }

        //���ù���Ա
        if(!$edit && isset($main_post['owner'])) {
            $this->set_owner($sid, $main_post['owner'], '', false, false);
        }
        //����ģ��
        if(!$edit && $field_post['templateid']>0) {
            $ST =& $this->loader->model('item:subjectstyle');
            $ST->_addnew(0,$sid,$field_post['templateid'],$this->timestamp+30*24*3600,true);//�ṩһ����
        }

        //�ϴ���ͼƬ
        if(!empty($_FILES['picture']['name'])) {
            $P =& $this->loader->model('item:picture');
            $P->save(array('sid'=>$sid), TRUE, TRUE);
        }

        define('ITEM_PID', $data['pid']);
        $status = 0; //�Ƿ����
        $catid = $data['catid'];
        //���·���ͳ��
        if(!$edit && $data['status'] == 1) {
            $this->category_num_add($catid, 1); //���벻��Ҫ���+1
            $this->add_user_point($post['cuid']); //��Ա����
            if($post['cuid']>0) {
                //$this->activity($post['cuid'], $post['creator']); //��Ա��Ծ��
                $this->_feed($sid);
            }
            $status = 1;
        } elseif($edit) {
            if(isset($data['catid']) && ($detail['catid'] != $data['catid'])) { //�Ƿ�����˷���
                if($detail['status'] == 1) $this->category_num_dec($detail['catid']); //ԭ����ͨ����˵�����-1
                if($data['status'] == 1) {
                    $this->category_num_add($data['catid']); //�·�������+1
                    $status = 1;
                }
            } else {
                if($detail['status'] != 1 && $data['status'] == 1) {
                    $this->category_num_add($data['catid']); //ͨ�����+1
                    if($post['cuid'] > 0) {
                        $this->add_user_point($post['cuid']);
                        $this->_feed($sid);
                    }
                    $status = 1;
                    //$this->activity($post['cuid'], $detail['creator']);
                } elseif($detail['status'] == 1 && isset($data['status']) && $data['status'] != 1) {
                    $this->category_num_dec($detail['catid']); //�������״̬-1
                } else {
                    if($detail['status']) $status = 1;
                }
            }
        }

        if(!isset($detail)) $detail = array();
        //if(isset($data['status'])) $field_post['status'] = $data['status'];
        $data['status'] = $status;
        //�������att
        $this->save_att_category($sid,$catids, $status);
        //�������att
        $this->save_att_area($sid, $data['aid'], $status);
        //������Ҫ���ڴ���
        $this->save_atts($table, $sid, $modelid, $data, $detail, $edit);
        //���������Ҫ���ڴ���
        $this->save_subjects($sid, $modelid, $edit);
        //��ǩ��Ҫ���ڴ���
        $this->save_tags($table, $sid, $modelid, $data, $detail, $edit);
        //�����˳���/���������������id
        if($edit && isset($main_post['city_id']) && $detail['city_id'] != $main_post['city_id']) {
            $A =& $this->loader->model('item:album');
            $A->change_city($sid,$main_post['city_id']);
        }
        return $sid;
    }

    // �������⸽��
    function save_field(&$post, $sid = null) {
    }

    // ���������ǩ
    function save_tags($table, $sid, $modelid, &$post, &$detail, $edit = false) {
        $city_id = (int) $post['city_id'];
        $fields = $this->variable('field_' . $modelid);
        $savedata = array();
        $TAG =& $this->loader->model('item:tag');
        foreach($fields as $val) {
            if($val['type'] != 'tag') continue;
            if(!isset($post[$val['fieldname']])) continue; //������棬���ʾ���ݺ;͵���ͬ���Ѿ���ע��
            if($newtags = $post[$val['fieldname']]) is_string($newtags) && $newtags = unserialize($newtags);
            if(!$groupid = $val['config']['groupid']) continue;
            if(!$edit) {
                if($post['status'] == 1) if($newtags) $TAG->add($city_id, $groupid, $sid, $newtags); //�½�
            } else {
                if($city_id != (int) $detail['city_id']) { //ת�����ڳ���
                    //ɾ���ɳ������ݣ������³�������
                    $TAG->move_city($sid,$groupid,$city_id,$newtags);
                    continue;
                }
                if($oldtags = $detail[$val['fieldname']]) is_string($oldtags) && $oldtags = unserialize($oldtags);
                if($detail['status'] != 1 && $post['status'] == 1) {
                    if($newtags) $TAG->add($city_id, $groupid, $sid, $newtags); //�½�
                } elseif($detail['status'] == 1 && $post['status'] == 1) {
                    if($oldtags && $newtags) $TAG->replace($city_id, $groupid, $sid, $newtags, $oldtags); //ɾ�����滻�����
                    if(!$oldtags && $newtags) $TAG->add($city_id, $groupid, $sid, $newtags); //�½�
                    if($oldtags && !$newtags) $TAG->delete($city_id, $groupid, $sid, $oldtags); //ɾ��
                } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                    if($oldtags)  $TAG->delete($city_id, $groupid, $sid, $oldtags); //ɾ��
                }
            }
        }
    }

    // ������������
    function save_atts($table, $sid, $modelid, &$post, &$detail, $edit = false) {
        $city_id = (int) $post['city_id'];
        $fields = $this->variable('field_' . $modelid);
        $savedata = array();
        $AD =& $this->loader->model('item:att_data');
        foreach($fields as $val) {
            if($val['type'] != 'att') continue;
            if(!isset($post[$val['fieldname']])) continue; //������棬���ʾ���ݺ;͵���ͬ���Ѿ���ע��
            $newatts = $post[$val['fieldname']];
            if(!$catid = $val['config']['catid']) continue;
            //ɾ���ɵ�
            $AD->delete_sid_catid($sid,$catid,'att');
            if(!$newatts) continue;
            if(!$edit) {
                if($post['status'] == 1 && $newatts) $AD->add($catid, $sid, $newatts); //�½�
            } else {
                $oldatts = $detail[$val['fieldname']];
                if($detail['status'] != 1 && $post['status'] == 1) {
                    if($newatts) $AD->add($catid, $sid, $newatts); //�½�
                } elseif($detail['status'] == 1 && ($post['status'] == 1||!isset($post['status']))) {
                    if($newatts) $AD->add($catid, $sid, $newatts); //�½�
                    //if($oldatts && $newatts) $AD->replace($catid, $sid, $newatts, $oldatts); //ɾ�����滻�����
                    //if(!$oldatts && $newatts) $AD->add($catid, $sid, $newatts); //�½�
                    //if($oldatts && !$newatts) $AD->delete($catid, $sid, $oldatts); //ɾ��
                } elseif($detail['status'] == 1 && isset($post['status']) && $post['status'] != 1) {
                    //if($oldatts)  $AD->delete($catid, $sid, $oldatts); //ɾ��
                }
            }
        }
    }

    // �����������
    function save_att_category($sid, $catids, $status=1) {
        $AD =& $this->loader->model('item:att_data');
        if(!$catids || !$status) {
            $AD->delete_sid($sid, 'category');
            return;
        }
        $atts = $AD->get_list($sid, 'category');
        if($atts) $atts = array_keys($atts);
        $C =& $this->loader->model('item:category');
        $attids = array();
        foreach($catids as $catid) {
            $_attids = $C->get_attids($catid);
            if($_attids) $attids = array_merge($attids, $_attids);
        }
        $attids = array_unique($attids);

        if(!$atts&&!$attids) return;
        if(!$atts&&$attids) {
            $AD->save($sid, $attids, 'category');
            return;
        } elseif($atts&&!$attids) {
            $AD->delete($sid, $atts, 'category');
            return;
        }
        
        $del = $add = array();
        foreach($attids as $id) {
            if(!in_array($id, $atts)) $add[] = $id;
        }
        foreach($atts as $id) {
            if(!in_array($id, $attids)) $del[] = $id;
        }
        if($del) $AD->delete($sid, $del,'category');
        if($add) $AD->save($sid, $add, 'category');
    }

    // �����������
    function save_att_area($sid, $aid, $status=1) {
        $AD =& $this->loader->model('item:att_data');
        if(!$aid || !$status) {
            $AD->delete_sid($sid,'area');
            return;
        }
        $atts = $AD->get_list($sid, 'area');
        if($atts) $atts = array_keys($atts);
        $A =& $this->loader->model('area');
        $attids = $A->get_attids($aid);

        if(!$atts&&!$attids) return;
        if(!$atts&&$attids) {
            $AD->save($sid, $attids, 'area');
            return;
        } elseif($atts&&!$attids) {
            $AD->delete($sid, $atts, 'area');
            return;
        }
        
        $del = $add = array();
        foreach($attids as $id) {
            if(!in_array($id, $atts)) $add[] = $id;
        }
        foreach($atts as $id) {
            if(!in_array($id, $attids)) $del[] = $id;
        }
        if($del) $AD->delete($sid, $del, 'area');
        if($add) $AD->save($sid, $add, 'area');
    }

    // ���������������
    function save_subjects($sid, $modelid, $edit = false) {
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if($val['type'] != 'subject') continue;
            $definname = 'ITEM_'.strtoupper($val['fieldname']).'_SUBJECTS';
            $value =& $this->global['define'][$definname];
            if($edit && !$value) $this->subject_delete_related($sid,$val['modelid'],$val['fieldid']);//ɾ�����й���
            if($value) $this->subject_update_related($sid,$value,$val['modelid'],$val['fieldid']);//���¹�����
            unset($this->global['define'][$definname]);
        }
    }

    //�����������������
    function subject_update_related($sid,$subjects,$modelid,$fieldid) {
        $this->db->from('dbpre_subjectrelated');
        $this->db->where('fieldid',$fieldid);
        $this->db->where('sid',$sid);
        $addlist = $dellist = array();
        if($q=$this->db->get()) {
            while($v=$q->fetch_array()) {
                if(!array_key_exists($v['r_sid'], $subjects)) {
                    //��������ȴ�ɾ��
                    $dellist[] = $v['related_id'];
                } else {
                    //���ڣ����׼����ӵ�ɾ��
                    unset($subjects[$v['r_sid']]);
                   
                }
            }
        }
        if($subjects) $addlist = array_keys($subjects);
        if($dellist) {
            $this->db->from('dbpre_subjectrelated');
            $this->db->where('related_id',$dellist);
            $this->db->delete();
        }
        if($addlist) {
            foreach($addlist as $_sid) {
                $this->db->from('dbpre_subjectrelated');
                $this->db->set('fieldid',$fieldid);
                $this->db->set('modelid',$modelid);
                $this->db->set('sid',$sid);
                $this->db->set('r_sid',$_sid);
                $this->db->insert();
            }
        }
    }

    //ɾ���������������
    function subject_delete_related($sid,$modelid,$fieldid) {
        $this->db->from('dbpre_subjectrelated');
        $this->db->where('fieldid',$fieldid);
        $this->db->where('sid',$sid);
        $this->db->delete();
    }

    //���б����ύ����,ֻ�漰�����ֶ�
    function update($post) {
        if(!is_array($post) || !$post) redirect('global_op_nothing');
        foreach($post as $sid => $val) {
            $this->db->from($this->table);
            $this->db->set($val);
            $this->db->where('sid',$sid);
            $this->db->update();
        }
    }

    //�����ƶ�����
    function move($sids, $catid) {
        $sids = parent::get_keyids($sids);
        if(!$catid) redirect('item_subject_move_catid_empty');

		$C =& $this->loader->model('item:category');
		$root_pid = $C->get_parent_id($catid);
		$category = $this->variable('category_'.$root_pid);

        if(!$mtcat = $category[$catid]) redirect('itemcp_cat_empty');
        if($mtcat['pid']==0) redirect('item_subject_move_catid_isroot');
        $pid = $mtcat['pid'];
        $this->db->from($this->table);
        $this->db->where_in('sid', $sids);
        $this->db->select('sid,pid,catid,status,sub_catids,minor_catids');
        if(!$q=$this->db->get()) return;
        $dec_cats = $moveids = array();
        $add_num = 0;
        $attids = array();
        while($v = $q->fetch()) {
            if($v['catid'] == $catid) continue;
            $moveids[] = $v['sid'];
            if($v['status'] = 1) {
                if(isset($dec_cats[$v['catid']])) {
                    $dec_cats[$v['catid']]++;
                } else {
                    $dec_cats[$v['catid']]=1;
                }
                $add_num++;
            }
            $attids[$v['sid']][] = $catid;
            if($pid == $val['pid'] && $val['minor_catids']) {
                $minor = explode('|', $val['minor_catids']);
                foreach($minor as $_k => $_v) {
                    if(!$_v) unset($minor[$_k]);
                }
                $attids[$v['sid']] = array_merge($attids[$v['sid']], $minor);
            }
        }
        $q->free();
        //��������
        if($moveids) {
            $this->db->from($this->table);
            $this->db->where_in('sid', $moveids);
            $this->db->set('pid',$pid);
            $this->db->set('catid',$catid);
            $this->db->set('sub_catids','');
            $this->db->update();
            //���µ�����
            $R =& $this->loader->model(':review');
            $R->update_category('item_subject', $moveids, $pid);
        }
        //����att
        foreach($attids as $_sid => $_catids) {
            $this->save_att_category($_sid, $_catids);
        }
        //���·���ͳ��
        if($add_num > 0) {
            $this->category_num_add($catid, $add_num);
        }
        foreach($dec_cats as $catid => $num) {
            $this->category_num_dec($catid,$num);
        }
    }

    // ɾ������
    function delete($ids, $delete_point = FALSE) {
        $ids = $this->get_keyids($ids);
        $where = array('sid'=>$ids);
        $this->_delete($where);
    }

    // ɾ��ĳ�����������
    function delete_catid($catid) {
        if(!is_numeric($catid) || !$catid) return;
        $where = array();
        /*
        if($this->is_root_category($catid)) {
            //$where['pid'] = $catid;
            if($catids = $this->get_sub_catids($catid)) {
                $catids[] = $catid;
            } else {
                $catids = $catid;
            }
            $where['catid'] = $catids;
        } else {
            $where['catid'] = $catid;
        }
        if(!$where['catid']) return;
        */
        $catids = $this->loader->model('item:category')->get_child_all_catids($catid);
        if($catids) {
            $where['catid'] = array_merge($catid, $catids);
        } else {
            $where['catid'] = $catid;
        }
        $this->_delete($where, FALSE, FALSE);
    }

    // �������
    function checkup($sids) {
        if(is_numeric($sids) && $sids > 0) $sids = array($sids);
        if(!is_array($sids)||count($sids)==0) redirect('global_op_unselect');
        $this->db->from($this->table);
        $this->db->where_in('sid', $sids);
        $this->db->where_not_equal('status', 1);
        $this->db->select('sid,aid,status,cuid,pid,catid,sub_catids,minor_catids');
        if(!$row = $this->db->get()) return;
        $upids = $pids = $atts = array();
        while ($value = $row->fetch_array()) {
            $upids[] = $value['sid'];
            $this->category_num_add($value['catid']);
            if($value['cuid'] > 0) {
                //$this->activity($value['cuid'], $value['creator']); //��Ծ��
                $this->add_user_point($value['cuid']); //��Ա����
                $this->_feed($value['sid']); //feed
            }
            $pids[$value['pid']][] = $value['sid'];
            //������
            $fielddata = $this->read_field($value['sid'], $value['catid']);
            $fielddata = array_merge($fielddata,$value);
            $fielddata['status'] = 1;
            $fielddata['modelid'] = $this->model['modelid'];
            $fielddata['tablename'] = 'dbpre_' . $this->model['tablename'];
            $atts[] = $fielddata;
            //����/��������
            $cat_attids = $area_attids = array();
            $area_attids[$value['sid']] = $value['aid'];
            foreach(array('sub_catids','minor_catids') as $_key) {
                $minor = explode('|', $value[$_key]);
                foreach($minor as $_k => $_v) {
                    if(!$_v) unset($minor[$_k]);
                }
                $minor[] = $value['catid'];
                $minor = array_unique($minor);
                $cat_attids[$value['sid']] = $minor;
            }
        }
        $row->free_result();
        if($upids) {
            $this->db->from($this->table);
            $this->db->set('status', 1);
            $this->db->where_in('sid', $upids);
            $this->db->update();
            //��ǩ������Ϊ�ڸ�������Ҫͨ��������id����ȡģ�ͱ�������ѯ����
            $this->checkup_tags($pids);
            //���������
            $detail = array();
            if($atts) foreach($atts as $val) {
                $this->save_atts($val['tablename'], $val['sid'], $val['modelid'], $val, $detail, false);
            }
            if($cat_attids) foreach($cat_attids as $_sid => $_catids) {
                $this->save_att_category($_sid, $_catids);
            }
            if($area_attids) foreach($area_attids as $_sid => $_aid) {
                $this->save_att_area($_sid, $_aid);
            }
        }
    }

    //��˸��±�ǩ
    function checkup_tags($data) {
        if(!$data) return false;
        $fields = $usetag = array();
        $TAG =& $this->loader->model('item:tag');
        //����data�Ľṹ�� [pid]=>array([sid],[sid])
        foreach($data as $pid => $sids) {
            if(!$pid) continue;
            $model = $this->get_model($pid, TRUE); //ȡ�������ģ��
            $modelid = $model['modelid'];
            if(!isset($fields[$modelid])) $fields[$modelid] = $this->variable('field_'.$modelid); //ȡģ���ֶ�
            if(!$fields[$modelid]) continue;
            foreach($fields[$modelid] as $k=>$val) {
                if($val['type'] == 'tag') $usetag[$modelid][$k] = $val; //ȡ��ǩ�ֶ�
            }
            //�ж��Ƿ�ʹ�ñ�ǩ
            if(!isset($usetag[$modelid]) || count($usetag[$modelid])==0) continue;
            $select = 's.sid,s.city_id'; 
            $selects = array(); 
            foreach($usetag[$modelid] as $val) {
                $select .= ','.$val['fieldname']; //��ȡ���ݵ��ֶ��б���sid��������ǩ�ֶ����
                $selects[$val['fieldname']] = $val; //�����Ա�ǩ�ֶ�����Ϊ��������������
            }
            $table = 'dbpre_' . $model['tablename']; //��ѯ�����⸽��
            //��Ϊ��Ҫ֪��������������У��������������
            $this->db->join($this->table,'s.sid',$table,'sf.sid');
            $this->db->where_in('s.sid', $sids); //��ѯ��sid
            $this->db->select($select);
            if(!$q=$this->db->get()) continue;
            //�������
            while($v=$q->fetch_array()) {
                //ѭ��ÿ�����ݵĸ����ֶ�
                foreach($v as $_k => $_v) {
                    if($_k == 'sid') continue;
                    if(!$_v || strlen($_v)<3) continue;
                    //��selects����ͨ�Զ�������������ǩ�����ı�ǩ��id
                    if(!$groupid = (int)$selects[$_k]['config']['groupid']) continue;
                    $_v = unserialize($_v);
                    $TAG->add($v['city_id'], $groupid, $v['sid'], $_v); //�����ǩ��
                }
            }
            $q->free_result();
        }
    }

    // �ؽ�ͳ��
    function rebuild($sids) {
        if(is_numeric($sids) && $sids > 0) $sids = array($sids);
        if(!is_array($sids)||count($sids)==0) redirect('global_op_unselect');
        foreach($sids as $sid) {
            if(!$detail = $this->read($sid,'*',FALSE)) continue;
            $set = array();
            //����
            $this->db->from('dbpre_review');
            $this->db->where(array('idtype'=>'item_subject','id'=>$sid,'status'=>1));
            $set['reviews'] = $this->db->count();
            //ͼƬ
            $this->db->from('dbpre_pictures');
            $this->db->where(array('sid'=>$sid,'status'=>1));
            $set['pictures'] = $this->db->count();
            //����
            $this->db->from('dbpre_guestbook');
            $this->db->where(array('sid'=>$sid,'status'=>1));
            $set['guestbooks'] = $this->db->count();
            //����ͳ��
            $R =& $this->loader->model(':review');
            $myset = $R->update_review_point('item_subject',$sid,$this->get_review_config($detail), FALSE);
            $myset && $set = array_merge($set, $myset);
            unset($R);
            //����ģ�������HOOK
            foreach(array_keys($this->global['modules']) as $flag) {
                if($flag == $this->model_flag) continue;
                $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'item_rebuild_hook.php';
                if(is_file($file)) {
                    if($myset = include $file) {
                        $set = array_merge($set, $myset);
                    }
                }
            }
            //ȥ������Ҫ���µ�
            foreach($set as $key => $val) {
                if($detail[$key] == $val) {
                    unset($set[$key]);
                }
            }
            if(empty($set)) continue;
            $this->db->from($this->table);
            $this->db->set($set);
            $this->db->where('sid',$sid);
            $this->db->update();
        }
        
    }

    // ���������
    function pageview($sid, $num=1) {
        $num = intval($num);
        if(empty($num)) return;
        $this->db->from($this->table);
        $this->db->set_add('pageviews', $num);
        $this->db->where('sid', $sid);
        $this->db->update();
        //�ÿ�
        $V =& $this->loader->model('item:visitor');
        $V->visit($sid);
    }

    // ȡ��ĳ�����µĵ�����������
    function category_count($catid) {
        if(empty($catid)) return false;
        $this->db->from($this->table);
        $this->db->where('catid', $catid);
        return $this->db->count();
    }

    // ��ѯ���������Ƿ����
    function exists($city_id, $name, $subname, $without_sid = NULL) {
        $this->db->from($this->table);
        $this->db->where('city_id', (int) $city_id);
        $this->db->where('name', $name);
        $this->db->where('subname', $subname);        
        $without_sid > 0 && $this->db->where_not_equal('sid', $without_sid);
        return $this->db->count() > 0;
    }

    //���������
    function mappoint($sid,$mappoint) {
        if(!$mappoint) return;
        list($lng,$lat) = explode(',', $mappoint);
        $this->db->from($this->table);
        $this->db->set('map_lng',$lng);
        $this->db->set('map_lat',$lat);
        $this->db->where('sid',$sid);
        $this->db->update();
    }

    // �趨����Ա
    function set_owner($sid, $username, $expirydate = '', $update_subject = true, $show_error = true, $is_username = true) {
        if(!$sid || !$username) {
            if(!$show_error) return;
            redirect(lang('global_sql_keyid_invalid','sid|username'));
        }
        $M =& $this->loader->model(':member');
        if(!$member = $M->read($username, $is_username)) {
            if(!$show_error) return;
            redirect('member_empty');
        }
        $uid = $member['uid'];
        if($expirydate) {
            $this->loader->helper('validate');
            if(!validate::is_date($expirydate)) {
                if(!$show_error) return;
                redirect('item_post_owner_expirydate_invalid');
            }
            if(!$expirydate = strtotime($expirydate)) {
                if(!$show_error) return;
                redirect('item_post_owner_expirydate_invalid');
            }
            if($expirydate < $this->global['timestamp']) {
                if(!$show_error) return;
                redirect('item_post_owner_expirydate_less');
            }
        } else {
            $expirydate = 0;
        }
        $this->db->from('dbpre_mysubject');
        $this->db->where('sid', $sid);
        if($detail = $this->db->get_one()) {
            if($detail['uid'] == $uid && $detail['expirydate'] == $expirydate) return;
            $this->db->sql_roll_back('from,where');
            $this->db->set('uid', $uid);
            $this->db->set('expirydate', $expirydate);
            $this->db->set('lasttime ', _G('timestamp'));
            $this->db->update();
        } else {
            $this->db->sql_roll_back('from');
            $this->db->set('sid', $sid);
            $this->db->set('uid', $uid);
            $this->db->set('expirydate', $expirydate);
            $this->db->set('lasttime ', _G('timestamp'));
            $this->db->insert();
        }
        if($update_subject) {
            //if($detail && $detail['uid'] == $uid) return;
            $this->db->from($this->table);
            $this->db->set('owner',$member['username']);
            $this->db->where('sid',$sid);
            $this->db->update();
        }
    }

    // ɾ��һ������Ա
    function delete_owner($sid, $uid) {
        $this->db->join('dbpre_mysubject','ms.uid','dbpre_members','m.uid');
        $this->db->where('sid', $sid);
        $this->db->select('ms.*,m.username');
        $this->db->order_by('ms.uid');
        if(!$q = $this->db->get()) return;
        $up_username = array();
        $delete = false;
        while($v=$q->fetch_array()) {
            if($v['uid'] == $uid) {
                $delete = true;
            } else {
                $up_username[] = $v['username'];
            }
        }
        if(!$delete) return;
        $this->db->from('dbpre_mysubject');
        $this->db->where('sid', $sid);
        $this->db->where('uid', $uid);
        $this->db->delete();
        //���������ֶ�
        $this->db->from('dbpre_subject');
        $this->db->where('sid', $sid);
        $this->db->set('owner', $up_username ? implode(',',$up_username) : '');
        $this->db->update();
    }

    // �ҹ��������
    function mysubject($uid) {
        if(isset($this->global['mysubjects'])) return $this->global['mysubjects'];
        $mindate = strtotime(date('Y-m-d',$this->global['timestamp']));
        $result = array();
        $this->db->from('dbpre_mysubject');
        $this->db->where('uid', $uid);
        if(!$query = $this->db->get()) return $result;
        $delete = array();
        $up_sid = array();
        while($val = $query->fetch_array()) {
            if($val['expirydate'] == 0 || $val['expirydate'] >= $mindate) {
                $result[] = $val['sid'];
            } else {
                $delete[] = $val['id'];
                $up_sid[] = $val['sid'];
            }
        }
        if($delete) {
            $this->db->from('dbpre_mysubject');
            $this->db->where_in('id', $delete);
            $this->db->delete();
            $this->update_owner($delete); //���������
        }
        return $result;
    }

    // ���������Ա��
    function update_owner($sids) {
        $this->db->join('dbpre_mysubject','ms.uid','dbpre_members','m.uid');
        $this->db->where_in('sid', $sids);
        $this->db->select('ms.*,m.username');
        $this->db->order_by('ms.uid');
        $result = array();
        if(!$query = $this->db->get()) return $result;
        while($val = $query->fetch_array()) {
            $result[$val['sid']][] = $v['username'];
        }
        $query->free_result();
        foreach($sids as $sid) {
            $this->db->from('dbpre_subject');
            $this->db->where('sid', $sid);
            $this->db->set('owner', isset($result[$sid]) ? implode(',',$result[$sid]) : '');
            $this->db->update();
        }
    }

    // �ж��ҵ�����
    function is_mysubject($sid, $uid) {
		if(isset($this->global['mysubjects']) && $uid == $this->global['user']->uid) {
			return in_array($sid, $this->global['mysubjects']);
		}
		$this->db->from('dbpre_mysubject');
		$this->db->where('uid', $uid);
		$this->db->where('sid', $sid);
		return $this->db->count() >= 1;
    }

    // ��ȡĳ������Ĺ���Ա
    function owners($sid) {
        $this->db->join('dbpre_mysubject','ms.uid','dbpre_members','m.uid');
        $this->db->where('sid', $sid);
        $this->db->select('ms.*,m.username,m.groupid');
        $this->db->order_by('ms.uid');
        $result = array();
        if(!$query = $this->db->get()) return $result;
        while($val = $query->fetch_array()) {
            $result[] = $val;
        }
        $query->free_result();
        return $result;
    }

    // ��ȡ������
    function read_cookie($pid) {
        $key = 'subject_' . (int)$pid;
        $ckitems = empty($this->global['cookie'][$key]) ? array() : unserialize(stripslashes($this->global['cookie'][$key]));
        foreach($ckitems as $key => $val) {
            $ckitems[$key] = _T($val);
        }
        return $ckitems;
    }

    // д����������
    function write_cookie(& $subject, $num = 10, $day = 3) {
        $ckitems = $this->read_cookie($subject['pid']);
        if(!in_array($subject['sid'], $ckitems)) {
            $result = array();
            $result[$subject['sid']] = $subject['name'] . $subject['subname'];
            if(!empty($ckitems)) {
                $i = 1;
                foreach($ckitems as $key => $val) {
                    if($i >= $num) break;
                    $result[$key] = $val;
                    $i++;
                }
            }
        }
        set_cookie('subject_' . $subject['pid'], serialize($result), 86400 * $day);
    }

    //��ȡ�����б���ʽ����
    function display_listfield(&$subject) {
        $modelid = $this->get_modelid($subject['pid']);

        $subject_field = array();
        $select = 's.sid,pid,catid,name,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,pageviews,reviews,pictures,thumb,guestbooks,favorites';
        $select_arr = explode(',', $select);
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if(!$val['show_list']) continue;
            if(!in_array($val['fieldname'], $select_arr)) {
                $select .= ',' . $val['fieldname'];
            }
            if(!in_array($val['fieldname'], array('name','subname','mappoint','owner','status','templateid','listorder'))) {
                $subject_field[] = $val;
            }
        }
        unset($select, $select_arr, $val, $fields);
        $IFD =& $this->loader->model('item:fielddetail');
        //��ǰ��ʾ��ҳ������
        $IFD->pagemod = 'list';
        //��ʽ���
        $IFD->td_num = 1; //��ֻ��1��td
        $IFD->class = "";
        $IFD->width = "";
        $IFD->align = "left";
        $result = '';
        foreach($subject_field as $_val) {
            $result .= $IFD->detail($_val, $subject[$_val['fieldname']], $subject['sid']);
        }
        return $result;
    }

    //��ȡ������ϸ��ʽ����
    function display_detailfield($subject) {
        $modelid = $this->get_modelid($subject['pid']);
        //���ɱ������
        $FD =& $this->loader->model($this->model_flag.':fielddetail');
        $IFD =& $this->loader->model('item:fielddetail');
        //��ǰ��ʾ��ҳ������
        $IFD->pagemod = 'detail';
        //��ʽ���
        $FD->class = 'key';
        $FD->width = '';
        $result = '';
        //�����ֶ���Ϣ
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if(in_array($val['fieldname'], array('mappoint','status','templateid','listorder'))) continue;
            if($val['type'] == 'textarea') continue;
            if($val['show_detail']) $result .= $FD->detail($val, $subject[$val['fieldname']], $subject['sid']) . "\r\n";
        }
        return $result;
    }

    //��ȡ�����б���ʽ����
    function display_sidefield(&$subject) {
        $modelid = $this->get_modelid($subject['pid']);

        $subject_field = array();
        $select = 's.sid,pid,catid,name,avgsort,sort1,sort2,sort3,sort4,sort5,sort6,sort7,sort8,best,pageviews,reviews,pictures,thumb,guestbooks,favorites';
        $select_arr = explode(',', $select);
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if(!$val['show_side']) continue;
            if(!in_array($val['fieldname'], $select_arr)) {
                $select .= ',' . $val['fieldname'];
            }
            if(!in_array($val['fieldname'], array('name','subname','mappoint','owner','status','templateid','listorder'))) {
                $subject_field[] = $val;
            }
        }
        unset($select, $select_arr, $val, $fields);
        $IFD =& $this->loader->model('item:fielddetail');
        //��ǰ��ʾ��ҳ������
        $IFD->pagemod = 'side';
        //��ʽ���
        $IFD->td_num = 1; //��ֻ��1��td
        $IFD->class = "";
        $IFD->width = "";
        $IFD->align = "left";
        $result = '';
        foreach($subject_field as $_val) {
            $result .= $IFD->detail($_val, $subject[$_val['fieldname']], $subject['sid']);
        }
        return $result;
    }

    //���ɱ�
    function create_form($pid, $subject = null, $ff_prarms = array('class'=>'altbg1')) {
        $cate = $this->loader->variable('category', $this->model_flag);

        if(!$category = $cate[$pid]) redirect('item_cat_empty');
        if(!$fieldlist = $this->loader->variable('field_' . $category['modelid'], $this->model_flag)) redirect('item_field_empty');

        $FF =& $this->loader->model($this->model_flag.':fieldform');
        $FF->all_data($subject);
        if($ff_prarms && is_array($ff_prarms)) {
            foreach($ff_prarms as $k => $v) {
                $FF->$k = $v;
            }
        }
        $content = '';
        foreach($fieldlist as $val) {
            if(!$this->in_admin && $val['isadminfield']=='2') {
                if(!$this->is_mysubject($subject['sid'], $this->global['user']->uid)) continue;
            } elseif(!$this->in_admin && !empty($val['isadminfield'])) {
                continue;
            }
            if(defined('item_allownull_' . $val['fieldname'])) $val['allownull'] = 0;
            $content .= $FF->form($val, $subject?$subject[$val['fieldname']]:'', $subject != null);
        }

        return $content;
    }

    // ȡ��ĳ�����������
    function get_category_total($catid) {
        $this->db->where('catid', $catid);
        $this->db->where('status', 1);
        $this->db->from($this->table);
        return $this->db->count(); 
    }

    // ���ӷ���ͳ������
    function category_num_add($catid, $num=1) {
        $this->db->from('dbpre_category');
        $this->db->set_add('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // ���ٷ���ͳ������
    function category_num_dec($catid, $num=1) {
        $this->db->from('dbpre_category');
        $this->db->set_dec('total', $num);
        $this->db->where('catid', $catid);
        $this->db->update();
    }

    // ���ӻ���
    function add_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_subject', FALSE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_add('subjects', $num);
        $this->db->update();
    }

    // ���ٻ���
    function dec_user_point($uid, $num = 1) {
        if(!$uid) return;
        $P =& $this->loader->model('member:point');
        $BOOL = $P->update_point($uid, 'add_subject', TRUE, $num, FALSE, FALSE);
        if(!$BOOL) return;
        $this->db->set_dec('subjects', $num);
        $this->db->update();
    }

    //��Ծ��
    function activity($uid,$username) {
        if(!$uid && !$username) return;
        $post = array();
        if(!$uid || !$username) {
            $this->db->from('dbpre_members');
            $this->db->select('uid,username');
            if($uid) $this->db->where('uid', $uid);
            if($username) $this->db->where('username', $username);
            if(!$res = $this->db->get_one()) return;
            $uid = $res['uid'];
            $username = $res['username'];
        }
        $A =& $this->loader->model($this->model_flag.':activity');
        $A->save($uid, $username, 1, 0);
    }

    //����������Ȩ��
    function check_access($key,$value,$jump) {
        if($this->in_admin) return TRUE;
        if($key == 'item_subjects') {
            $value = (int) $value;
            if($value && $value < 0) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('item_access_alow_subject');
            }
            if($value && $value < $this->global['user']->subjects) {
                if(!$jump) return FALSE;
                redirect('item_access_subjects');
            }
        } elseif($key=='item_allow_edit_subject') {
            $value = (int) $value;
            if(!$value) {
                if(!$jump) return FALSE;
                if(!$this->global['user']->isLogin) redirect('member_not_login');
                redirect('item_access_allow_edit_subject');
            }
        }
        return TRUE;
    }

    //����ύ����
    function check_post(&$post,$edit,$sid) {
        if($post['domain']) {
            !$this->domain_check($post['domain']) and redirect('item_post_domain_invalid');
            $this->domain_exists($post['domain'],$sid) and redirect('item_post_domain_exists');
        }
        if($post['aid']) {
            if(!$post['city_id']) redirect('item_post_city_id_empty');
            $area = $this->loader->variable('area_' . $post['city_id'],'',false);
            if(!isset($area[$post['aid']])) redirect('item_post_aid_empty');
            if($area[$post['aid']]['level']<2) redirect('item_post_aid_level_invalid');
        }
        //�����ID�ֶ�
        if($post['templateid'] > 0) { //ʹ���˷����Ҫ���м��
            $tpllist = $this->loader->variable('templates');
            $exists = false;
            empty($tpllist) && redirect(lang('item_fieldvalidator_no_select_item','������'));
            if($tpllist['item']) foreach($tpllist['item'] as $val) {
                if($val['templateid'] == $post['templateid']) {
                    $exists = true;
                    break;
                }
            }
            !$exists && redirect(lang('item_fieldvalidator_invalid_item', '������'));
        }
    }

    //���������ĺϷ��Լ��
    function domain_check($domain) {
        if(preg_match("/^[0-9]+$/i", $domain)) return false;
        if(!preg_match("/^[a-z0-9]{1,20}$/i", $domain)) return false;
        if(is_numeric($domain)) return false;
        if(in_array($domain, array_keys($this->global['modules']))) return false;
        $actlist = array('ajax','member','index','list','detail','pic','allpic','reviews','top','tag','rss','search',
            'cate','category');
        if(in_array($domain, $actlist)) return false;
        if($reserve = $this->modcfg['reserve_sldomain']) {
            $list = explode(',', $reserve);
            if(in_array($domain, $list)) return false;
        }
        return true;
    }

    //�����������Ƿ����
    function domain_exists($domain,$exc_sid=null) {
        $this->db->from($this->table);
        $this->db->where('domain',$domain);
        if($exc_sid>0) $this->db->where_not_equal('sid', $exc_sid);
        return $this->db->count() >= 1;
    }

    //��⵱ǰ�û��ĵ���Ȩ��
    //���������ֵ 
    // code | 1:���� -1:Ȩ�޲��� -2:����δ�����ο͵��� -3:����δ�����ظ��� -4:��Աû���ظ�����Ȩ�� -5:��Ա�ظ�������������(extra:������) -6:�ظ�����ʱ����δ��(extra:ʱ����)
    function review_access($sid=null) {
        $result = array('code'=>1,'extra'=>'');
        if($this->in_admin) return $result;
        $R =& $this->loader->model(':review');
        if(!$this->global['user']->check_access('review_num', $R, FALSE)) {
            $result['code'] = -1;
            return $result;
        }
        $R =& $this->loader->model(':review');
        if(!$sid) return $result;
        $subject = is_array($sid) ? $sid : $this->read($sid,'*',false);
        $sid = $subject['sid'];
        $count = $R->reviewed('item_subject', $sid, true); //���������������
        $category = $this->get_category($subject['pid']);
        if(!$category['config']['guest_review'] && !$this->global['user']->isLogin) {
            $result['code'] = -2;
            return $result; //δ�����ο͵���
        }
        if(!$count['num']) return $result; //��һ�ε���
        if(!$category['config']['repeat_review'] && $count['num'] > 0) {
            $result['code'] = -3;
            return $result; //δ�����ظ�����
        }
        if(!$this->global['user']->check_access('review_repeat', $R, FALSE)) {
            $result['code'] = -4;
            return $result; //��Ա��û���ظ�����Ȩ��
        }
        $minnum = (int) $category['config']['repeat_review_num'];
        if($minnum && $minnum <= $count['num']) {
            $result['code'] = -5;
            $result['extra'] = $minnum;
            return $result; //��������������
        }
        if($count['num'] > 1) {
            $last = $R->get_last('item_subject', $sid);
            $lasttime = $last['posttime'];
        } else {
            $lasttime = $count['posttime'];
        }
        $time = $this->global['timestamp'] - $lasttime;
        $mintime = (int) $category['config']['repeat_review_time'];
        if($mintime && ($mintime*60) >= $time) {
            $result['code'] = -6;
            $result['extra'] = $mintime;
            return $result; //�ظ�����ʱ����δ��
        }
        return $result;
    }

    //��ȡ���������
    function get_subject($params) {
        //vp($params);
        return $params['name'] . ($params['subname']?('('.$params['subname'].')'):'');
    }

    //��ȡ������������
    function get_city_id($params) {
        return $params['city_id'];
    }

    //��ȡ�����pid
    function get_obj_pid($params) {
        return $params['pid'];
    }

    //���µ�������
    function review_total($sid, $num) {
        if(!$sid||!$num) return;
        $this->db->from($this->table);
        $this->db->where('sid',$sid);
        if($num>0) {
            $this->db->set_add('reviews',$num);
        } else {
            $this->db->set_dec('reviews',abs($num));
        }
        $this->db->update();
    }

    //��ȡ������������Ĳ�������
    function get_review_config($params) {
        $category = $this->variable('category');
        $pid = $params['pid'];
        $config = array();
        $config = $category[$pid]['config'];
        $config['review_opt_gid'] = $category[$pid]['review_opt_gid'];
        return $config;
    }

    //��ȡ��������������б�
    function get_review_category() {
        $category = $this->variable('category');
        $array = array();
        if($category) foreach($category as $val) {
            $array[$val['catid']] = $val['name'];
        }
        return $array;
    }

    //��ȡ��ǰ����Ĺ��������ֶ�
    function get_relate_subject_field($modelid) {
        $result = array();
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if($val['type']!='subject') continue;
            if(!$val['show_detail']) continue;
            //if($val['isadminfield']) continue;
            if($val['config']['showmod']=='word'||!$val['config']['showmod']) continue;
            $result[] = $val;
        }
        return $result;
    }
	
    //��ȡ��ǰ������Ա��Ͳ�Ʒ�ֶ�
    function get_taoke_product_field($modelid) {
        $result = array();
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if($val['type']!='taoke_product') continue;
            if(!$val['show_detail']) continue;
            //if($val['isadminfield']) continue;
            $result[] = $val;
        }
        return $result;
    }

    //��ȡ��ǰ����Ķ����ı��ֶ�
    function get_textarea_field($modelid) {
        $result = array();
        $fields = $this->variable('field_' . $modelid);
        foreach($fields as $val) {
            if($val['type']!='textarea') continue;
            if(!$val['show_detail']) continue;
            //if($val['isadminfield']) continue;
            $result[] = $val;
        }
        return $result;
    }

    //������������
    function set_template($sid,$templateid,$catid=null) {
        if(!$catid) {
            //���÷���ID���ɼ���һ�β��
            $subject = $this->read($sid);
            if(empty($subject)) redirect('item_empty');
            $catid = $subject['catid'];
        }
        $templateid = (int) $templateid;
        $this->get_category($catid);
        $this->get_model($this->category['modelid']);
        $table = 'dbpre_' . $this->model['tablename'];
        $this->db->from($table);
        $this->db->where('sid',$sid);
        $this->db->set('templateid',$templateid);
        $this->db->update();
    }

    //��ȡ��������
    function get_style() {
        if(!$this->style) {
            $this->style =& $this->loader->model('item:subjectstyle');
        }
        return $this->style;
    }

    //ɾ������
    function _delete($where, $uptotal = TRUE, $delete_point = TRUE) {
        $this->db->select('sid,pid,catid,status,cuid,owner,creator');
        $this->db->from($this->table);
        $this->db->where($where);
        if(!$row = $this->db->get()) return;

        $upids = $creator = array();
        while ($value = $row->fetch_array()) {
            if(!$this->in_admin && $this->global['user']->username != $value['owner']) {
                redirect('global_op_access');
            }
            $upids[$value['catid']][] = $value['sid'];
            if($value['status'] == '1') {
                // ����ͳ�Ƹ���
                if($uptotal) $this->category_num_dec($value['catid']);
                // ����
                if($delete_point && $value['cuid']) {
                    if(isset($creator[$value['cuid']])) {
                        $creator[$value['cuid']]++;
                    } else {
                        $creator[$value['cuid']] = 1;
                    }
                }
            }
        }
        $row->free_result();

        if($upids) {
            $delids = array();
            foreach($upids as $pid => $ids) {
                // ����(��ģ�ͻ����ɲ�ͬ�ı�)
                $this->_delete_field($ids, $pid);
                $delids = array_merge($delids, $ids);
            }
            // ����
            $this->db->from($this->table);
            $this->db->where_in('sid', $delids);
            $this->db->delete();
            // ɾ��������
            $this->_delete_relate($delids);
        }

        if($creator) {
			if($delete_point) {
	            // ���ٻ���
	            $P =& $this->loader->model('member:point');
	            foreach($creator as $uid => $num) {
	                $P->update_point($uid, 'add_subject', TRUE, $num);
	            }
			}
			if($creator) foreach ($creator as $uid => $num) {
				$this->db->from('dbpre_members')
					->where('uid',$uid)
					->set_dec('subjects',(int)$num)
					->update();
			}
        }
    }

    // ɾ������
    function _delete_field($ids, $pid) {
        $this->get_category($pid);
        $this->get_model($this->category['modelid']);
        $table = 'dbpre_' . $this->model['tablename'];
        //ɾ����������
        $this->db->from($table);
        $this->db->where_in('sid', $ids);
        $this->db->delete();
        //ɾ����Ա����
        $MF =& $this->loader->model('member:membereffect');
        $MF->delete($ids, $this->model['tablename']);
    }

    // ɾ����������Ϣ��ֱ�ӹ�����
    function _delete_relate($sids) {
        //�������Ա��
        $this->db->from('dbpre_mysubject');
        $this->db->where_in('sid', $sids);
        $this->db->delete();
        //����
        $R =& $this->loader->model(':review');
        $R->delete_idtype('item_subject', $sids, FALSE, FALSE);
        //ͼƬ
        $P =& $this->loader->model('item:picture');
        $P->delete_subject($sids);
        //���
        $A =& $this->loader->model('item:album');
        $A->delete_subject($sids);
        //����
        $GB =& $this->loader->model('item:guestbook');
        $GB->delete($sids, FALSE, FALSE, TRUE);
        //��ǩ
        $TAG =& $this->loader->model('item:tag');
        $TAG->delete_ids($sids);
        //�ղ�
        $FAV =& $this->loader->model('item:favorite');
        $FAV->delete_sids($sids);
		//�Ա���
		$STK =& $this->loader->model('item:subjecttaoke');
		$STK->delete_sids($sids);
        unset($R,$P,$GB,$TAG,$FAV,$STK);
        //����ģ�������ɾ������HOOK
        foreach(array_keys($this->global['modules']) as $flag) {
            if($flag == $this->model_flag) continue;
            $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'item_delete_hook.php';
            if(is_file($file)) {
                @include $file;
            }
        }
    }

    //feed
    function _feed($sid) {
        if(!$sid) return;

        $FEED =& $this->loader->model('member:feed');
        if(!$FEED->enabled()) return;
        $this->global['fullalways'] = TRUE;

        if(!$detail = $this->read($sid,'*', FALSE)) return;
        if(!$detail['cuid']) return;
        $model = $this->get_model($detail['pid'], TRUE);

        $feed = array();
        $feed['icon'] = lang('item_subject_feed_icon');
        $feed['title_template'] = lang('item_subject_feed_title_template');
        $feed['title_data'] = array (
            'username' => '<a href="'.url("space/index/uid/$detail[cuid]").'">' . $detail['creator'] . '</a>',
            'item_unit' => $model['item_unit'],
            'item_name' => $model['item_name'],
        );
        $feed['body_template'] = lang('item_subject_feed_body_template');
        $title = $detail['name'] . ($detail['subname'] ? "($detail[subname])" : '');
        $feed['body_data'] = array (
            'title' => '<a href="'.url("item/detail/id/$detail[sid]").'">'.$title.'</a>',
            'review' => '<a href="'.url("review/member/ac/add/type/item_subject/id/$detail[sid]").'">'.lang('item_review').'</a>',
        );
        $feed['body_general'] = '';

        $FEED->save($this->model_flag, $detail['city_id'], $feed['icon'], $detail['cuid'], $detail['creator'], $feed);
        //$FEED->add($feed['icon'], $detail['cuid'], $detail['creator'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], $feed['body_general']);

    }
}
?>