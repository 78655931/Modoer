<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if(!empty($_FILES['picture']['name'])) {

    $_G['loader']->lib('upload_image', NULL, FALSE);
    $img = new ms_upload_image('picture', $_G['cfg']['picture_ext']);
    $img->set_max_size($_G['cfg']['picture_upload_size']);
    $img->set_ext($_G['cfg']['picture_ext']);
    $img->upload('temp', null);
    $picture = str_replace(DS, '/', $img->path . '/' . $img->filename);
    if($_POST['in_iframe']) {
        echo fetch_iframe($picture);
    } else {
        echo $picture;
    }
    exit;

} else {

    redirect('没有文件被上传。');

}
?>