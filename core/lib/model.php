<?php
/**
* Modoer���ģ�ͻ���
* @author moufer<moufer@163.com>
* @copyright modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_model extends ms_base {

    var $db = null;
    // ��ǰģ�Ͳ����ı�
    var $table = null;
    // ����������
    var $key = null;
    // ���ֶ��б�
    var $field = array();
    // ���ֶ��Զ�������
    var $field_fun = array();
    // ģ���ʶ
    var $model_flag = null;
    // ��ʵ����ʱ���Զ���⻺��
    var $auto_check_write = false;
    // ��������
    var $cache_name = '';

    function __construct() {
        global $_G;
        parent::__construct();
        if(!isset($this->global['db'])) {
            $this->global['db'] =& $this->loader->lib('database', NULL, TRUE, $this->global['dns']);
        }
        $this->db =& $this->global['db'];
         // �Զ�����Ҫ�Ļ��棬���Զ�����
        $this->auto_check_write && $this->check_write();
    }

    function ms_base() {
        $this->__construct();
    }

    // ��ȡȫ�ֱ�����δ����ȫ�ֱ���ʱ���Ӷ�Ӧ�Ļ����л�ȡ
    function variable($keyname, $show_error = TRUE) {
        if($this->model_flag) {
            return $this->loader->variable($keyname, $this->model_flag, $show_error);
        } else {
            return $this->loader->variable($keyname, '', $show_error);
        }
    }

    // ¼�뵱ǰ����Ҫ�û��ύ���ֶ�����
    function add_field($field) {
		if(is_array($field)) {
			foreach($field as $v) $this->add_field($v);
		} else {
			if(strpos($field, ',')) 
                $this->add_field(explode(",", $field));
			else
				if(!in_array($field, $this->field)) $this->field[] = $field;
		}
    }

    // ¼���ύ���ֶ�����ת�����躯��
    function add_field_fun($field, $fun) {
		if(is_array($field)) {
			foreach($field as $v) $this->add_field_fun($v, $fun);		
		} else {
			if(strpos($field, ','))
				$this->add_field_fun(explode(",", $field), $fun);
			else
				$this->field_fun[$field] = $fun;
		}
    }

    // ͨ��¼��ı��ֶΣ�������һ���û��ύ����������
    function get_post(& $post) {
        if(!$this->field) return FALSE;
        $result = array();
        foreach($this->field as $key) {
            if(isset($post[$key])) $result[$key] = $post[$key];
        }
        return $result;
    }

    // �Ի�õ�����������ж�Ӧ��ֵ���к���ת��
    function convert_post(& $post) {
		if(!$this->field_fun) return $post;
		foreach($this->field_fun as $key => $fun) {
			if(!isset($post[$key])) continue;
			$post[$key] = $fun($post[$key]);
		}
		return $post;
    }

    // ��ѡ������ʽ����������
    function get_keyids($ids) {
        if(is_numeric($ids)&&$ids>0) $ids = array($ids);
        if(!$ids || !is_array($ids)) redirect('global_op_unselect');
        return $ids;
    }

    // ��ȡ��ǰ�������
    function & read($value, $select="*") {
        if(!$value) redirect(lang('global_sql_keyid_invalid', $this->key));
        $where = array();
        $where[$this->key] = $value;
        $row = $this->db->get_easy($select, $this->table, $where);
        $result = $row ? $row->fetch_array() : FALSE;
        return $result;
    }

    // ��ȡ��ǰ�����������
    function read_all($select="*", $orderby=null, $total=FALSE) {
        $result = array();
        $this->db->clear();
        $result = array(0, '');
        $this->db->from($this->table);
        if($total) {
            if(!$result[0] = $this->db->count()) return $result;
            $this->db->sql_roll_back('from');
        }
        $this->db->select($select);
        if($orderby) $this->db->order_by($orderby);
        $result[1] = $this->db->get();
        return $result;
    }

    // ��ѯ��ǰ�������
    function find($select="*", $where=null, $orderby=null, $start=0, $offset=10, $total=FALSE) {
	    $this->db->from($this->table);
		$where && $this->db->where($where);
        $result = array(0,'');
        if($total) {
            if(!$result[0] = $this->db->count()) {
                return $result;
            }
            $this->db->sql_roll_back('from,where');
        }
		$this->db->select($select?$select:'*');
        if($orderby) $this->db->order_by($orderby);
        if($start < 0) {
            if(!$result[0]) {
                $start = 0;
            } else {
                $start = (ceil($result[0]/$offset) - abs($start)) * $offset; //�� ����ҳ�� �����ȡλ��
            }
        }
        $this->db->limit($start, $offset);
        $result[1] = $this->db->get();

        return $result;
    }

    // ɾ����ǰ���е�һЩ����
    function delete($ids, $cache=TRUE) {
        $ids = $this->get_keyids($ids);
        $this->db->from($this->table);
        if(is_array($ids)) {
            $this->db->where_in($this->key, $ids);
        } else {
            $this->db->where($this->key, $ids);
        }
        $this->db->delete();
        //�Ƿ���»���
        if($cache) $this->write_cache();
    }

    // �������ݵ���ǰ�ı�
    function save(& $post, $keyid=null, $cache=TRUE, $check=TRUE, $convert=TRUE) {
        $edit = $keyid != null;
        $detail = null;
        if(is_array($keyid)) { //���²����������һ�����飬��ʾ�����һ�������ľ���������
            $detail = $keyid;
            $keyid = $keyid[$this->key];
        }
        // ��ʽ����ת�����ֶ�ֵ
        if($convert) {
            $post = $this->convert_post($post);
        }
        // ����ֶ�
        if($check) { 
            $result = $this->check_post($post, $edit);
            // ������ص����飬��˵�����ص����ύ����
            if(!empty($result) && is_array($result)) $post = $result;
        }
        $mypost = array();
        //ȥ����ͬ���ֶΣ������£�
        if($detail) {
            foreach($detail as $k=>$v) {
                if(isset($post[$k]) && $post[$k]!=$v) {
                    $mypost[$k] = $post[$k];
                }
            }
            //postû������˵��û�и����ֶΣ���������
            if(!$mypost || count($mypost)==0) return $keyid;
        } else {
            $mypost = $post;
        }
        //׼���������
        $this->db->from($this->table);
        if($mypost) foreach($mypost as $k => $v) {
            $this->db->set($k, $v);
        }
        // ���±�����
        if($keyid) {
            $this->db->where($this->key, $keyid);
            $this->db->update();
        } else {
            $this->db->insert();
            $keyid = $this->db->insert_id();
        }
        // ���»���
        if($cache) {
            $this->write_cache();
        }
        // ����insert_id
        return $keyid;
    }

    // д�뻺��ġ����������ɼ̳���������ɾ��庯��
    function write_cache() {}

    // ��֤�û��ύ�����ݡ����������ɼ̳���������ɾ��庯��
    function check_post(& $post, $edit = FALSE) {}

    // �жϻ����Ƿ���ڡ�
    function check_write() {
        if(!$this->cache_name) return;
        $cachefile = MUDDER_CACHE . $this->model_flag . '_' . $this->cache_name . '.php'; 
        if(!check_cache($cachefile)) {
            $this->write_cache();
        }
    }
}

