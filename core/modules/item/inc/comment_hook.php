<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

return array (
    'album' => array(
        'name' => lang('item_album_hook_comment_name'),
        'table_name' => 'dbpre_album',
        'key_name' => 'albumid',
        'title_name' => 'name',
        //'grade_name' => 'grade',
        'total_name' => 'comments',
        'detail_url' => 'item/album/id/_ID_',
    ),
);
?>