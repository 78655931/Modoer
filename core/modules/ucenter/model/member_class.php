<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_ucenter_member extends ms_model {

    var $table = 'dbpre_members';
    var $key = 'uid';

    var $modcfg;

    function __construct() {
        parent::__construct();
        $this->model_flag = 'member';
        $this->modcfg = $this->loader->variable('config','member');
        $this->init_field();
    }

    function msm_member() {
        $this->__construct();
    }

    function init_field() {
		$this->add_field('uid,username,password,password2,email,groupid,nexttime,nextgroupid,passport,passport_id');
		$this->add_field_fun('username,passport,passport_id', '_T');
        $this->add_field_fun('uid,groupid,nexttime,nextgroupid', 'intval');
        $this->add_field_fun('password,email', 'trim');
    }

    function & read($uid,$isusername=FALSE,$select='*') {
        $result = '';
        $keyname = 'username';
        if(!$isusername) {
            if(!$uid = (int) $uid) return $result;
            $keyname = 'uid';
        }
        $this->db->from($this->table);
        $this->db->select($select);
        $this->db->where($keyname, $uid);
        if($row = $this->db->get()) {
            $result = $row->fetch_array();
            $row->free_result();
        } else {
            return $return;
        }
        $this->db->from('dbpre_member_profile');
        $this->db->where('uid',$result['uid']);
        $profile = $this->db->get_one();
        if(is_array($profile)) $result = array_merge($profile,$result);
        return $result;
    }

    function & find($where, $start, $offset) {
        $result = array();
        $this->db->from($this->table);
        if($where['username']) {
            $this->db->where_like('username', '%'.$where['username'].'%');
            unset($where['username']);
        }
        if($where) {
            foreach($where as $key => $val) $this->db->where($key, $val);
        }
        $total = $this->db->count();
        if(!$total) {
            $result = array(0,'');
            return $result;
        }
        $this->db->sql_roll_back('from,where');
        $this->db->order_by('uid');
        $this->db->limit($start, $offset);
        $result = array($total, $this->db->get());
        return $result;
    }

    function save(& $post,$uid=null,$activation=FALSE) {
        $edit = $uid > 0;
        if($post['password'] != $post['password2']) {
            redirect('member_post_empty_eq_pw');
        }
        if($this->in_admin && empty($post['password'])) unset($post['password']);
        $pw = $post['password'];
        if($post['password']) $post['password'] = md5($post['password']);
        unset($post['password2']);
        $post['nexttime'] = $post['nexttime'] ? strtotime($post['nexttime']) : 0;
        $this->check_post($post, $edit, $activation);

        if($edit) {
            $detail = $this->read($uid);
            if(empty($detail)) redirect('member_empty');
            if($detail['email'] == $post['email']) $post['email'] = $detail['email'];
            $return = uc_user_edit($detail['username'], '', $pw, $post['email'], 1);
            if($return < 0 && $return > -7) redirect('ucenter_member_edit_' . $return);
        } elseif(!$activation) {
            $return = uc_user_register($post['username'], $pw, $post['email']);
            if($return < 0) redirect('ucenter_member_add_' . $return);
            $post['uid'] = $return;
        }

        if(!$this->in_admin) {
            if($this->modcfg['email_verify']) {
                $post['groupid'] = 4;
                $this->global['email_verify'] = $post['email'];
            }
        }
        $uid = parent::save($post, $uid, 0, 0, 0);

        if(!$edit) {
            $P =& $this->loader->model('member:point');
            $P->update_point($uid, 'reg');
            //发送短消息
            if($this->modcfg['salutatory']) $this->_send_salutatory_msg($uid);
            //发送激活邮件
            $this->global['email_verify'] && $this->_send_verify_mail($uid);
        }
        return $uid;
    }

    function activation($post) {
        $post['password'] = md5($post['password']);
        parent::save($post, null, 0, 0, 0);
        $P =& $this->loader->model('member:point');
        $P->update_point($post['uid'], 'reg');
    }

    function delete($ids) {
        $ids = $this->get_keyids($ids);
        $this->db->where_in($this->key, $ids);
        $this->db->from($this->table);
        $this->db->delete(); 
        //passport
        $PT =& $this->loader->model('member:passport');
        $PT->delete($ids,false);
        //其他模块关联的HOOK
        foreach(array_keys($this->global['modules']) as $flag) {
            if($flag == $this->model_flag) continue;
            $file = MUDDER_MODULE . $flag . DS . 'inc' . DS . 'member_delete_hook.php';
            if(is_file($file)) {
                @include $file;
            }
        }
    }

    function check_post(& $post, $isedit, $activation = FALSE) {
        $this->loader->helper('validate');
        if(!$post['username'] && !$isedit) {
            redirect('member_post_empty_name');
        } elseif(!$isedit && $post['username']) {
            $this->check_username($post['username']);
        }
        if(!$post['password'] && !$isedit) {
            redirect('member_post_empty_pw');
        }
        if($post['password']) {
            $this->check_password($post['password']);
        }
        if(!$activation) {
            $this->loader->helper('validate');
            if(!$post['email'] || !validate::is_email($post['email'])) {
                redirect('member_post_empty_email');
            }
            if($post['groupid'] && $post['groupid'] < 0) {
                redirect('member_post_empty_group');
            }
        }
    }

    function check_username_exists($username, $without_uid = null) {
        $this->db->from($this->table);
        $this->db->where('username',$username);
        if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
        if($this->db->count() > 0) return TRUE;
        $return = uc_user_checkname($username);
        if($return < 0) return TRUE;
    }

    function check_email_exists($email, $without_uid = null) {
        $this->db->from($this->table);
        $this->db->where('email',$email);
        if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
        if($this->db->count() > 0) return TRUE;
        $return = uc_user_checkemail($email);
        if($return < 0) return TRUE;
    }

    function check_mobile_exists($mobile, $without_uid = null) {
        $this->db->from($this->table);
        $this->db->where('mobile',$mobile);
        if($without_uid > 0) $this->db->where_not_equal('uid', $without_uid);
        return $this->db->count() > 0;
    }

    function check_username($username, $echo = FALSE) {
        if(strlen($username) > 15) {
            if($echo) { echo lang('member_reg_name_len_max'); exit; }
            return redirect('member_reg_name_len_max');
        }
        $return = uc_user_checkname($username);
        if($return < 0) {
            if($echo) { echo lang('ucenter_member_checkname_'.$return); exit; }
            redirect('ucenter_member_checkname_'.$return);
        }
        if($censorwords = $this->modcfg['censoruser'] ? explode("\r\n", $this->modcfg['censoruser']) : '') {
            foreach($censorwords as $censor) {
                $preg = "/".str_replace("*", ".*?", $censor)."/is";
                if(preg_match($preg, $username)) {
                    if($echo) {echo lang('member_reg_name_limit'); exit; }
                    redirect('member_reg_name_limit');
                }
            }
        }
    }

    function check_password($password, $return_error = FALSE) {
        $len = 6;
        if(strlen($password) < $len) {
        	$error = sprintf(lang('member_post_empty_pw_len'), $len);
        	if($return_error) return $error;
            redirect($error);
        }
        if(!preg_match("/[a-zA-Z0-9_~!@#]+/i", $password)) {
        	if($return_error) return lang('member_reg_pw_limit');
            redirect('member_reg_pw_limit');
        }
    }

    function _send_salutatory_msg($uid) {
        if(!$member = $this->read($uid)) return;
        $subject = lang('member_reg_msg_subject', $this->global['cfg']['sitename']);
        $content = $this->modcfg['salutatory_msg'] ? $this->modcfg['salutatory_msg'] : lang('member_reg_msg_content');

        $rp = array('$sitename', '$username', '$time');
        $sm = array($this->global['cfg']['sitename'], $member['username'], date("Y-m-d H:i:s", $this->global['timestamp']));
        $content = str_replace($rp, $sm, $content);

        $MSG =& $this->loader->model('member:message');
        $MSG->send(0, $uid, $subject, $content);
        unset($MSG);
    }

    function _send_verify_mail($uid) {
        if(!$uid) return false;
        if(!$detail = $this->read($uid)) return false;
        if($detail['groupid'] != '4') return false;

        $post = array();
        $post['uid'] = $uid;
        $post['posttime'] = $this->global['timestamp'];
        $post['secode'] = create_formhash($post['uid'], $post['posttime'],'');
        $post['sort'] = 'email_verify';

        $this->db->from('dbpre_getpassword');
        $this->db->set($post);
        $this->db->insert();
        $getpwid = $this->db->insert_id();

        $url = str_replace('&amp;','&',url("member/login/op/verify/id/$getpwid/sec/$post[secode]",'',TRUE));
        if(!$email = $detail['email']) return false;

        $search = array('{sitename}','{siteurl}','{username}','{nowtime}','{verifyurl}');
        $replace = array($this->global['cfg']['sitename'], $this->global['cfg']['siteurl'], $detail['username'],
            date('Y-m-d H:i:s',$this->global['timestamp']), $url);

        $subject = str_replace($search, $replace, lang('member_verify_mail_subject'));
        $message = str_replace($search, $replace, lang('member_verify_mail_message'));
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
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/plain;charset=" . _G('charset') . "\r\n";
            $result = @mail($email, $subject, $message, $headers);
        }

        return $result;
    }


    //更新积分
    function update_point($uid,$point) {
        if(!$uid = abs ( (int) $uid)) redirect(lang('global_sql_keyid_invalid','uid'));
        $this->db->from($this->table);
        $this->db->where('uid',$uid);
        $this->db->set($point);
        $this->db->update();
    }
}
?>