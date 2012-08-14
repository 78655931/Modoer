<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');

class msm_pay_card extends ms_model {

    var $table = 'dbpre_pay_card';
    var $key = 'cardid';
    var $model_flag = 'pay';

    var $modcfg = array();
    var $cz_type = null;

    function __construct() {
        parent::__construct();
        $this->modcfg = $this->variable('config');
        $this->cz_type = @unserialize($this->modcfg['cz_type']);
    }

    function msm_pay_card() {
        $this->__construct();
    }

    function read($keyvalue,$keyname=null) {
        if(!$keyname) {
            return parent::read($keyvalue);
        }
        $this->db->from($this->table);
        $this->db->where($keyname,$keyvalue);
        $result = $this->db->get_one();
        return $result;
    }

    function batch_create($post) {
        $post['num'] = (int) $post['num'];
        if($post['num'] < 1) redirect('pay_card_create_num_min');
        if($post['num'] > 100) redirect(lang('pay_card_create_num_max',100));
        $post['price'] = (int) $post['price'];
        if($post['price'] <= 1) redirect('pay_card_create_price_min');
        if(!$this->cz_type && in_array($post['type'], $this->cz_type)) redirect('pay_card_create_type_empty');
        $endtime = strtotime($post['endtime']);
        if($endtime <= $this->global['timestamp']) redirect('pay_card_create_time_invalid');
        $use_num = !$this->modcfg['card_no_type'] || in_array($this->modcfg['card_no_type'],array('numeric', 'mix')); //使用数字
        $use_letter = !$this->modcfg['card_no_type'] || in_array($this->modcfg['card_no_type'],array('character', 'mix')); //使用字母
        $this->modcfg['card_prefix'] = trim($this->modcfg['card_prefix']);
        if($use_prefix && !$this->modcfg['card_prefix']) redirect('pay_card_create_prefix_invalid');
        $use_prefix && $prefix = $this->modcfg['card_prefix'];
        if(!$post['no_pw'] && $this->modcfg['card_pwnum'] < 6) redirect(lang('pay_card_create_password_min', 6));
        for($i=0; $i<$post['num']; $i++) {
            $insert = array();
            $insert['number'] = $this->_create_number($i, $this->modcfg['card_numlen'], $use_letter, $use_num, $prefix);
            $insert['password'] = $post['no_pw'] ? 'NULL' : $this->_create_random($this->modcfg['card_pwnum'], FALSE);
            $insert['cztype'] = $post['type'];
            $insert['price'] = $post['price'];
            $insert['dateline'] = $this->global['timestamp'];
            $insert['endtime'] = $endtime;
            $insert['status'] = 1;
            $insert['usetime'] = 0;
            $insert['uid'] = 0;
            $insert['username'] = '';
            $this->db->from($this->table);
            $this->db->set($insert);
            $this->db->insert();
        }
    }

    function recharge($cardno, $cardpw, $no_pw=false, $cztype='rmb') {
        if(!$this->modcfg['card']) redirect('pay_card_disabled');
        if(!$cardno || (!$no_pw && !$cardpw)) redirect('pay_card_recharge_empty');
        if(!$card = $this->read($cardno,'number')) {
            redirect('pay_card_recharge_not_exists');
        }
        if($card['status'] != '1') redirect('pay_card_recharge_status_invalid');
        $time = $this->global['timestamp'] + 24 * 3600;
        $endtime = mktime(0,0,0,date('m', $time),date('d', $time),date('Y', $time));
        if($card['endtime'] < $endtime) redirect('pay_card_recharge_time_invalid');
        if(!$no_pw) {
            if($card['password'] != $cardpw) redirect('pay_card_recharge_pw_invalid');
        } else {
            if($card['password'] != 'NULL') redirect('pay_card_recharge_not_nopw');
        }
		$typename = $card['cztype'] == 'rmb' ? lang('pay_type_rmb') : template_print('member','point',array('point'=>$card['cztype']));
        if($card['cztype'] != $cztype) redirect(lang('pay_card_recharge_cztype_invalid',$typename));

        //更新
        $this->db->from($this->table);
        $this->db->set('status',2);
        $this->db->set('uid', $this->global['user']->uid);
        $this->db->set('username', $this->global['user']->username);
        $this->db->set('usetime', $this->global['timestamp']);
        $this->db->where('cardid', $card['cardid']);
        $this->db->update();
        //给会员充值,因为2.0的会员模块不支持现金字段充值，所以独立写一个
        $this->db->from('dbpre_members');
        $this->db->set_add($card['cztype'], $card['price']);
        $this->db->where('uid', $this->global['user']->uid);
        $this->db->update();
		//充值记录
		$log =& $this->loader->model('member:point_log');
		$post['out_uid'] = $post['in_uid'] = $this->global['user']->uid;
		$post['out_username'] = $post['in_username'] = $this->global['user']->username;
		$post['out_point'] = '';
		$post['in_point'] = $card['cztype'];
		$post['out_value'] = 0;
		$post['in_value'] = $card['price'];
		$post['dateline'] = $this->global['timestamp'];
		$post['des'] = lang('member_point_pay_des');
		$log->save($post);
        return $card;
    }

    function update_status() {
        $endtime = strtotime(date('Y-m-d',$this->global['timestamp'] + 24*3600));
        $this->db->from($this->table);
        $this->db->set('status',3);
        $this->db->where('status',1);
        $this->db->where_less('endtime',$endtime);
        $this->db->update();
    }

    function export($where) {
        
        $params = lang('pay_export_title');

        $this->db->from($this->table);
        $this->db->where($where);
        $this->db->select(implode(',', array_keys($params)));
        $this->db->order_by('dateline','DESC');
        if(!$list = $this->db->get()) redirect('pay_execl_empty');

        header("Content-Type: application/text");
        header("Content-Disposition: attachment; filename=".lang('pay_export_caption').".txt");
        header("Pragma: no-cache");
        header("Expires: 0");

        foreach($params as $val) {
            echo $val . "\t";
        }
        echo "\r\n";

        while($value = $list->fetch_array()) {
            foreach($params as $key=>$val) {
                if($key=='password' && $value[$key] == 'NULL') $value[$key] = '';
                if($key=='cztype') $value[$key] = lang('pay_type_' . $value[$key]);
                if($key=='dateline') $value[$key] = date('Y-m-d', $value[$key]);
                if($key=='endtime' || $key=='exchangetime') $value[$key] = date('Y-m-d', $value[$key]);
                if($key=='status') $value[$key] = lang('pay_card_status_'.$value[$key]);
                echo $value[$key] . "\t";
            }
            echo "\r\n";
        }
        $list->free_result();
        exit();
    }

    function _create_number($i, $numlen, $use_letter=TRUE, $use_num=TRUE, $prefix = '') {
        $num = $numlen;
        $p1 = '';
        $p2 = $this->_create_random($num, $use_letter, $use_num, $prefix);
        return $p2 . $p1;
    }

    function _create_random($length, $use_letter=TRUE, $use_num=TRUE, $prefix = '') {
        PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
        $hash = $chars = '';
        if($use_letter) {
            $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        if($use_num) {
            $chars .= '0123456789';
        }
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $prefix . $hash;
    }

}
?>