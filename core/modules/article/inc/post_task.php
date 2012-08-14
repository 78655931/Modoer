<?php
/**
 * 文章类任务（任务类开发样本）
 *
 * @author moufer<moufer@163.com>
 * @copyright (c)2001-2009 Moufersoft
 * @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class task_article_post {

    var $flag = 'article:post';
    var $title = 'article_task_post_title';
    var $copyright = 'MouferStudio';
    var $version = '1.0';

    var $info = array();
    var $install = false;
    var $ttid = 0;

    function __construct() {
        $this->title = lang($this->title);
    }

    function form($cfg) {
        global $_G;
        $result = array();
        $result[] = array(
            'title' => '文章发布数量',
            'des' => '任务要求会员发布文章的数量（审核通过），默认为 1 条',
            'content' => form_input('config[num]',$cfg['num']>0?$cfg['num']:1,'txtbox4'),
        );
        return $result; 
    }

    function form_post($cfg) { 
        if(!$cfg['num'] || !is_numeric($cfg['num']) || $cfg['num'] < 1) redirect('完成条件错误：未填写一个有效的文章发布数量。');
        return true; 
    }

    function progress(& $task_detail) {
        global $_G;
        $db =& $_G['db'];
        $taskid = $task_detail['taskid'];
        $db->from('dbpre_mytask');
        $db->where('uid',$_G['user']->uid);
        $db->where('taskid',$taskid);
        $mytask = $db->get_one();
        if(!$mytask) return 0;

        $cfg = @unserialize($task_detail['config']);
        if(!$cfg) return 0;
        if(!$cfg['num'] || $cfg['num'] < 1) $cfg['num'] = 1;

        $db->from('dbpre_articles');
        $db->where('uid',$_G['user']->uid);
        $db->where('status', 1);
        $db->where_more('dateline', $mytask['applytime']);
        if(!$total = $db->count()) return 0;
        $min = min($cfg['num'], $total);
        return round($min / $cfg['num'] * 100);
    }

    function apply($taskid) {}

    function delete($taskid) {}

    function install() {}

    function unstall() {}
}
?>