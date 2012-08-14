<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=lang('admincp_title')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<link href="./static/images/admin/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
var siteurl = "<?=$_config['siteurl']?>";
if(self.parent.frames.length != 0) {
	self.parent.location = document.location;
}
function redirect(url) {
	window.location.replace(url);
}
</script>
<script type="text/javascript" src="./data/cachefiles/config.js"></script>
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<script type="text/javascript" src="./static/javascript/common.js"></script>
</head>
<body>
<form method="post" action="<?=SELF?>?" name="login">
<input type="hidden" name="url_forward" value="<?=$url_forward?>" />
<?if(!$admin->isLogin):?>
<input type="hidden" name="adminlogin" value="1" />
<?endif?>
<table width="600" border="0" cellpadding="8" cellspacing="0" class="login_table">
    <tr><td colspan="2" class="table_title"><?=lang('admincp_caption')?></td></tr>
    <tr><td colspan="2" height="50"></td></tr>
    <tr>
        <td width="250" align="right"><?=lang('admincp_tpl_login_user')?></td>
        <td width="350">
            <?if($admin->isLogin):?>
                <?=$admin->adminname?>&nbsp;&nbsp;[&nbsp;<a href="<?=SELF?>?logout=yes"><?=lang('admincp_nav_logout')?></a>&nbsp;]
            <?else:?>
                <input type="text" name="admin_name" style="width:150px;" />
            <?endif?>
        </td>
    </tr>
    <tr>
        <td align="right"><?=lang('admincp_tpl_login_pw')?></td>
        <td><input type="password" name="admin_pw" style="width:150px;" /></td>
    </tr>
    <?if(_G('cfg','console_seccode')) :?>
    <tr>
        <td align="right"><?=lang('admincp_tpl_login_secode')?></td>
        <td>
            <div id="seccode"></div>
            <input type="text" name="seccode" onfocus="show_seccode();" style="width:150px;" />
        </td>
    </tr>
    <?endif?>
    <tr>
        <td></td>
        <td>
            <input type="submit" name="loginsubmit" class="button" value="<?=lang('admincp_tpl_login_btn')?>" />
            <script type="text/javascript">
                if(document.login.admin_name) document.login.admin_name.focus();
            </script>
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center" height="50">
            <SCRIPT LANGUAGE=Javascript>
var _4cad=["\x3c\x66\x6f\x6e\x74\x20\x63\x6f\x6c\x6f\x72\x3d\x62\x6c\x75\x65\x3e\u7a0b\u5e8f\u63d0\u4f9b\uff1a\x3c\x2f\x66\x6f\x6e\x74\x3e\x3c\x61\x20\x68\x72\x65\x66\x3d\'\x68\x74\x74\x70\x3a\x2f\x2f\x77\x77\x77\x2e\x73\x6f\x75\x68\x6f\x2e\x6e\x65\x74\'\x20\x74\x61\x72\x67\x65\x74\x3d\'\x5f\x62\x6c\x61\x6e\x6b\'\x3e\x3c\x75\x3e\x3c\x66\x6f\x6e\x74\x20\x63\x6f\x6c\x6f\x72\x3d\x72\x65\x64\x3e\u641c\u864e\u7cbe\u54c1\u793e\u533a\x3c\x2f\x66\x6f\x6e\x74\x3e\x3c\x2f\x75\x3e\x3c\x2f\x61\x3e"];window["\x64\x6f\x63\x75\x6d\x65\x6e\x74"]["\x77\x72\x69\x74\x65\x6c\x6e"](_4cad[0x0]);
</SCRIPT>
        </td>
    </tr>
</table>
</form>
</body>
</html>