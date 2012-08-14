<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

// 允许的操作行为
$act = $_GET['act'];
$in_ajax = 1;
$allowacs = array( 'user_effect' );
$loginacs = array( 'user_effect' );

$act = empty($act) || !in_array($act, $allowacs) ? '' : $act;

if(!$act) {

    redirect('global_op_unkown');

} elseif(in_array($act, $loginacs) && !$user->isLogin) {

    $_G['forward'] = $_G['web']['referer'] ? $_G['web']['referer'] : $_G['cfg']['siteurl'];

    redirect('member_not_login');

}

switch($act) {

case 'user_effect':

    $M =& $_G['loader']->model(MOD_LFAG.':membereffect');

    $id = (int) $_POST['id'];
    $idtype = $_POST['idtype'];
    $effect = $_POST['effect'];
    $this->check_post($id, $idtype, $effect);

    switch($_POST['effectaction']) {
    case 'total':
        echo $M->count($id, $idtype, $effect);
        break;
    case 'getusers':
        $query = $M->get_member($id, $idtype, $effect);
        $split = '';
        if($query) {
            while($value = $db->fetch_array($query)) {
                echo $split.'<a href="'.url("space/index/uid/$value[uid]", "", 1) . '">'.$value['username'].'</a>';
                $split = ',';
            }
        }
        break;
    default:
        $M->save($id, $idtype, $effect);
        echo $M->count($id, $idtype, $effect);
        break;
    }

    output();

    break;

default:

}
?>