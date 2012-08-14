<?php
$menus = array (
    'home' => array('modoer',lang('admincp_menu_home')),
    'setting' => array('modoer',lang('admincp_menu_setting')),
    'website' => array('modoer',lang('admincp_menu_website')),
	'member' => array('member',lang('admincp_menu_member')),
    'item' => array('item',lang('admincp_menu_item')),
    'product' => array('product',lang('admincp_menu_product')),
    'review' => array('review',lang('admincp_menu_review')),
    'article' => array('article',lang('admincp_menu_article')),
    'module' => array('ALL',lang('admincp_menu_modules')),
    //'plugin' => array('modoer',lang('admincp_menu_plugins')),
    //'help' => array('ALL',lang('admincp_menu_help')),
);

foreach(array('product','article') as $mk) {
    if(!check_module($mk)) unset($menus[$mk]);
}

foreach($menus as $key => $value) {
    if(!$admin->check_access($value[0])) continue;
    $cpMenu[] = "'$key'";
    if($key != 'menu') {
        $param = "module=modoer&menu=cpmenu&tab=$key";
        $menuNav .= "\t".'<li class="unselected"><a href="#" onclick="return gotoMenu(this,'."'$key',"."'$param'".');" onfocus="this.blur()">'.$value[1].'</a></li>'."\r\n";
    }
}
$cpMenu = $cpMenu ? implode(",", $cpMenu) : '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<link rel="stylesheet" type="text/css" href="./static/images/admin/admin.css" />
<script type="text/javascript">
var IN_ADMIN = true;
var cpMenu = new Array(<?=$cpMenu?>);
function gotoMenu(obj, tab, param) {
    var selmenu = obj.parentNode;
    var menus = document.getElementById('menu').getElementsByTagName('li');
    if(selmenu) {
        selmenu.className = "selected";
        for(var i = 0;i < menus.length;i++) {
            if(menus[i] != selmenu) menus[i].className = "unselected";
        }
        //showSubmenu(action);
        parent.menu.location = '<?=SELF?>?module=modoer&act=cpmenu&tab=' + tab;
        //parent.main.location = '<?=SELF?>?' + param;
    }
    return false;
}
function showSubmenu(Id) {
    for(var i=0;i<cpMenu.length;i++) {
        var obj = parent.menu.document.getElementById('menu_' + cpMenu[i]);
        if(obj){
            obj.style.display = (Id==cpMenu[i]) ? 'block' : 'none';
        }
    }
}
//¸üÐÂ¿ò¼Ü
function admin_refresh() {
	if(parent) {
		parent.location.reload();
	}
}
</script>
</head>
<body style="margin:0px;">
<div id="header">
	<div class="op">
		<?=sprintf(lang('admincp_welcome'), $admin->adminname)?>
		<?if(!$admin->is_founder):?><?=sprintf(lang('admincp_current_city', $_CITY['name']))?><?endif;?>&nbsp;
		<a href="index.php" target="_blank"><?=lang('admincp_nav_index')?></a>&nbsp;
		<a href="<?=SELF?>" target="_top"><?=lang('admincp_nav_cp')?></a>&nbsp;
		<a href="#" onclick="admin_refresh();"><?=lang('admincp_nav_refresh')?></a>&nbsp;
		<a href="<?=SELF?>?logout=yes" target="_top"><?=lang('admincp_nav_logout')?></a>
	</div>
	<div id="product"><?=lang('admincp_caption')?></div>
	<div id="nav">
		<ul id="menu">
			<?=$menuNav?>
		</ul>
	</div>
	<div style="clear:both"></div>
</div>
</body>
</html>