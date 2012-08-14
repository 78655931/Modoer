<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'exchange';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 5;
    public $next_setp = false;

    private $old_ver = '2.0';
    private $new_ver = '3.0';

	public function __construct($moduleid,$old_ver) {
		parent::__construct();
        $this->loader->helper('sql');
        $this->moduleid = $moduleid;
        $this->old_ver = $old_ver;
        $this->_check_version();
        $this->step = (int) _get('step');
        $this->step = $this->step < 1 || !$this->step ? 1 : $this->step;
        $this->start = (int) _get('start');
        $this->start = $this->start < 0 || !$this->start ? 0 : $this->start;
        $this->index = (int) _get('index');
        $this->index = $this->index < 0 || !$this->index ? 0 : $this->index;
	}

    public function updating() {
        $method = '_step_' . $this->step;
        if(method_exists($this, $method)) {
            $this->$method();
        } else {
            echo sprintf('The required method "%s" does not exist for %s.', $method, get_class($this));
            exit;
        }
        return $this;
    }

    public function completed() {
        $this->params['moduleid'] = $this->moduleid;
        $this->params['step'] = $this->step;
        if($this->next_setp) {
            $this->start = $this->index = 0;
        }
        $this->params['start'] = $this->start;
        $this->params['index'] = $this->index;
        $this->progress = round($this->step / $this->total_setp, 2);
        if($this->progress>1) $this->progress = 1;
        return $this->step > $this->total_setp;
    }

    private function _step_1() {
        $tables = array(
            array(
                "dbpre_exchange_category",
                "catid smallint(8) unsigned NOT NULL AUTO_INCREMENT,
                name varchar(40) NOT NULL DEFAULT '',
                num mediumint(0) NOT NULL DEFAULT '0',
                listorder smallint(5) NOT NULL DEFAULT '0',
                PRIMARY KEY  (catid)",
            ),
            array(
                "dbpre_exchange_lottery",
                "lid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                giftid mediumint(8) unsigned NOT NULL DEFAULT '0',
                uid mediumint(8) NOT NULL default '0',
                lotterycode varchar(50) NOT NULL DEFAULT '',
                status tinyint(1) unsigned NOT NULL DEFAULT '0',
                dateline int(10) unsigned NOT NULL DEFAULT '0',
                PRIMARY KEY (lid),
                KEY giftid (giftid)",
            ),
        );
        if($table = $tables[$this->start]) {
            sql_create_table(str_replace('dbpre_','', $table[0]), $table[1]);
            $this->start++;
        } else {
            if($this->start >= count($tables)) {
                $this->step++;
                $this->next_setp = true;
            }
        }
    }

    private function _step_2() {
        $array = array(
            array('exchange_log', 'pointtype', 'CHANGE', "pointtype pointtype VARCHAR( 50 ) NOT NULL DEFAULT ''"),
                        array('exchange_gifts', 'pointtype', 'CHANGE', "pointtype pointtype ENUM( 'rmb', 'point1', 'point2', 'point3', 'point4', 'point5', 'point6', 'point7', 'point8' ) NOT NULL"),

            array('exchange_log', 'pay_style', 'add', "pay_style tinyint(1) NOT NULL DEFAULT '0' AFTER number"),
            array('exchange_gifts', 'catid', 'add', "catid smallint(5) unsigned NOT NULL default '0' AFTER giftid"),
            array('exchange_gifts', 'sid', 'add', "sid smallint(5) unsigned NOT NULL default '0' AFTER catid"),
            array('exchange_gifts', 'pattern', 'add', "pattern tinyint(1) unsigned NOT NULL DEFAULT '1' AFTER sort"),
            array('exchange_gifts', 'reviewed', 'add', "reviewed tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER pattern"),
            array('exchange_gifts', 'starttime', 'add', "starttime int(10) NOT NULL DEFAULT '0' AFTER available"),
            array('exchange_gifts', 'endtime', 'add', "endtime int(10) NOT NULL DEFAULT '0' AFTER starttime"),
            array('exchange_gifts', 'randomcodelen', 'add', "randomcodelen tinyint(1) NOT NULL DEFAULT '0' AFTER endtime"),
            array('exchange_gifts', 'randomcode', 'add', "randomcode varchar(50) NOT NULL DEFAULT '' AFTER randomcodelen"),
            array('exchange_gifts', 'point', 'add', "point int(10) unsigned NOT NULL DEFAULT '0' AFTER price"),
            array('exchange_gifts', 'point3', 'add', "point3 int(10) unsigned NOT NULL DEFAULT '0' AFTER point"),
            array('exchange_gifts', 'point4', 'add', "point4 int(10) unsigned NOT NULL DEFAULT '0' AFTER point3"),
            array('exchange_gifts', 'pointtype2', 'add', "pointtype2 ENUM( 'rmb', 'point1', 'point2', 'point3', 'point4', 'point5', 'point6', 'point7', 'point8' ) NOT NULL AFTER pointtype"),
            array('exchange_gifts', 'pointtype3', 'add', "pointtype3 ENUM( 'rmb', 'point1', 'point2', 'point3', 'point4', 'point5', 'point6', 'point7', 'point8' ) NOT NULL AFTER pointtype2"),
            array('exchange_gifts', 'pointtype4', 'add', "pointtype4 ENUM( 'rmb', 'point1', 'point2', 'point3', 'point4', 'point5', 'point6', 'point7', 'point8' ) NOT NULL AFTER pointtype3"),
            array('exchange_gifts', 'timenum', 'add', "timenum int(10) unsigned NOT NULL DEFAULT '0' AFTER num"),
            array('exchange_gifts', 'allowtime', 'add', "allowtime varchar(255) NOT NULL DEFAULT ''"),
            array('exchange_gifts', 'usergroup', 'add', "usergroup varchar(255) NOT NULL DEFAULT ''"),
        );
        if($detail = $array[$this->index]) {
            sql_alter_field($detail[0], $detail[1], $detail[2], $detail[3]);
        }
        $this->index++;
        $total = count($array);
        if($this->index >= $total) {
            $this->next_setp = true;
            $this->step++;
        }
    }

    private function _step_3() {
        $this->db->from('dbpre_exchange_category');
        $this->db->where('catid', '1');
        if(!$this->db->count()) {
            $this->db->from('dbpre_exchange_category');
            $this->db->set('catid', '1');
            $this->db->set('name', '默认分类');
            $this->db->set('num', '0');
            $this->db->set('listorder', '1');
            $this->db->insert();
        }
        $this->step++;
        $this->next_setp = true;
    }

    private function _step_4() {
        $this->db->from('dbpre_exchange_gifts');
        $this->db->set('catid', '1');
        $this->db->where('catid', '0');
        $this->db->update();

        $total = $this->db->from('dbpre_exchange_gifts')->where('catid', 1)->where('available',1)->count();
        $this->db->from('dbpre_exchange_category')->set('num',(int)$total)->where('catid',1)->update();

        $this->step++;
        $this->next_setp = true;
    }

    private function _step_5() {
        $this->db->from('dbpre_modules');
        $this->db->set('version', '3.0');
        $this->db->set('releasetime', '2012-5-1');
        $this->db->set('author', 'moufer，轩');
        $this->db->set('introduce', '使用网站金币兑换礼品，抽奖，刺激玩家的积极性，消费金币');
        $this->db->set('siteurl', 'http://www.modoer.com');
        $this->db->set('email', 'moufer@163.com');
        $this->db->set('copyright', 'Moufer Studio');
        $this->db->where('flag', 'exchange');
        $this->db->update();
        $this->step++;
        $this->next_setp = true;
    }

    private function _get_detail() {
        $this->db->from('dbpre_modules');
        $this->db->where('flag', $this->flag);
        return $this->db->get_one();
    }

    private function _check_version() {
        if($this->old_ver < '2.0') {
            echo 'Please first upgrade your module(exchange) to version 2.0.';
            exit;
        }
    }

}
?>