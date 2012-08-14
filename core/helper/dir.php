<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
function dir_remove($dir) {
   if(substr($path, -1, 1) != "/") {
       $path .= "/";
   }
   $normal_files = glob($path . "*");
   $hidden_files = glob($path . "\.?*");
   $all_files = array_merge($normal_files, $hidden_files);

   foreach ($all_files as $file) {
       if(preg_match("/(\.|\.\.)$/", $file)) {
           continue;
       }
       if(is_file($file) === TRUE) {
           @unlink($file);
       } elseif (is_dir($file) === TRUE) {
           dir_remove($file);
       }
   }
   if(is_dir($path) === TRUE) {
       rmdir($path);
   }
}

function dir_create($dirpath) {
    if(is_file($dirpath)) $dirpath = dirname($dirpath);
    if(is_dir($dirpath)) return true;
    $dirpath = str_replace(MUDDER_ROOT, '', $dirpath);
    $dirs = explode(DS, $dirpath);
    if(!$dirs) return;
    $path = MUDDER_ROOT;
    foreach($dirs as $d) {
        if(!$d) continue;
        $path .= $d . DS;
        if(!is_dir($path))
            if(!mkdir($path,'0777')) 
                show_error(lang('global_mkdir_no_access', $path));
    }
    return true;
}
?>