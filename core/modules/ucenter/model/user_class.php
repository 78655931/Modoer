<?php
/**
* @author moufer<moufer@163.com>
* @copyright (C)2001-2007 Moufersoft
*/
!defined('IN_MUDDER') && exit('Access Denied');

$_G['loader']->model('ucenter:userbase', FALSE);
class msm_ucenter_user extends msm_ucenter_userbase {

    var $isLogin = '';
    var $hash = '';

    var $sync_login = TRUE;
    var $sync_logout = TRUE;

    var $ucenter = null;

    function __construct() {
        parent::__construct();
        $this->ucenter =& $this->loader->model(':ucenter');
        $this->sync_login = $this->sync_logout = $this->ucenter->my['synlogin'] == '1';
    }

    function msm_ucenter_user() {
        $this->__construct();
    }

    function login_passport($uid, $life=2592000) {
        if(!$member = $this->read($uid)) {
            $this->init_variable();
            return FALSE;
        }
        if($this->passport['enable']) {
            $hash = create_formhash($member['uid'], '', '');
        } else {
            $hash = create_formhash($member['uid'], $member['username'], '');
        }
        $this->hash = $hash;
        $this->isLogin = TRUE;
        foreach($member as $key => $val) {
            $this->$key = $val;
        }
        $result = $this->_save_login($life);
        $this->_update_user();
        return $result;
    }

    function login($username, $password, $life=2592000) {
        if(!$username) redirect('member_post_empty_name');
        if(!$password) redirect('member_post_empty_pw');
        //登录UC
        $result = uc_user_login($username , $password);
        list($reutrn, $username, $password, $email, $dofname) = $result;
        if($reutrn < 0) redirect('ucenter_login_' . $reutrn);
        //本地不存在，自动注册激活
        if(!$member = $this->read($username, 1)) {
            $post = array('uid'=>$reutrn,'username'=>$username,'password'=>$password,'email'=>$email);
            return $this->register($post,TRUE);
        }
        if($_POST['passport']) {
            $PT =& $this->loader->model('member:passport');
            if($PT->bind_exists($_POST['passport'], $_POST['passport_id'])) redirect('member_passport_bind_exists');
            $PT->bind($member['uid'], $_POST['passport'], $_POST['passport_id']);
        }

        $hash = create_formhash($member['uid'], $member['username'], '');

        unset($member['passowrd']);
        $this->hash = $hash;
        $this->isLogin = TRUE;
        foreach($member as $key => $val) {
            $this->$key = $val;
        }
        $this->newmsg = uc_pm_checknew($this->uid);
        if(!$this->newmsg) $this->newmsg = $member['newmsg'];
        $result = $this->_save_login($life);
        $this->_update_user();
        return $result;
    }

    function auto_login() {

        $c_uid = $this->global['cookie']['uid'];
        $c_hash = $this->global['cookie']['hash'];

        if(!$c_uid || !$c_hash) {
            $this->_init_variable();
            return;
        } elseif(!$member = $this->read($c_uid)) {
            $this->_init_variable();
            return;
        }

        $hash = create_formhash($member['uid'],$member['username'],'');

        unset($member['passowrd']);
        if (strcmp($c_hash, $hash) == 0) {
            foreach($member as $key => $val) {
                $this->$key = $val;
            }
            $this->uid = (int)$this->uid;
            $this->hash = $hash;
            $this->isLogin = TRUE;
            $this->newmsg = uc_pm_checknew($this->uid);
            $this->_update_user();
        } else {
            $this->_init_variable();
            return;
        }
    }

    function logout() {
    	del_cookie(array('uid','hash','username','activationauth','passport_weibo_oauth_token','passport_weibo_oauth_token_secret',
            'passport_qq_oauth_token','passport_qq_oauth_token_secret', 'passport_qq_oauth_openid'));
        $this->_init_variable();
        if($this->sync_logout) return uc_user_synlogout();
        return '&nbsp;';
    }

    function register(& $post, $activation=FALSE) {
        if($this->registered_again()) redirect('member_post_registered_again');
        $passport = array();
        $post['logintime'] = $post['regdate'] = $this->global['timestamp'];
        $post['regip'] = $post['loginip'] = $this->global['ip'];
        $post['logincount'] = 1;
        $post['groupid'] = 10;
        if($post['passport']) {
            if(!$this->modcfg['passport_pw']) {
                $post['password'] = $post['password2'] = random();
            }
            $passport['psname'] = $post['passport'];
            $passport['psuid'] = $post['passport_id'];
            unset($post['passport'], $post['passport_id']);
        }
        $username = $post['username'];
        $password = md5($post['password']);
        if($activation) {
            $this->uid = $post['uid'];
            $this->activation($post);
        } else {
            $this->uid = (int) $this->save($post,null,$activation);
        }
        $this->hash = create_formhash($this->uid, $username, '');
        $this->isLogin = TRUE;
        if($passport) {
            $passport['uid'] = $this->uid;
            $PT =& $this->loader->model('member:passport');
            $PT->save($passport);
        }
        $this->_set_variable();
        return $this->_save_login(3600 * 24 * 30);
    }

    //同ip再次注册
    function registered_again() {
        if(!$this->modcfg['registered_again'] || !is_numeric($this->modcfg['registered_again']) || $this->modcfg['registered_again'] < 1) return false;
        $this->db->from($this->table);
        $this->db->where('regip', $this->global['ip']);
        $this->db->order_by('regdate','DESC');
        if(!$detail = $this->db->get_one()) return false;
        return $this->global['timestamp'] - $detail['regdate'] <= $this->modcfg['registered_again'];
    }

    //找回密码
    function forget($username,$email) {
        if(!$username) redirect('member_post_empty_name');
        $this->loader->helper('validate');
        if(!$email||!validate::is_email($email)) redirect('member_post_empty_email');
        $this->db->from($this->table);
        $this->db->where('username',$username);
        $this->db->select('uid,email');
        if((!$r = $this->db->get_one())|| $r['email'] != $email) redirect('member_forget_invalid');

        $post = array();
        $post['uid'] = $r['uid'];
        $post['posttime'] = $this->global['timestamp'];
        $post['secode'] = create_formhash($post['uid'], $post['posttime'],'');
        $post['sort'] = 'get_password';

        $this->db->from('dbpre_getpassword');
        $this->db->set($post);
        $this->db->insert();
        $getpwid = $this->db->insert_id();

        $endday = 3; //有效期 天
		
        $url = str_replace('&amp;','&',url("member/login/op/updatepw/id/$getpwid/sec/$post[secode]",'',TRUE));


        $search = array('{sitename}','{siteurl}','{username}','{nowtime}','{endtime}','{forgeturl}','{endday}');
        $replace = array($this->global['cfg']['sitename'], $this->global['cfg']['siteurl'], $username,
            date('Y-m-d H:i:s',$this->global['timestamp']), date('Y-m-d H:i:s',($this->global['timestamp']+$endday*24*3600)),
            $url, $endday);

        $subject = str_replace($search, $replace, lang('member_forget_mail_subject'));
        $message = str_replace($search, $replace, lang('member_forget_mail_message'));
        $message = wordwrap($message, 75, "\r\n") . "\r\n";

        $cfg =& $this->global['cfg'];
        if($cfg['mail_use_stmp']) {
            $cfg['mail_stmp_port'] = $cfg['mail_stmp_port'] > 0 ? $cfg['mail_stmp_port'] : 25;
            $auth = ($cfg['mail_stmp_username'] && $cfg['mail_stmp_password']) ? TRUE : FALSE;
            $this->loader->lib('mail',null,false);
            $MAIL = new ms_mail($cfg['mail_stmp'], $cfg['mail_stmp_port'], $auth, $cfg['mail_stmp_username'], $cfg['mail_stmp_password']);
            $MAIL->debug = $cfg['mail_debug'];
            $result = @$MAIL->sendmail($email, $cfg['mail_stmp_email'], $subject, $message, 'TXT');
            unset($MAIL);
        } else {
            $result = @mail($email, $subject, $message);
        }
        if(!$result) redirect('global_send_mail_error');
    }

    //找回密码 - 读取操作信息
    function check_getpassword($getpwid, $secode) {
        if(!$getpwid || !$secode) redirect('member_getpassword_param_empty');
        $this->db->from('dbpre_getpassword');
        $this->db->where('getpwid', $getpwid);
        if(!$data = $this->db->get_one()) redirect('member_getPassword_empty');

        $endday = 3;
        if($data['posttime'] < $this->global['timestamp'] - $endday*24*3600) {
            $this->db->sql_roll_back('from');
            $this->db->where('getpwid', $getpwid);
            $this->db->delete();
            redirect('member_getpassword_timeout', url('member/login/op/forget'));
        }
        if($secode != create_formhash($data['uid'], $data['posttime'], '')) redirect('member_getpassword_invalid');
        return $data['uid'];
    }
    
    //找回密码 - 更新密码操作
    function update_password($getpwid, $secode, $pw1, $pw2) {
        if($error = $this->check_password($pw1, 1)) redirect($error);
        if($pw1 != $pw2) redirect('member_post_empty_eq_pw');
        $uid = $this->check_getpassword($getpwid, $secode);
        if(!$member = $this->read($uid)) redirect('member_empty');
        $return = uc_user_edit($member['username'], '', $pw1, '', 1);
        if($return < 0) redirect('ucenter_member_edit_' . $return);
        //更新密码
        $md5pw = md5($pw1);
        $this->db->from($this->table);
        $this->db->set('password', $md5pw);
        $this->db->where('uid', $uid);
        $this->db->update();
        //删除这个操作记录
        $this->db->from('dbpre_getpassword');
        $this->db->where('getpwid', $getpwid);
        $this->db->delete();
    }

    //验证邮箱和帐号
    function verify($getpwid, $secode) {
        if(!$getpwid || !$secode) redirect('member_verify_param_empty');
        $this->db->from('dbpre_getpassword');
        $this->db->where('getpwid', $getpwid);
        if(!$data = $this->db->get_one()) redirect('member_verify_empty');
        if($secode != create_formhash($data['uid'], $data['posttime'], '')) redirect('member_verify_invalid');

        //更新帐号会员组
        $this->db->from($this->table);
        $this->db->set('groupid', 10);
        $this->db->where('uid', $data['uid']);
        $this->db->where('groupid', 4);
        $this->db->update();
        //删除这个操作记录
        $this->db->from('dbpre_getpassword');
        $this->db->where('getpwid', $getpwid);
        $this->db->delete();
        return true;
    }
    //检测头像是否设置
    function check_avatar() {
        return uc_check_avatar($this->uid) == 1;
    }

    //获取当前用户的权限值
    function & get_access($key) {
        $usergroups =& $this->loader->variable('usergroup_'.$this->groupid,'member');
        $access = $usergroups['access'][$key];
        return $access;
    }

    //检测权限
    function check_access($key,&$callback,$jump=TRUE) {
        $value = $this->get_access($key);
        if(is_object($callback)) {
            return $callback->check_access($key,$value,$jump);
        }
        return $callback($key,$value);
    }

    function _set_variable() {
        $member = $this->read($this->uid);
        if($member) foreach($member as $key => $val) {
            $this->$key = $val;
        }
    }

    function _init_variable() {
        $this->uid = 0;
        $this->groupid = 1;
        $this->isLogin = FALSE;
    }

    function _save_login($life = 0) {
        $life = (int) $life;
        set_cookie('uid', $this->uid, $life);
        set_cookie('hash', $this->hash, $life);
        if($this->sync_login) $result = uc_user_synlogin($this->uid);
        if(!$result) $result = '&nbsp;';
        return $result;
    }

    function _update_user() {
        $update = FALSE;
        $this->db->clear();
        $groups = $this->loader->variable('usergroup','member');
        $is_member_group = $groups[$this->groupid]['grouptype'] == 'member';
        if($is_member_group) {//普通用户组会随着积分变化
            $nextgroupid = $this->get_groupid();
            if($this->groupid != $nextgroupid) {
                $update = TRUE;
                $this->db->set('groupid', $nextgroupid);
            }
        } elseif($this->nexttime > 0) { //特殊组会员到期时间判断
            $now = strtotime(date('Y-m-d', $this->global['timestamp']));
            if($this->nexttime < $now) { //到期了
                $update = TRUE;
                $this->db->set('groupid', $this->nextgroupid ? $this->get_groupid() : $this->nextgroupid);
                $this->db->set('nexttime', 0);
            }
        }
        if($this->global['timestamp'] - $this->logintime > 43200 ) {
            $update = TRUE;
            $this->db->set_add('logincount', 1);
            $this->db->set('logintime', $this->global['timestamp']);
            $this->db->set('loginip', $this->global['ip']);
        }
        if($update) {
            $this->db->from($this->table);
            $this->db->where('uid', $this->uid);
            $this->db->update();
        }
    }

    function update_login() {
        $this->db->set_add('logincount', 1);
        $this->db->set('logintime', $this->global['timestamp']);
        $this->db->set('loginip', $this->global['ip']);
        $this->db->from($this->table);
        $this->db->where('uid',$this->uid);
        $this->db->update();
    }
    
    function change_password($old,$new,$new2) {
    	if($need_old && !$old) redirect('member_post_empty_pw');
    	if(!$new || $new != $new2) redirect('member_post_empty_eq_pw');
    	if($error = $this->check_password($new, 1)) redirect($error);

        $return = uc_user_edit($this->username , $old , $new , '');
        if($return < 0) redirect('ucenter_member_edit_' . $return);

        $this->db->from($this->table);
        $this->db->where('uid',$this->uid);
        $this->db->select('password');
    	$member = $this->db->get_one();

    	//if(md5($old) != $member['password']) redirect('member_post_empty_pw_error');
    	//update pw
    	$md5pw = md5($new);
    	$this->db->from($this->table);
    	$this->db->set('password', $md5pw);
    	$this->db->where('uid', $this->uid);
    	$this->db->update();
    	//update cookie
    	$this->hash = create_formhash($this->uid, $this->username, '');
    	$this->_save_login();
    }

    function get_groupid() {
        $this->loader->model('member:usergroup', FALSE);
        $g = new msm_member_usergroup();
        $gid = $g->point_by_usergroup($this->point);
        if(!$gid) $gid = 10;
        return $gid;
    }

}
?>
