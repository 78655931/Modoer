<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$fieldtype = $_POST['fieldtype'];
if(empty($fieldtype)) {
    echo lang('admincp_fieldtype_unkown');
    exit();
}
$F =& $_G['loader']->model(MOD_FLAG.':field');

$fieldid = $_POST['fieldid'] = (int) $_POST['fieldid'];
if($edit = $fieldid > 0) {
    $result = $F->read($fieldid);
    if(!$result) {
        echo lang('admincp_fieldtype_invalid');
        exit();
    }
    $t_cfg = unserialize($result['config']);
}

$settingfile = "model".DS."fields".DS."setting".DS.$fieldtype.".inc.php";
if(file_exists(MOD_ROOT . $settingfile)) {
    include MOD_ROOT . $settingfile;
} elseif(file_exists(MUDDER_CORE . $settingfile)) {
    include MUDDER_CORE . $settingfile;
} else {
    echo ''; exit;
}
?>