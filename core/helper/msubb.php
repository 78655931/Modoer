<?php
class msubb {

	function clear($content) {
		return preg_replace("/\[\/\S+?\/\]/", "", $content);
	}
	
	function pares($content, $detail = null) {
        $content = msubb::smilies($content);
        $content = msubb::username($content);
        $content = msubb::image($content, $detail['pictures']);
        return nl2br($content);
    }

    function smilies($content) {
    	return preg_replace("/\[\/([0-9]{1,2})\/\]/", "<img src=\"".URLROOT."/static/images/smilies/\\1.gif\" />", $content);
    }

    function username($content) {
    	$username_arr = msubb::get_username($content);
    	if(!$username_arr) return $content;
    	$search = $replace = array();
    	foreach ($username_arr as $k => $value) {
    		$search[$k] = "[/@$value/]";
    		$replace[$k] = "<a href='".url("space/index/username/$value")."' target=\"_blank\">@$value</a>";
    	}
    	return str_replace($search, $replace, $content);
    }

    function image($content, $pictures) {
        $pictures = is_serialized($pictures) ? unserialize($pictures) : '';
        if(!$pictures) return $content;
        $img_arr = msubb::get_image($content);
        if(!$img_arr) return $content;
        $search = $replace = array();
        foreach ($img_arr as $k => $value) {
            $imgsrc = $pictures[$value];
            if(!$imgsrc) continue;
            $search[$k] = "[/img:$value/]";
            $replace[$k] = "<img src=\"".URLROOT."/$imgsrc\" />";
        }
        return str_replace($search, $replace, $content);
    }

    function get_username($content) {
    	 if ( ! preg_match_all('%\[/@(\S+?)/\]%', $content, $matches)) return;
    	 return $matches[1];
    }

    function get_image($content) {
         if ( ! preg_match_all('%\[/img:(\S+?)/\]%', $content, $matches)) return;
         return $matches[1];
    }
}
?>