<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');

$I =& $_G['loader']->model(MOD_FLAG.':subject');
$op = isset($_POST['op']) ? $_POST['op'] : $_GET['op'];
switch ($op) {
     case 'checkup':
        $I->checkup($_POST['sids']);
        redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
        break;
     case 'delete':
        $I->delete($_POST['sids']);
        redirect('global_op_succeed', cpurl($module, $act,'',array('pid' => $_GET['pid'])));
        break;
     default:
         /*
        $pid = isset($_GET['pid']) ? $_GET['pid'] : $MOD['pid'];
		(!$pid || !$I->get_category($pid)) and redirect('item_empty_default_pid');
        $category = $I->variable('category');
		$modelid = $category[$pid]['modelid'];
        $model = $I->variable('model_' . $modelid);
        */
        $where = array();
        //$where['pid'] = (int) $pid;
        if(!$admin->is_founder) $where['city_id'] = $_CITY['aid'];
        $where['status'] = 0;
        $select = 'sid,city_id,pid,catid,name,subname,addtime,cuid,creator,status';
        $start = get_start($_GET['page'], $offset = 20);
        list($total, $list) = $I->find($select, $where, array('addtime'=>'DESC'), $start, $offset);
        $multipage = multi($total, $offset, $_GET['page'], cpurl($module, $act, '', array('pactid' => $pactid)));

        /*
        $fields = $I->variable('field_' . $modelid);
        $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
        foreach ($fields as $value) {
            if($var = $varname[$value['fieldname']]) {
                $$var = $value['title'];  
            }
        }
        */
        
        $plist = $category = array();
        $category = $I->variable('category');
        foreach ($category as $key => $value) {
            if(!$value['pid']) {
                $plist[$value['catid']] = $value;
            }
        }
        
        unset($varname, $var, $key, $value);
        
        $admin->tplname = cptpl('subject_check', MOD_FLAG);
 }