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
$F =& $_G['loader']->model('item:field');

$modelid = _input('modelid', null, MF_INT_KEY);
$fieldid = _input('fieldid', null, MF_INT_KEY);
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