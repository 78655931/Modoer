<?php
/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
!defined('IN_MUDDER') && exit('Access Denied');
define('SCRIPTNAV', 'coupon');

if(isset($_GET['id'])) $couponid = (int) $_GET['id'];
if(!$couponid) redirect(lang('global_sql_keyid_invaild','id'));

$CO = $_G['loader']->model(':coupon');
$detail = $CO->read($couponid);
if(!$detail || $detail['status']!=1) redirect('coupon_empty');

$CO->print_coupon($couponid);

$pic = url('modoer','',1) . $detail['picture'];
empty($pic) && redirect('coupon_print_pic_empty');

echo'
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset='.$_G['charset'].'" />
<title> 打印图片 </title>
<meta name="author" content="modoer.com" />
<meta name="keywords" content="moufer" />
<meta name="description" content="moufer studio" />
</head>
<body>
';
echo "<img src=\"$pic\" title=\"点击图片进行打印\" onclick=\"window.window.print();\" style=\"cursor:pointer;\" />";
echo 
"<script type=\"text/javascript\">\r\n
window.onload = function () {
    window.window.print();
}
</script>\r\n";
echo'</body></html>';

//footer();
?>