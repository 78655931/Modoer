<?php
/**
* 文件上传类
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_download_file {

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

    function __construct($url, $exts='') { //PHP 5
    	if(empty($url)) {
    	    redirect('global_upload_error_4');
    	}
		$this->_file = $url;
        if(!$exts) $exts = 'rar zip 7z txt';
        $this->set_ext($exts);
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

    function download($folder, $subdir = 'MONTH') {
        global $_G;
		
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
        } else {
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
		
        $fileinfo = pathinfo($this->_file);
        $ext = strtolower($fileinfo["extension"]); 
        if(!$this->lock_name) {
            $rand = rand(1, 100);
            $name = $rand . '_' . _G('timestamp') . '.' . $ext;
            unset($rand);
        } else {
            $name = $this->lock_name . '.' . $ext;
        }
		$savefile = $path . DS . $name;
		//download
		$SP =& $_G['loader']->lib('snoopy');
		$SP->fetch($this->_file);
		if($SP->results !="") {
			$handle = @fopen($savefile, 'w');
			@fwrite($handle, $SP->results);
			@fclose($handle);
            $this->filename = $name;
            $this->path = str_replace(MUDDER_ROOT, '', $path);
			return TRUE;
		} else {
			redirect('global_upload_lost');
		}
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
        if(!$this->is_upfile($this->_file)) {
            redirect(lang('global_upload_type_invalid', implode('，', $this->limit_ext)."($this->_file)"));
        }
        return TRUE;
    }

}