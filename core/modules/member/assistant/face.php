<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

if($_POST['dosubmit']) {
    
    $face_src = str_replace(array(URLROOT . '/uploads/faces/', '/'), array('', DS), get_face($user->uid, TRUE, FALSE));
    $_G['loader']->lib('upload_image',NULL,FALSE);
    $img = new ms_upload_image('face','jpg');
    $img->set_max_size = '2048'; // 100 KB
    $img->lock_name = str_replace('.jpg', '', pathinfo($face_src, PATHINFO_BASENAME));
    $img->upload('faces', pathinfo($face_src, PATHINFO_DIRNAME), array('width' => 48, 'height' => 48));
    redirect('global_op_succeed', url('member/index/ac/face'));
}