<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

$op = $_POST['op'] ? $_POST['op'] : $_GET['op'];
$I =& $_G['loader']->model(MOD_FLAG.':subject');
$mymenu = 'menu';
switch($op) {
    default:
        $pid = _get('pid',null,MF_INT_KEY);
        $query = $_G['db']->query("SELECT pid,count(pid) as count FROM {$_G[dns][dbpre]}subject s WHERE cuid = $user->uid Group by pid order by count");
        if($query) {
            while ($v = $query->fetch_array()) {
                $mycate[$v['pid']] = $v['count'];
            }
            if(!$pid && !isset($mycate[$pid])) {
                $pid = key($mycate);
            }
        }

        if($pid > 0) {

            $category = $I->variable('category');
            if($category[$pid]['pid']) redirect('item_cat_invalid');

            $modelid = $category[$pid]['modelid'];
            $model = $I->variable('model_' . $modelid);
            $fields = $I->variable('field_' . $modelid);

            $varname = array('catid' => 'cattitle', 'name' => 'title', 'subname' => 'subtitle');
            foreach ($fields as $value) {
                if($var = $varname[$value['fieldname']]) {
                    $$var = $value['title'];  
                }
            }

            $access_edit = $category[$pid]['config']['allow_edit_subject'] && $user->check_access('item_allow_edit_subject', $I, false);

            $where['pid'] = (int) $pid;
            $where['cuid'] = $user->uid;
            $orderby = array('addtime', 'DESC');

            $select = 'sid,city_id,aid,pid,catid,name,subname,status,reviews,pictures,guestbooks,addtime,owner,favorites';

            $start = get_start($_GET['page'], $offset = 20);
            list($total, $list) = $I->find($select, $where, array('addtime'=>'DESC'), $start, $offset);
            $multipage = multi($total, $offset, $_GET['page'], url("item/member/ac/$ac/pid/$pid/page/_PAGE_"));

        }

        $path_title = lang('item_title_m_subject');
        $tplname = 'subject_list';
}
?>