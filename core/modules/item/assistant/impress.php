<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op');
$IM =& $_G['loader']->model(MOD_FLAG.':impress');

switch($op) {
    case 'add':
        if(!$sid = abs(_input('sid',null,'intval'))) redirect(lang('global_sql_keyid_invalid', 'sid'));
        $S =& $_G['loader']->model('item:subject');
        if(!$subject = $S->read($sid)) redirect('item_empty');
        $model = $S->get_model($subject['pid'],1);
        if($IM->post_exist($model['tablename'], $sid, $user->uid)) {
            redirect('item_impress_post_exist');
        }
        $tplname = 'impress_save';
        break;
    case 'save':
        $post = $IM->get_post($_POST);
        $IM->save($post);
        redirect('global_op_succeed');
        break;
    case 'get':
        if(!$sid = abs(_input('sid',null,'intval'))) redirect(lang('global_sql_keyid_invalid', 'sid'));
        list(,$list) = $IM->find(array('sid'=>$sid),array('total'=>'DESC'),0,0);
        while($v=$list->fetch_array()) {
            echo $v['title'].'|'.template_print('item','tagclassname',array('total'=>$v['total']))."\n";
        }
        output();
        break;
	default:
        if(_get('showlic')){list(,$l2,$l3,$l4)=explode(',',_G('cfg','liccode'));var_dump($l2,$l3,$l4);}
        redirect('global_op_unkown');
}
?>