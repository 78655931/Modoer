<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'coupon' => array(
        'name' => lang('coupon_hook_comment_name'),
        'table_name' => 'dbpre_coupons',
        'key_name' => 'couponid',
        'title_name' => 'subject',
        'grade_name' => 'grade',
        'total_name' => 'comments',
        'detail_url' => 'coupon/detail/id/_ID_',
    ),
);
?>