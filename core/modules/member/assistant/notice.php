<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$isread = _get('isread', 0, MF_INT);
$N =& $_G['loader']->model('member:notice');

$total = $N->get_count($isread);
if($total>0) {
    $offset = 20;
    $start = get_start($_GET['page'], $offset);
    $list = $N->get_list($isread, $start, $offset);
}
?>