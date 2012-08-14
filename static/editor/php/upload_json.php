<?php
/**
 * KindEditor PHP
 * http://www.kindsoft.net/
 */

define("IN_MUDDER", TRUE);
require_once 'JSON.php';

//���Ŀ¼����Ŀ¼�ȼ�
$dir_level = 3; 

//�ļ�����Ŀ¼·��
$root_dir = dirname(__FILE__);
for($i=1; $i<=$dir_level; $i++) {
    $root_dir = dirname($root_dir);
}
//URL��Ը�Ŀ¼
$self = explode("/",dirname($_SERVER['PHP_SELF']));
for($i=1;$i<=$dir_level;$i++) {
    array_pop($self);
}
$root_url = $self ? implode("/", $self) : '/';

$save_path = $root_dir . '/uploads/attachments/';
$save_url = $root_url . '/uploads/attachments/';

include $root_dir . '/core' . '/function.php';

//�ļ�����Ŀ¼URL
$cfg = include $root_dir . '/data/cachefiles/modoer_config.php';
if(!$cfg['editor_relativeurl']) {
    $save_url = $cfg['siteurl'] . '/uploads/attachments/';
}

//���������ϴ����ļ���չ��
$ext_arr = array('gif', 'jpg', 'jpeg', 'png');
//����ļ���С
$max_size = $cfg['picture_upload_size']>0?$cfg['picture_upload_size']*1024:1000000;
//����Ŀ¼Ȩ��
@mkdir($save_path, 0777);

//���ϴ��ļ�ʱ
if (empty($_FILES) === false) {
	//ԭ�ļ���
	$file_name = $_FILES['imgFile']['name'];
	//����������ʱ�ļ���
	$tmp_name = $_FILES['imgFile']['tmp_name'];
	//�ļ���С
	$file_size = $_FILES['imgFile']['size'];
	//����ļ���
	if (!$file_name) {
		alert("��ѡ���ļ���");
	}
	//���Ŀ¼
	if (@is_dir($save_path) === false) {
		alert("�ϴ�Ŀ¼�����ڡ�");
	}
	//���Ŀ¼дȨ��
	if (@is_writable($save_path) === false) {
		alert("�ϴ�Ŀ¼û��дȨ�ޡ�");
	}
	//����Ƿ����ϴ�
	if (@is_uploaded_file($tmp_name) === false) {
		alert("��ʱ�ļ����ܲ����ϴ��ļ���");
	}
	//����ļ���С
	if ($file_size > $max_size) {
		alert("�ϴ��ļ���С��������(��� $max_size �ֽ�)��");
	}
	//����ļ���չ��
	$temp_arr = explode(".", $file_name);
	$file_ext = array_pop($temp_arr);
	$file_ext = trim($file_ext);
	$file_ext = strtolower($file_ext);
	//�����չ��
	if (in_array($file_ext, $ext_arr) === false) {
		alert("�ϴ��ļ���չ���ǲ��������չ����");
	}
	//�����ļ���
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
	//���ļ���
	$new_file_name = date("YmdHms") . '_' . rand(10000, 99999) . '.' . $file_ext;
	//�ƶ��ļ�
	$file_path = $save_path . $new_file_name;
	if (move_uploaded_file($tmp_name, $file_path) === false) {
		alert("�ϴ��ļ�ʧ�ܡ�");
	}
	@chmod($file_path, 0644);
    //����Ƿ�ͼƬ
    if(function_exists('getimagesize') && !@getimagesize($file_path)) {
        @unlink($file_path);
        alert('δ֪��ͼƬ�ļ���');
    }
    //����ˮӡ
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