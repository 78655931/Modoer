<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'item_subject' => array(
        'name' => lang('item_hook_review_name'),
        'flag' => 'item',
        'table_name' => 'dbpre_subject',
        'key_name' => 'sid',
        'title_name' => 'name',
        'total_name' => 'reviews',
        'model_name' => 'item:subject',
        'side_block_name' => 'item_subject_side',
        'detail_url' => 'item/detail/id/_ID_',
    ),
);
?>