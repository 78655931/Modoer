<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
* @version $Id article.inc.php 1 2008-08-23 14:19 $
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

require_once(MUDDER_ROOT.'./'.$_modules['ucenter']['directory'].'/include/inc_common.php');
require_once(MOD_ROOT.'./include/fun_common.php');
if(preg_match("/^[a-z\_\.]+$/", $file)) {
    require_once(MOD_ROOT.'./admin/'.$file.'.inc.php');
} else {
    cpmsg('н╢ж╙╣д╡ывВ!', 'stop');
}
?>