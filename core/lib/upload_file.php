<?php
/**
* 文件上传类
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_upload_file {

    var $folder;
    var $max_size;
    var $root_dir = 'uploads';
    var $limit_ext;
    var $lock_name = '';

    // only read
    var $size;
    var $filename;
    var $path;
    var $src;

    //private
    var $_file;

    function __construct($name,$exts='') { //PHP 5
    	if(empty($_FILES[$name])) {
    	    redirect('global_upload_error_4');
    	}
        $this->_file =& $_FILES[$name];
        if($this->_file['error'] > 0) {
            redirect('global_upload_error_' . $this->_file['error']);
        }
        $this->size = $this->_file['size'];
        
        $this->max_size = size_bytes(ini_get('upload_max_filesize'));
        if(!$exts) $exts = 'rar zip 7z txt';
        $this->set_ext($exts);
    }

    function ms_upload_file($name,$etxs='') { //PHP 4
        $this->__construct($name,$etxs);
    }

    function set_max_size($size) {
        $this->max_size = min($this->max_size, size_bytes($size . 'k'));
    }

    function set_ext($exts) {
        if(!$exts) return '';
        $exts = explode(' ', $exts);
        foreach($exts as $k => $v) {
            if(!$v) {
                unset($exts[$k]);
            } else {
                $exts[$k] = strtolower($v);
            }
        }
        if($exts) $this->limit_ext = $exts;
    }

    function get_filename() {
        $this->_check();
        $filename = str_replace("\\\\", "\\", $this->_file['tmp_name']);
        return $filename;
    }

    function get_contents() {
        $filename = $this->get_filename();
        return file_get_contents($filename);
    }

    function upload($folder, $subdir = 'MONTH') {
        
        $this->_check();
        
        $this->folder = $folder;
        $path = MUDDER_ROOT . $this->root_dir . DS . $this->folder;
        if(!@is_dir($path)){
            if(!@mkdir($path, 0777)) {
                show_error(sprintf('global_mkdir_no_access'), $this->root_dir . DS . $this->folder);
            }
        }

        if($subdir == 'WEEK') {
            $subdir = date('Y', _G('timestamp')).'-week-'.date('W', _G('timestamp'));
        } elseif($subdir == 'DAY') {
            $subdir = date('Y-m-d', _G('timestamp'));
        } elseif(!$subdir || $subdir == 'MONTH') {
            $subdir = date('Y-m', _G('timestamp'));
        }
        if($subdir) {
            $dirs = explode(DS, $subdir);
            foreach ($dirs as $val) {
                $path .= DS . $val;
                if(!@is_dir($path)) {
                    if(!@mkdir($path, 0777)) {
                        show_error(sprintf('global_mkdir_no_access'), str_replace(MUDDER_ROOT, '', $path));
                    }
                }
            }
        }

        $fileinfo = pathinfo($this->_file['name']);
        $ext = strtolower($fileinfo["extension"]); 

        if(!$this->lock_name) {
            PHP_VERSION < '4.2.0' && srand();
            $rand = rand(1, 100);
            $name = $rand . '_' . _G('timestamp') . '.' . $ext;
            unset($rand);
        } else {
            $name = $this->lock_name . '.' . $ext;
        }
        $sorcuefile = $path . DS . $name;

        if (move_uploaded_file($this->_file['tmp_name'], $sorcuefile)) {
            $this->filename = $name;
            $this->path = str_replace(MUDDER_ROOT, '', $path);
            $this->src = str_replace(DS, '/', $this->loacl_path);
            $this->delete_tmpfile();
            return TRUE;
        } else {
            redirect('global_upload_lost');
        }
    }

    function delete_tmpfile() {
        @unlink(str_replace("\\\\", "\\", $this->_file['tmp_name']));
    }

    function delete_file() {
        @unlink($this->path.'/'.$this->filename);
    }

    function is_upfile($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!$ext) return FALSE;
        return in_array($ext, $this->limit_ext);
    }

    function _check() {
        if(!is_uploaded_file($this->_file['tmp_name'])) {
            redirect('global_upload_unkown');
        } elseif(!$this->is_upfile($this->_file['name'])) {
            @unlink($this->_file['tmp_name']);
            redirect(lang('global_upload_type_invalid', implode('，', $this->limit_ext)));
//        } if(filesize($this->_file['tmp_name']) != $this->_file['size']) {
//          @unlink($this->_file['tmp_name']);
//        	redirect('global_upload_size_invalid');
        } elseif($this->_file['size'] > $this->max_size) {
            @unlink($this->_file['tmp_name']);
            redirect(sprintf(lang('global_upload_szie_thraw'), floor($this->max_size/1024) ,'KB'));
        }
        return TRUE;
    }

}