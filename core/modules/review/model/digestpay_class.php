<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
class msm_review_digestpay extends ms_model {

    var $table = 'dbpre_digest_pay';
    var $key = 'payid';

    var $idtypes = null;
    var $modcfg = null;
    var $r = null;

    var $price = 0;
    var $price_gain = 0;
    var $pointtype = 'point1';
    var $pointname = '';

    function __construct() {
        parent::__construct();
        $this->model_flag = 'review';
        $this->modcfg = $this->variable('config');
        $this->init_config();
        $this->r =& $this->loader->model(':review');
    }

    function init_config() {
        $this->price = (int)$this->modcfg['digest_price'];
        $this->price < 0 && $this->price = 0;
        $this->pointtype = $this->modcfg['digest_pointtype'];
        $this->pointname = display('member:point',"point/$pt");

        $gain = (int)$this->modcfg['digest_gain'];
        $gain < 0 && $gain = 0;
        if($gain) {
            $this->price_gain = round($this->price*($gain/100));
        }
    }

    //是否启用
    function is_enabled() {
        return !empty($this->price) && !empty($this->pointtype);
    }

    //是否返利
    function is_gain() {
        return !empty($this->price_gain);
    }

    //购买前判断
    function buycheck($rid) {
        $review = $this->r->read($rid);
        if(empty($review)) redirect('review_empty');
        if(!$review['digest']) redirect('review_digest_invalid');
        if(!$pt = $this->pointtype) redirect('review_digest_fun_invalid');
        if($this->global['user']->$pt < $this->price) {
            redirect(lang('review_digest_point_not_enough', array($this->pointname, $this->price)));
        }
        return $review;
    }

    //是否已经购买过
    function exists($rid) {
        $this->db->from($this->table);
        $this->db->where('idtype','review');
        $this->db->where('id',$rid);
        $this->db->where('uid',$this->global['user']->uid);
        return $this->db->count()>0;
    }

    //购买
    function pay($rid) {
        if(!$this->is_enabled()) return true;
        if($this->exists($rid)) return true;
        $review = $this->buycheck($rid);
        $this->_save($review);
        return true;
    }

    //保存记录
    function _save(&$review) {
        $this->db->from($this->table)
            ->set('id', $review['rid'])
            ->set('idtype', 'review')
            ->set('uid', $this->global['user']->uid)
            ->set('username', $this->global['user']->username)
            ->set('price', $this->price)
            ->set('pointtype', $this->pointtype)
            ->set('dateline', $this->timestamp);
        if($this->is_gain()&&$review['uid']>0) {
            $this->db->set('gain_uid', $review['uid'])
                ->set('gain_price', $this->price_gain);
        }
        $this->db->insert();

        $this->_pay_point($review);
        if($this->is_gain()) $this->_gain_point($review);

        return $this->db->insert_id();
    }

    //减去用户积分
    function _pay_point(&$review) {
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($this->global['user']->uid,$this->pointtype,-$this->price,lang('购买精华点评(rid:%d)', $review['rid']));
    }

    //点评作者奖励积分
    function _gain_point(&$review) {
        if(!$review['uid']) return;
        $PT =& $this->loader->model('member:point');
        $PT->update_point2($review['uid'],$this->pointtype,$this->price_gain,lang('精华点评奖励(rid:%d)', $review['rid']));
    }
}
?>