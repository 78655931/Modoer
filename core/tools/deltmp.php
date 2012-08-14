<?php
/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2011 Moufersoft
* @website www.modoer.com
*/
_G('loader')->model('tool',FALSE);
class msm_tool_deltmp extends msm_tool {

    protected $name = 'ɾ��Modoer��ʱ�ļ�';
    protected $descrption = 'ɾ��Modoer��uploads/temp�ļ����ڵ���ʱ�ļ����������賿û���û�������ʱ��ִ�С�';

    private $tmpdir = '';

    public function run() {
        $this->tmpdir = MUDDER_ROOT . 'uploads' . DS . 'temp';
        $this->_delete($this->tmpdir);
        $this->completed = true;
    }

    private function _delete($dir) {
        $dh = opendir($dir);
        if(!$dh) return;
        while ($file=  readdir($dh)) {
            if($file != "." && $file != "..") {
                $fullpath = $dir . DS . $file;
                if(!is_file($fullpath)) {
                    unlink($fullpath);
                } elseif(is_dir($fullpath)) {
                    $this->_delete($fullpath);
                }
            }
        }
        closedir($dh);

        if($dir == $this->tmpdir) return;
        rmdir($dir);
    }
}
/* end */