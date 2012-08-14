<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ucenter extends ms_base {

    var $cfgfile = '';
    var $use_bak = false;

    var $setting = array();
    var $apps = array();
    var $my = array();

    function __construct() {
        parent::__construct();
        $this->cfgfile = MUDDER_DATA . 'config_uc.php';
        @include MUDDER_ROOT . 'uc_client' . DS . 'data' . DS . 'cache' . DS . 'settings.php';
        $this->settting = $_CACHE['settings'];
        $this->apps = @include MUDDER_ROOT . 'uc_client' . DS . 'data' . DS . 'cache' . DS . 'apps.php';
        $this->apps = $_CACHE['apps'];
        if($this->apps && isset($this->apps[UC_APPID])) {
            $this->my =& $this->apps[UC_APPID];
        }
    }

    function msm_ucenter() {
        $this->__construct();
    }

    //对模块配置和uc配置进行检测和处理
    function config(&$modcfg, &$uc) {
        if($modcfg['uc_enable']) {
            if($uc['connect']) {
                $uc['dbpw'] = $uc['dbpw'] == '********' ? UC_DBPW : $uc['dbpw'];
                $uc_db_link = @mysql_connect($uc['dbhost'], $uc['dbuser'], $uc['dbpw'], 1);
                if(!$uc_db_link) {
                    redirect('ucentercp_dbconnect_error');
                } elseif(!@mysql_select_db($uc['dbname'], $uc_db_link)) {
                    redirect(lang('ucentercp_dbselect_error', $uc['dbname']));
                } elseif(!$this->table_exists($uc_db_link, $uc['dbname'], $uc['dbtablepre'])) {
                    redirect('ucentercp_dbpre_error');
                }
                mysql_close($uc_db_link);
            }
            $this->write_uc($uc);
        }
    }

    //写入uc配置到网站配置文件
    function write_uc(&$uc) {

        //检测可写
        if(!$this->is_write()) redirect(lang('global_file_not_exist','data/config_uc.php'));
        $this->check_uccfg($uc);

        //先备份
        if($this->use_bak) {
            @copy($this->cfgfile, MUDDER_DATA . 'config_uc_bak.php');
        }

        foreach($uc as $k => $v) {
            $v = str_replace("'", "\\'", $v);
            $uc[$k] = $v;
        }

        $content = file_get_contents($this->cfgfile);
        $content = trim($content);
        if(substr($content, -2) == '?>') $content = substr($content, 0, -2);

        $connect = '';
        if($uc['connect']) {
            $connect = 'mysql';
            $content = $this->replace($content, "/define\('UC_DBHOST',\s*'.*?'\);/i", "define('UC_DBHOST', '".$uc['dbhost']."');");
            $content = $this->replace($content, "/define\('UC_DBUSER',\s*'.*?'\);/i", "define('UC_DBUSER', '".$uc['dbuser']."');");
            $content = $this->replace($content, "/define\('UC_DBPW',\s*'.*?'\);/i", "define('UC_DBPW', '".$uc['dbpw']."');");
            $content = $this->replace($content, "/define\('UC_DBNAME',\s*'.*?'\);/i", "define('UC_DBNAME', '".$uc['dbname']."');");
            $content = $this->replace($content, "/define\('UC_DBCHARSET',\s*'.*?'\);/i", "define('UC_DBCHARSET', '".$uc['dbcharset']."');");
            $content = $this->replace($content, "/define\('UC_DBTABLEPRE',\s*'.*?'\);/i", "define('UC_DBTABLEPRE', '`".$uc['dbname'].'`.'.$uc['dbtablepre']."');");
        }
        $content = $this->replace($content, "/define\('UC_CONNECT',\s*'.*?'\);/i", "define('UC_CONNECT', '$connect');");
        $content = $this->replace($content, "/define\('UC_KEY',\s*'.*?'\);/i", "define('UC_KEY', '".$uc['key']."');");
        $content = $this->replace($content, "/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '".$uc['api']."');");
        $content = $this->replace($content, "/define\('UC_APPID',\s*'.*?'\);/i", "define('UC_APPID', '".$uc['appid']."');");
        $content = $this->replace($content, "/define\('UC_IP',\s*'.*?'\);/i", "define('UC_IP', '".$uc['ip']."');");

        @file_put_contents($this->cfgfile, $content);
        unset($content);
    }

    //检测uc数据是否正确
    function check_uccfg(&$uc) {
        if($uc['ip'] && !preg_match('/^[0-9\.]+$/', $uc['ip'])) redirect('ucentercp_invalid_ip');
    }

    //替换配置参数
    function replace($s, $find, $replace) {
        if(preg_match($find, $s)) {
            $s = preg_replace($find, $replace, $s);
        } else {
            $s .= "\r\n" . $replace;
        }
        return $s;
    }

    //判断ucenter表是否存在
    function table_exists($link, $dbname, $dbpre) {
        $table = $dbpre . 'members';
        $q = mysql_query("SHOW TABLES FROM `" . $dbname . "` LIKE '" . $table . "'");
        if(!$q) return FALSE;
        $result = mysql_fetch_array($q, MYSQL_ASSOC);
        mysql_free_result($q);
        return !empty($result);
    }

    //检测配置文件
    function is_write() {
        return is__writable($this->cfgfile);
    }
}
?>