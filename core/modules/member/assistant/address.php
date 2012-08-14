<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = _input('op',null,MF_TEXT);
$ADD =& $_G['loader']->model('member:address');

switch ($op) {
    case 'save':
        $post = $ADD->get_post($_POST['address']);
        $id  = _post('id', null, MF_INT_KEY);
        $result = $ADD->save($post, $id);
        if($result>0) {
            set_cookie('lastmessage',lang('global_op_succeed'));
            location(url("member/index/ac/$ac"));
        } else {
            $address =& $post;
            if($id>0) $address['id'] = $id;
        }
        break;
    case 'edit':
        $id = _get('id', null, MF_INT_KEY);
        $address = $ADD->read($id);
        if(!$address) redirect('对不起，你编辑的信息不存在。');
        break;
    case 'delete':
        $id = _post('id', null, MF_INT_KEY);
        $ADD->delete($id);
        echo 'OK';
        output();
        break;
    default:
        $lastmessage = _cookie('lastmessage');
        del_cookie('lastmessage');
        break;
}
?>