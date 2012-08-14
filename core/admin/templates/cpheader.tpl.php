<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>">
<meta http-equiv="x-ua-compatible" content="ie=7" />
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css">
<?if($js) {?>
<script type="text/javascript" src="./data/cachefiles/config.js"></script>
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<script type="text/javascript" src="./static/javascript/common.js"></script>
<script type="text/javascript" src="./static/javascript/mdialog.js"></script>
<script type="text/javascript" src="./static/javascript/admin.js"></script>
<link rel="stylesheet" type="text/css" href="./static/images/mdialog.css">
<script type="text/javascript">
var IN_ADMIN = true;
$(document).ready(function() {
	$(document).keydown(resetEscAndF5);
});
</script>
<?}?>
</head>
<body>