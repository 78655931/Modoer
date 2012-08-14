<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$M =& $_G['loader']->model('menu');
$op = _input('op');

switch($op) {
    case 'get_child';
        $parentid = (int) $_POST['parentid'];
        if($parentid < 1) {
            echo '';
        } else {
            if($list = $M->read_all($parentid, 1)) {
                foreach($list as $val) {
                    echo $val['menuid']."=".$val['title']."\r\n";
                }
            } else {
                echo '';
            }
        }
        break;
    case 'add':
    case 'edit':
        if(!$_POST['dosubmit']) {
            $extra = array('onclick'=>"select_isfolder(this.value);");
            if($op=='edit') {
                $menuid = (int) $_GET['menuid'];
                if($menuid < 1) redirect(sprintf(lang('global_sql_keyid_invalid'),'menuid'));
                if(!$menu = $M->read($menuid)) redirect('global_op_nothing');
                $parentid = $menu['parentid'];
                $extra['disabled'] = 'disabled';
            } else {
                $parentid = (int) $_GET['parentid'];
            }
            $menulist = $M->read_child_list();
            $select_menu = $M->create_leve_option($parentid);
            $admin->tplname = cptpl('menu_save');
        } else {
            if($op=='edit') {
                $menuid = (int) $_POST['menuid'];
                if($menuid < 1) redirect(sprintf(lang('global_sql_keyid_invalid'),'menuid'));
            }
            $parentid = $_POST['menu']['parentid'];
            $M->save($_POST['menu'], $menuid);
            redirect('global_op_succeed', cpurl($module, $act, 'list', array('parentid'=>$parentid)));
        }
        break;
    case 'delete':
        if(!$_POST['dosubmit']) {
            $M->delete($_GET['menuid']);
        } else {
            $M->delete($_POST['menuids']);
        }
        redirect('global_op_succeed', cpurl($module, $act));
        break;
    default:
        $op = 'list';
        if($_POST['dosubmit']) {
            $M->update($_POST['menus']);
            redirect('global_op_succeed', cpurl($module, $act, 'list', array('parentid'=>$_POST['parentid'])));
        } else {
            $parentid = $_GET['parentid'] ? $_GET['parentid'] : '';
            $list = $M->read_all($parentid);
            if($parentid) {
                $goto_info = $M->read($parentid);
                $goto_parentid = $goto_info['parentid'];
            }
            $admin->tplname = cptpl('menu_list');
        }
}
?>