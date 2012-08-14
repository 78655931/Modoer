<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

!defined('IN_MUDDER') && exit('Access Denied');

class msm_word extends ms_model {

	var $table = 'dbpre_words';
    var $key = 'id';
    var $filters;

    function __construct() {
        parent::__construct();
    }

    function msm_word() {
		$this->__construct();
    }

    function & find($start,$offset=20) {
        $result = array();

        $this->db->from($this->table);
        $result[] = $this->db->count();

        $this->db->sql_roll_back('from');
        $this->db->limit($start, $offset);
        $result[] = $this->db->get();

        return $result;
    }

    function save(& $post, $id=NULL, $cache=TRUE) {
        $this->check_post($post);
        if($id > 0) {
            $this->db->from($this->table);
            $this->db->where('keyword', $post['keyword']);
            if($this->db->count() > 0) redirect('admincp_word_kw_exists');
        }
        return parent::save($post, $id, $cache, FALSE);
    }

    function update($post) {
        if(!$post) return;
        foreach($post as $id => $val) {
            parent::save($val, $id, FALSE);
        }
        $this->write_cache();
    }

    function filter(& $content) {
        if(!$filters = $this->_read_cache()) return $content;
        if($filters['block'] && preg_match($filters['block'], preg_replace("/\s*|\[[^\]]*\]/i", '', $content))) {
            redirect('modoer_word_filter');
        } else {
            return empty($filters['filter']) ? $content :
                @preg_replace($filters['filter']['search'], $filters['filter']['replace'], $content);
        }
    }

    function check(& $content) {
        if(!$filters = $this->_read_cache()) return FALSE;
        return $filters['check'] && preg_match($filters['check'], $content);
    }

    function check_post(& $post, $edit = FALSE) {
        if(!$post['keyword']) redirect('admincp_word_kw_empty');
        if(!$post['expression']) redirect('admincp_word_exp_empty');
        if(strlen($post['keyword']) < 3) redirect('admincp_word_kw_short');
    }

    function write_cache() {
        $result = $filter = $block = $check = array();
        $this->db->from($this->table);
        if($query = $this->db->get()) {
            while($row = $query->fetch_array()) {
                if($row['expression'] == '{CHECK}') {
                    $check[] = $row['keyword'];
                } elseif($row['expression'] == '{BLOCK}') {
                    $block[] = $row['keyword'];
                } else {
                    $filter['search'][] = "/".str_replace("/","\\/",$row['keyword'])."/i";
                    $filter['replace'][] = str_replace("'","\'",$row['expression']);
                }
            }
            $result['filter'] = $filter;
            $result['block'] = $block ? "/(".implode('|',$block).")/i" : '';
            $result['check'] = $check ? "/(".implode('|',$check).")/i" : '';
        }
        write_cache('words', arrayeval($result));
    }

    function _read_cache() {
        if(empty($this->filter)) {
            return $this->loader->variable('words', 'modoer', FALSE);
        }
    }
}
?>