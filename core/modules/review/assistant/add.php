<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$R =& $_G['loader']->model(':review');

if(!$_POST['dosubmit']) {

    //点评数量权限验证
    $user->check_access('review_num', $R);

    if($idtype = _get('type',null,MF_TEXT)) {
        if(!$id = _get('id',0,MF_INT_KEY)) redirect(lang('global_sql_keyid_invalid','id'));
        $typeinfo = $R->get_typeinfo($idtype);
        $OBJ =& $_G['loader']->model($typeinfo['model_name']);
        if(!$object = $OBJ->read($id)) redirect('review_object_empty');
        $subject = $OBJ->get_subject($object);
        $review_access = $OBJ->review_access($object ? $object : null);
        if($review_access['code'] != 1) {
            $R->redirect_access($review_access);
        }
    }

    if($object) {
        $config = $OBJ->get_review_config($object);
        $pid = $OBJ->get_obj_pid($object);
		$rogid = $config['review_opt_gid'];
        //判断是否允许游客点评
        if(!$user->isLogin && !$config['guest_review']) {
            $forward = url("review/member/ac/add/type/item_subject/id/$sid");
            if(defined('IN_AJAX')) {
                dialog(lang('global_op_title'), '', 'login');
            } else {
                location(url('memner/login/forward/'.base64_encode($forward)));
                exit;
            }
        }
        $review_opts = $R->variable('opt_' . $rogid);
        $taggroups = $OBJ->variable('taggroup','item');
    }

    $_G['loader']->helper('form');
	$tplname = 'review_save';

} else {

    if(!$user->isLogin && $MOD['seccode_review_guest'] || $user->isLogin && $MOD['seccode_review']) {
        check_seccode($_POST['seccode']);
    }
    $post = $R->get_post($_POST['review'], FALSE);
    $rid = $R->save($post);

    redirect(RETURN_EVENT_ID, get_forward(url('item/member/ac/m_review'),1));

}
?>