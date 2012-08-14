<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class ms_cache {

    var $read_list = null;
    var $write_list = null;

    // check cache file
    function check($cachefile, $life = -1) {
        if(is_file($cachefile)) {
            return $life < 0 || _G('timestamp') - @filemtime($cachefile) < $life;
        } else {
            return FALSE;
        }
    }

    function read($cachefile, $mode = 'i') {
        if(!is_file($cachefile)) return false;
        if(DEBUG) $this->read_list[] = $cachefile;
        return $mode == 'i' ? @include $cachefile : @file_get_contents($cachefile);
    }

    function write($name, $data, $model=NULL, $type='return', $dir = '') {
        $cachedir = $dir ? MUDDER_ROOT . ($dir . DS) : MUDDER_CACHE;
        if(!$model) $model = 'modoer';
        $filename = $type == 'js' ? ($name . '.js') : ($model . '_' . $name . '.php');
        if(!is_dir($cachedir)) {
            if(@mkdir($cachedir, 0777)) {
                show_error(sprintf(lang('global_mkdir_no_access'), str_replace(MUDDER_ROOT,'./',$cachedir)));
            }
        }

        $file = $cachedir . $filename;
        if($fp = @fopen($file, 'wb')) {
            if($type=='js') {
                @fwrite($fp, "//Modoer cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp')) . "\r\n\r\n" . $data . "\r\n");
            } elseif($type=='return') {
                @fwrite($fp, "<?php\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\nreturn " . $data . "; \r\n?>");
            } else {
                @fwrite($fp, "<?php\r\n//Modoer cache file\r\n//Created on ".date('Y-m-d H:i:s', _G('timestamp'))."\r\n\r\n!defined('IN_MUDDER') && exit('Access Denied');\r\n\r\n" . $data . "\r\n\r\n?>");
            }
            @fclose($fp);
            @chmod($file, 0777);
        } else {
            show_error(lang('global_file_not_exist', str_replace(MUDDER_ROOT, './', $file)));
        }

        if(DEBUG) $this->write_list[] = $file;
    }

    /**
     * ÏÔÊ¾»º´æÐ´Èë¼ÇÂ¼ 
     *
     */
	function debug_print() {
		global $_G;
        if(!$this->load_files) return;
		$style = 'margin:5px auto;width:95%;line-height:18px;font-family:Courier New;text-align:left;';
		$content ='<div style="'.$style.'">';
		$content .= '<ul style="margin:0;padding:0 0 5px;background:#eee;border-width:1px; border-style:solid;border-color:#CCC;list-style:none;">';
		$content .='<h3 style="font-size:16px;border-bottom:1px solid #FF9900;margin:5px;padding:0 0 5px;">Write Cache Files</h3>';
		foreach($this->write_list as $val) {
			$content .= '<li style="padding:1px 8px;font-size:12px;">' . $val . '</li>';
		}
		$content .= '</ul></div>';

		return $content;
	}
}

?>