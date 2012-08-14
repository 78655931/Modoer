<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_article_category extends ms_model {

    var $table = 'dbpre_article_category';
	var $key = 'catid';
    var $model_flag = 'article';

	function __construct() {
		parent::__construct();
        $this->model_flag = 'article';
        $this->modcfg = $this->variable('config');
        $this->init_field();
	}

    function msm_article_category() {
        $this->__construct();
    }

	function init_field() {
        $this->add_field('pid,name,listorder');
        $this->add_field_fun('pid,listorder', 'intval');
        $this->add_field_fun('name', '_T');
    }

    function find($where) {
        list(,$list) = parent::find('*',$where,'listorder',0,0,false);
        return $list;
    }

    //�ƶ�����
    function move($catids,$move_pid) {
        if(!$catids||!is_array($catids)) redirect('global_op_unselect');
        if(!$move_pid) redirect('article_category_move_dest_empty');
        $this->db->from($this->table);
        $this->db->set('pid', $move_pid);
        $this->db->where_in('catid', $catids);
        $this->db->update();
    }

    //����
    function update($post) {
        if(!$post) redirect('global_op_unselect');
        foreach($post as $catid => $val) {
            parent::save($val, $catid, false);
        }
    }

    //ɾ��
    function delete($catids) {
        $ids = $this->get_keyids($catids);
        $category = $this->variable('category');
        $pids = $subcatids = array();
        foreach($ids as $id) {
            if($category[$id]['pid']) {
                $subcatids[] = $id;
            } else {
                $pids[] = $id;
                $subcatids = array_merge($subcatids, array_keys($this->get_sub_cats($id)));
            }
        }
        if($subcatids) {
            $A =& $this->loader->model(':article');
            $A->delete_catids($subcatids);
        }
        parent::delete(array_merge($pids,$subcatids));
    }

    //�����ؽ�
    function rebuild($catids) {
        $catids = parent::get_keyids($catids);
        $A =& $this->loader->model(':article');
        foreach($catids as $catid) {
            $total = $A->total_cat_mun($catid);
            $this->db->from($this->table);
            $this->db->set('total',$total);
            $this->db->where('catid',$catid);
            $this->db->update();
        }
    }

    //�����
    function check_post(& $post, $edit = false) {
        if(!$post['name']) {
            redirect('article_category_name_empty');
        }
    }

    //����
    function write_cache() {
        $this->db->from($this->table);
        $this->db->order_by(array('pid'=>'ASC','listorder'=>'ASC'));
        $list = $this->db->get();
        $result = array();

        $js_content = "var article_category_root = new Array();\n";
        $js_content .= "var article_category_sub = new Array();\n";

        if($list) while($val=$list->fetch_array()) {
            $result[$val['catid']] = $val;
            if(!$val['pid']) {
                $js_content .= "article_category_root[$val[catid]] = '$val[name]';\n";
                $js_content .= "article_category_sub[$val[catid]] = new Array();\n";
            } else {
                $js_content .= "article_category_sub[$val[pid]][$val[catid]] = '$val[name]';\n";
            }
        }
        write_cache('category', arrayeval($result), $this->model_flag);
        write_cache('article_category', $js_content, $this->model_flag, 'js');
        //����article_category.js����Ϊ������Ỻ��js�ļ���������Ҫ��js��һ�����±�ʶ������ģ����������
        $C =& $this->loader->model('config');
        $C->save(array('jscache_flag'=>rand(1,1000)), 'article');
    }

    //ȡ�ӷ����б�
    function get_sub_cats($id) {
        $result = array();
        $category = $this->variable('category');
        if($category[$id]['pid']) {
            $id = $category[$id]['pid'];
        }
        foreach($category as $val) {
            if($val['pid']  == $id) $result[$val['catid']] = $val['name'];
        }
        return $result;
    }
}
?>