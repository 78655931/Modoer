<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if($_GET[sid]) {
    location(url("review/member/ac/add/type/item_subject/id/$_GET[sid]"));//member-ac-review_add
} else {
    location(url("review/member/ac/add/type"));
}
?>