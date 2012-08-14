<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class hook_modoer extends ms_base {

    function __construct() {
        parent::__construct();
    }

    function init_begin() {
        $this->global['cfg']['siteurl'] = $this->global['cfg']['siteurl'] . 
            (substr($this->global['cfg']['siteurl'] , -1) != '/' ? '/' : '');
        if($this->global['cfg']['siteclose'] && !defined('IN_ADMIN') && $_GET['act'] != 'seccode') {
            show_error($this->global['cfg']['closenote']);
        }
        if($this->global['cfg']['useripaccess'] && !check_ipaccess($this->global['cfg']['useripaccess'])) {
            show_error(lang('global_ip_without_list'));
        }
        if($this->global['cfg']['ban_ip'] && check_ipaccess($this->global['cfg']['ban_ip'])) {
            show_error(lang('global_ip_not_have_access'));
        }

        //URL rewrite
        if($this->global['cfg']['rewrite']) {
            $this->global['rewriter'] =& $this->global['loader']->lib('urlrewriter');
            $this->global['rewriter']->set_mod($this->global['cfg']['rewrite_mod']);
            $this->global['rewriter']->set_domain_mod($this->global['cfg']['city_sldomain']);
            $this->global['rewriter']->hide_index = $this->global['cfg']['rewrite_hide_index'];
            if(isset($_GET['Rewrite'])) {
                $this->global['rewriter']->html_recover($_GET['Rewrite']);
            } elseif(isset($_GET['Pathinfo'])) {
                $this->global['rewriter']->pathinfo_recover($_GET['Pathinfo']);
            } elseif($this->global['index_url_rewrite']) {
                $this->global['rewriter']->index_recover();
            }
        }
    }

    function init_end() {

    }

    function footer() {
    }

}
?>