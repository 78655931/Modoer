<?php
/**
 * KindEditor PHP
 * 
 * ��PHP��������ʾ���򣬽��鲻Ҫֱ����ʵ����Ŀ��ʹ�á�
 * �����ȷ��ֱ��ʹ�ñ�����ʹ��֮ǰ����ϸȷ����ذ�ȫ���á�
 * 
 */

require_once 'JSON.php';
 
$php_path = dirname(__FILE__) . '/';
$php_url = dirname($_SERVER['PHP_SELF']) . '/';

//��Ŀ¼·��������ָ������·�������� /var/www/attached/
$root_path = $php_path . '../attached/';
//��Ŀ¼URL������ָ������·�������� http://www.yoursite.com/attached/
$root_url = $php_url . '../attached/';
//ͼƬ��չ��
$ext_arr = array('gif', 'jpg', 'jpeg', 'png', 'bmp');

//����path���������ø�·����URL
if (empty($_GET['path'])) {
	$current_path = realpath($root_path) . '/';
	$current_url = $root_url;
	$current_dir_path = '';
	$moveup_dir_path = '';
} else {
	$current_path = realpath($root_path) . '/' . $_GET['path'];
	$current_url = $root_url . $_GET['path'];
	$current_dir_path = $_GET['path'];
	$moveup_dir_path = preg_replace('/(.*?)[^\/]+\/$/', '$1', $current_dir_path);
}
//������ʽ��name or size or type
$order = empty($_GET['order']) ? 'name' : strtolower($_GET['order']);

//������ʹ��..�ƶ�����һ��Ŀ¼
if (preg_match('/\.\./', $current_path)) {
	echo 'Access is not allowed.';
	exit;
}
//���һ���ַ�����/
if (!preg_match('/\/$/', $current_path)) {
	echo 'Parameter is not valid.';
	exit;
}
//Ŀ¼�����ڻ���Ŀ¼
if (!file_exists($current_path) || !is_dir($current_path)) {
	echo 'Directory does not exist.';
	exit;
}

//����Ŀ¼ȡ���ļ���Ϣ
$file_list = array();
if ($handle = opendir($current_path)) {
	$i = 0;
	while (false !== ($filename = readdir($handle))) {
		if ($filename{0} == '.') continue;
		$file = $current_path . $filename;
		if (is_dir($file)) {
			$file_list[$i]['is_dir'] = true; //�Ƿ��ļ���
			$file_list[$i]['has_file'] = (count(scandir($file)) > 2); //�ļ����Ƿ�����ļ�
			$file_list[$i]['filesize'] = 0; //�ļ���С
			$file_list[$i]['is_photo'] = false; //�Ƿ�ͼƬ
			$file_list[$i]['filetype'] = ''; //�ļ��������չ���ж�
		} else {
			$file_list[$i]['is_dir'] = false;
			$file_list[$i]['has_file'] = false;
			$file_list[$i]['filesize'] = filesize($file);
			$file_list[$i]['dir_path'] = '';
			$file_ext = strtolower(array_pop(explode('.', trim($file))));
			$file_list[$i]['is_photo'] = in_array($file_ext, $ext_arr);
			$file_list[$i]['filetype'] = $file_ext;
		}
		$file_list[$i]['filename'] = $filename; //�ļ�����������չ��
		$file_list[$i]['datetime'] = date('Y-m-d H:i:s', filemtime($file)); //�ļ�����޸�ʱ��
		$i++;
	}
	closedir($handle);
}

//����
function cmp_func($a, $b) {
	global $order;
	if ($a['is_dir'] && !$b['is_dir']) {
		return -1;
	} else if (!$a['is_dir'] && $b['is_dir']) {
		return 1;
	} else {
		if ($order == 'size') {
			if ($a['filesize'] > $b['filesize']) {
				return 1;
			} else if ($a['filesize'] < $b['filesize']) {
				return -1;
			} else {
				return 0;
			}
		} else if ($order == 'type') {
			return strcmp($a['filetype'], $b['filetype']);
		} else {
			return strcmp($a['filename'], $b['filename']);
		}
	}
}
usort($file_list, 'cmp_func');

$result = array();
//����ڸ�Ŀ¼����һ��Ŀ¼
$result['moveup_dir_path'] = $moveup_dir_path;
//����ڸ�Ŀ¼�ĵ�ǰĿ¼
$result['current_dir_path'] = $current_dir_path;
//��ǰĿ¼��URL
$result['current_url'] = $current_url;
//�ļ���
$result['total_count'] = count($file_list);
//�ļ��б�����
$result['file_list'] = $file_list;

//���JSON�ַ���
header('Content-type: application/json; charset=UTF-8');
$json = new Services_JSON();
echo $json->encode($result);
?>
