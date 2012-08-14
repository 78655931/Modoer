<?php
/**
 * KindEditor PHP
 * http://www.kindsoft.net/
 */

define("IN_MUDDER", TRUE);
require_once 'JSON.php';

//与根目录的子目录等级
$dir_level = 3; 

//文件保存目录路径
$root_dir = dirname(__FILE__);
for($i=1; $i<=$dir_level; $i++) {
    $root_dir = dirname($root_dir);
}
//URL相对根目录
$self = explode("/",dirname($_SERVER['PHP_SELF']));
for($i=1;$i<=$dir_level;$i++) {
    array_pop($self);
}
$root_url = $self ? implode("/", $self) : '/';

$save_path = $root_dir . '/uploads/attachments/';
$save_url = $root_url . '/uploads/attachments/';

include $root_dir . '/core' . '/function.php';

//文件保存目录URL
$cfg = include $root_dir . '/data/cachefiles/modoer_config.php';
if(!$cfg['editor_relativeurl']) {
    $save_url = $cfg['siteurl'] . '/uploads/attachments/';
}

//定义允许上传的文件扩展名
$ext_arr = array('gif', 'jpg', 'jpeg', 'png');
//最大文件大小
$max_size = $cfg['picture_upload_size']>0?$cfg['picture_upload_size']*1024:1000000;
//更改目录权限
@mkdir($save_path, 0777);

//有上传文件时
if (empty($_FILES) === false) {
	//原文件名
	$file_name = $_FILES['imgFile']['name'];
	//服务器上临时文件名
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//文件大小
	$file_size = $_FILES['imgFile']['size'];
	//检查文件名
	if (!$file_name) {
		alert("请选择文件。");
	}
	//检查目录
	if (@is_dir($save_path) === false) {
		alert("上传目录不存在。");
	}
	//检查目录写权限
	if (@is_writable($save_path) === false) {
		alert("上传目录没有写权限。");
	}
	//检查是否已上传
	if (@is_uploaded_file($tmp_name) === false) {
		alert("临时文件可能不是上传文件。");
	}
	//检查文件大小
	if ($file_size > $max_size) {
		alert("上传文件大小超过限制(最大 $max_size 字节)。");
	}
	//获得文件扩展名
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//检查扩展名
	if (in_array($file_ext, $ext_arr) === false) {
		alert("上传文件扩展名是不允许的扩展名。");
	}
	//创建文件夹
	$dir_mod = $cfg['picture_dir_mod'];
    if($dir_mod == 'WEEK') {
        $subdir = date('Y').'-week-'.date('W');
    } elseif($dir_mod == 'DAY') {
        $subdir = date('Y-m-d');
    } else {
        $subdir = date('Y-m');
    }
	$ymd = date("Ym");
	$save_path .= $subdir . "/";
	$save_url .= $subdir . "/";
	if (!file_exists($save_path)) {
		mkdir($save_path);
	}
	//新文件名
	$new_file_name = date("YmdHms") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//移动文件
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("上传文件失败。");
	}
	@chmod($file_path, 0644);
    //检查是否图片
    if(function_exists('getimagesize') && !@getimagesize($file_path)) {
        @unlink($file_path);
        alert('未知的图片文件。');
    }
    //打上水印
    if($cfg['watermark']) {
        include $root_dir . '/core/lib/image.php';
        $IMG = new ms_image();
        $IMG->watermark_postion = $cfg['watermark_postion'] >=5 ? 4 : $cfg['watermark_postion'];
        $wmfile = $root_dir . '/static/images/watermark.png';
        $IMG->watermark($file_path, $file_path, $wmfile);
        unset($IMG);
    }
    $file_url = $save_url . $new_file_name;

	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 0, 'url' => $file_url));
	exit;
}

function alert($msg) {
	header('Content-type: text/html; charset=UTF-8');
	$json = new Services_JSON();
	echo $json->encode(array('error' => 1, 'message' => $msg));
	exit;
}
?>