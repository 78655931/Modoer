<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
class msm_template extends ms_model {

    var $table = 'dbpre_templates';
    var $key = 'templateid';

    function __construct() {
        parent::__construct();
    }

    function msm_template() {
        $this->__construct();
    }

    function update($post, $tpltype='main') {
        foreach($post as $templateid => $val) {
            $val['tpltype'] = $tpltype;
            $this->save($val, $templateid, FALSE);
        }
        $this->write_cache();
    }

    function delete($ids) {
        if(is_array($ids)) {
            $this->db->where_in('templateid', $ids);
            $this->db->from($this->table);
            $this->db->delete();
        } else {
            parent::delete($ids);
        }
        $this->write_cache();
    }

    function read_all($type='main') {
        $this->db->where("tpltype", $type);
        $this->db->from($this->table);
        $row = $this->db->get();

        $result = array();
        if(!$row) return $result;
        while($value=$row->fetch_array()) {
            $result[] = $value;
        }
        $row->free_result();

        return $result;
    }

    function write_cache() {
        $this->db->from($this->table);
        $this->db->order_by(array('tpltype'=>'ASC','templateid'=>'ASC'));
        $row = $this->db->get();
        while($value = $row->fetch_array()) {
            $result[$value['tpltype']][$value['templateid']] = $value;
        }
        write_cache('templates', arrayeval($result));
    }

    function check_post(&$post) {
        if(!$post['name']) redirect('admincp_template_empty_name');
        if(!$post['directory']) redirect('admincp_template_empty_dir');
        if(!$post['tpltype']) redirect('admincp_template_empty_type');
        if(!$this->_is_template_dir($post['directory'],$post['tpltype'])) redirect('admincp_template_invalid');
    }

    function post_file_content() {
        if(!$content = trim($_POST['content'])) redirect('admincp_template_edit_content_empty');
        if(isset($_POST['filedir'])) { //new
            if(!$_POST['filename']) redirect('admincp_template_add_filename_empty');
            $filename = MUDDER_ROOT . $_POST['filedir'] . $_POST['filename'];
            if(file_exists($filename)) redirect(lang('global_file_exists'));
            file_put_contents($filename, $content);
            chmod($filename, 0777);
        } else { //edit
            if(!$_POST['filename']) redirect('admincp_template_edit_filename_empty');
            file_put_contents($_POST['filename'], $content);
            chmod($_POST['filename'], 0777);
        }
    }

    function delete_files($files) {
        if(!$files||!is_array($files)) redirect('global_op_unselect');
        foreach($files as $f) {
            if(!$f || strlen($f) < 10 || !is_file(MUDDER_ROOT . $f)) continue;
            unlink(MUDDER_ROOT . $f);
        }
    }

    function manage($root_dir, $files) {
        if(!$root_dir || strlen($root_dir) < 10) redirect('admincp_template_manage_rootdir_empty');
        if(!$files||!is_array($files)) redirect('global_op_nothing');
        $template_des = array();
        foreach($files as $val) {
            $template_des[$val['newfilename']] = $val['des'];
            if($val['filename'] == $val['newfilename']) continue;
            if(!$val['filename'] || !$val['newfilename']) redirect('admincp_template_manage_filename_empty');
            if(!is_file(MUDDER_ROOT . $root_dir . DS . $val['filename'])) redirect(lang('global_file_not_exist', $val['filename']));
            rename(MUDDER_ROOT . $root_dir . DS . $val['filename'], MUDDER_ROOT . $root_dir . DS . $val['newfilename']);
        }
        $this->_write_des($root_dir, $template_des);
    }

    function _is_template_dir($directory, $type = 'main') {
        $dir = MUDDER_ROOT . 'templates' . DS . $type . DS . $directory;
        return is_dir($dir);
    }

    function _write_des($root_dir, $data) {
        $content = "<?php\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\nreturn " . arrayeval($data) . "; \r\n?>";
        file_put_contents(MUDDER_ROOT.$root_dir.DS.'template.php', $content);
    }
}
?>