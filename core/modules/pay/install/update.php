<?php
!defined('IN_MUDDER') && exit('Access Denied');
class model_update extends ms_model {

    public $flag = 'pay';
    public $moduleid = 0;

    public $setp = 0;
    public $start = 0;
    public $index = 0;
    public $params = array();
    public $progress = 0;

    public $total_setp = 1;
    public $next_setp = false;

    private $old_ver = '';
    private $new_ver = '2.1';

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
                "dbpre_pay",
                "payid mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
                order_flag varchar(30) NOT NULL DEFAULT '',
                orderid mediumint(8) unsigned NOT NULL DEFAULT '0',
                uid mediumint(8) unsigned NOT NULL DEFAULT '0',
                order_name varchar(255) NOT NULL DEFAULT '',
                payment_orderid varchar(60) NOT NULL DEFAULT '',
                payment_name varchar(60) NOT NULL DEFAULT '',
                creation_time int(10) NOT NULL DEFAULT '0',
                pay_time int(10) unsigned NOT NULL DEFAULT '0',
                price decimal(9,2) NOT NULL DEFAULT '0.00',
                pay_status tinyint(1) NOT NULL DEFAULT '0',
                my_status tinyint(1) NOT NULL DEFAULT '0',
                notify_url varchar(255) NOT NULL DEFAULT '',
                callback_url varchar(255) NOT NULL DEFAULT '',
                PRIMARY KEY (payid),
                KEY order_flag (order_flag,orderid)",
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

    private function _check_version() {
    }

}
?>