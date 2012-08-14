<?php
/**
* IP Address
*/
class ms_ipaddress {

	const LIB_FULL = "static/images/ipdata/QQWry.dat";
	const LIB_LITE = "static/images/ipdata/tinyipdata.dat";

	private $ip = '';
	private $lib = '';
	private $address = '';
	private $errMsg = '';
	
	function __construct() {
		$this->set_ip(_G('ip'));
		$this->set_lib(is_file(MUDDER_ROOT.self::LIB_FULL)?self::LIB_FULL:self::LIB_LITE);
	}

	public function set_ip($ip='') {
		$this->ip = $ip;
		return $this;
	}

	public function set_lib($lib=self::LIB_LITE) {
		$this->lib = $lib;
		return $this;
	}

	public function get_address() {
		$func = $this->lib == self::LIB_FULL ? '_get_full_lib' : '_get_lite_lib';
		return $this->$func();
	}

	public function get_cityid() {
		$addr = $this->get_address();
		if($addr && !is_numeric($addr)) {
			$citys = _G('loader')->variable('area');
			foreach($citys as $val) {
				if(strposex($addr, $val['name'])) return $val['aid'];
			}
		}
		return 0;
	}

	public function get_message() {
		return $this->errMsg;
	}

	private function _get_lite_lib() {

		static $fp = NULL, $offset = array(), $index = NULL;

		$ipdatafile = MUDDER_ROOT . $this->lib;

		if(!preg_match("/^([0-9]{1,3}.){3}[0-9]{1,3}$/", $this->ip)){
			$this->errMsg = 'IP Address Error';
		    return -1;//'IP Address Error';
		}

		$ipdot = explode('.', $this->ip);
		$ip    = pack('N', ip2long($this->ip));

		$ipdot[0] = (int)$ipdot[0];
		$ipdot[1] = (int)$ipdot[1];

		if($fp === NULL && $fp = @fopen($ipdatafile, 'rb')) {
			$offset = @unpack('Nlen', @fread($fp, 4));
			$index  = @fread($fp, $offset['len'] - 4);
		} elseif($fp == FALSE) {
			$this->errMsg = 'IP date file not exists or access denied';
			return  -2;
		}

		$length = $offset['len'] - 1028;
		$start  = @unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . 
			$index[$ipdot[0] * 4 + 3]);

		for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {
			if ($index{$start} . $index{$start + 1} . $index{$start + 2} . $index{$start + 3} >= $ip) {
				$index_offset = @unpack('Vlen', $index{$start + 4} . $index{$start + 5} . $index{$start + 6} . "\x0");
				$index_length = @unpack('Clen', $index{$start + 7});
				break;
			}
		}

		@fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
		if($index_length['len']) {
			$ipaddr = @fread($fp, $index_length['len']);
			if(trim($ipaddr) && _G('charset') != 'gb2312') {
			    $ipaddr = charset_convert($ipaddr, 'gb2312', _G('charset'));
			}
			if($ipaddr) return $ipaddr;
		}
        $this->errMsg = 'Unknown1';
        return 0;//'Unknown';
	}

	private function _get_full_lib() {
		$this->errMsg = '';
		//IP数据文件路径
		$ipdatafile = MUDDER_ROOT . $this->lib;

		//检查IP地址
		if(!preg_match("/^([0-9]{1,3}.){3}[0-9]{1,3}$/", $this->ip)){
			$this->errMsg = 'IP Address Error';
		    return -1;//'IP Address Error';
		}

		//打开IP数据文件
		if(!$fd = fopen($ipdatafile, 'rb')){
			$this->errMsg = 'IP date file not exists or access denied';
		    return -2;//'IP date file not exists or access denied';
		}

		//分解IP进行运算，得出整形数
		$ip = explode('.', $this->ip);
		$ipNum = $ip[0] * 16777216 + $ip[1] * 65536 + $ip[2] * 256 + $ip[3];

		//获取IP数据索引开始和结束位置
		$DataBegin = fread($fd, 4);
		$DataEnd = fread($fd, 4);
		$ipbegin = implode('', unpack('L', $DataBegin));
		if($ipbegin < 0) $ipbegin += pow(2, 32);
		$ipend = implode('', unpack('L', $DataEnd));
		if($ipend < 0) $ipend += pow(2, 32);
		$ipAllNum = ($ipend - $ipbegin) / 7 + 1;

		$BeginNum = 0;
		$EndNum = $ipAllNum;

		//使用二分查找法从索引记录中搜索匹配的IP记录
		while($ip1num>$ipNum || $ip2num<$ipNum) {
		    $Middle= intval(($EndNum + $BeginNum) / 2);

		    //偏移指针到索引位置读取4个字节
		    fseek($fd, $ipbegin + 7 * $Middle);
		    $ipData1 = fread($fd, 4);
		    if(strlen($ipData1) < 4) {
		        fclose($fd);
		        $this->errMsg = 'System Error';
		        return -3;//'System Error';
		    }
		    //提取出来的数据转换成长整形，如果数据是负数则加上2的32次幂
		    $ip1num = implode('', unpack('L', $ipData1));
		    if($ip1num < 0) $ip1num += pow(2, 32);

		    //提取的长整型数大于我们IP地址则修改结束位置进行下一次循环
		    if($ip1num > $ipNum) {
		        $EndNum = $Middle;
		        continue;
		    }

		    //取完上一个索引后取下一个索引
		    $DataSeek = fread($fd, 3);
		    if(strlen($DataSeek) < 3) {
		        fclose($fd);
		        $this->errMsg = 'System Error';
		        return -4;//'System Error';
		    }
		    $DataSeek = implode('', unpack('L', $DataSeek.chr(0)));
		    fseek($fd, $DataSeek);
		    $ipData2 = fread($fd, 4);
		    if(strlen($ipData2) < 4) {
		        fclose($fd);
		        $this->errMsg = 'System Error';
		        return -5;//'System Error';
		    }
		    $ip2num = implode('', unpack('L', $ipData2));
		    if($ip2num < 0) $ip2num += pow(2, 32);

		    //没找到提示未知
		    if($ip2num < $ipNum) {
		        if($Middle == $BeginNum) {
		            fclose($fd);
		            $this->errMsg = 'Unknown1';
		            return 0;//'Unknown';
		        }
		        $BeginNum = $Middle;
		    }
		}

		$ipFlag = fread($fd, 1);
		if($ipFlag == chr(1)) {
		    $ipSeek = fread($fd, 3);
		    if(strlen($ipSeek) < 3) {
		        fclose($fd);
		        $this->errMsg = 'System Error';
		        return -6;//'System Error';
		    }
		    $ipSeek = implode('', unpack('L', $ipSeek.chr(0)));
		    fseek($fd, $ipSeek);
		    $ipFlag = fread($fd, 1);
		}

		if($ipFlag == chr(2)) {
		    $AddrSeek = fread($fd, 3);
		    if(strlen($AddrSeek) < 3) {
		        fclose($fd);
		        $this->errMsg = 'System Error';
		        return -7;//'System Error';
		    }
		    $ipFlag = fread($fd, 1);
		    if($ipFlag == chr(2)) {
		        $AddrSeek2 = fread($fd, 3);
		        if(strlen($AddrSeek2) < 3) {
		            fclose($fd);
		            $this->errMsg = 'System Error';
		            return -8;//'System Error';
		        }
		        $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
		        fseek($fd, $AddrSeek2);
		    } else {
		        fseek($fd, -1, SEEK_CUR);
		    }

		    while(($char = fread($fd, 1)) != chr(0))
		        $ipAddr2 .= $char;

		    $AddrSeek = implode('', unpack('L', $AddrSeek.chr(0)));
		    fseek($fd, $AddrSeek);

		    while(($char = fread($fd, 1)) != chr(0))
		        $ipAddr1 .= $char;
		} else {
		    fseek($fd, -1, SEEK_CUR);
		    while(($char = fread($fd, 1)) != chr(0))
		        $ipAddr1 .= $char;

		    $ipFlag = fread($fd, 1);
		    if($ipFlag == chr(2)) {
		        $AddrSeek2 = fread($fd, 3);
		        if(strlen($AddrSeek2) < 3) {
		            fclose($fd);
		            $this->errMsg = 'System Error';
		            return -9;//'System Error';
		        }
		        $AddrSeek2 = implode('', unpack('L', $AddrSeek2.chr(0)));
		        fseek($fd, $AddrSeek2);
		    } else {
		        fseek($fd, -1, SEEK_CUR);
		    }
		    while(($char = fread($fd, 1)) != chr(0)){
		        $ipAddr2 .= $char;
		    }
		}
		fclose($fd);

		//最后做相应的替换操作后返回结果
		if(preg_match('/http/i', $ipAddr2)) {
		    $ipAddr2 = '';
		}

		$ipaddr = "$ipAddr1 $ipAddr2";
		if(trim($ipaddr) && _G('charset') != 'gb2312') {
		    $ipaddr = charset_convert($ipaddr, 'gb2312', _G('charset'));
		}

		$ipaddr = preg_replace('/CZ88.Net/is', '', $ipaddr);
		$ipaddr = preg_replace('/^s*/is', '', $ipaddr);
		$ipaddr = preg_replace('/s*$/is', '', $ipaddr);
		if(preg_match('/http/i', $ipaddr) || $ipaddr == '') {
			$this->errMsg = 'Unknown2';
		    $ipaddr = 0;//'Unknown';
		}

		return $ipaddr;
	}
}