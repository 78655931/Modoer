<?php
$url=$_SERVER['HTTP_HOST'] ;  //»ñÈ¡ÓòÃû
$allow =array('127.0.0.1','localhost/Modoer');

define('IN_ADMIN', TRUE); define('SCRIPTNAV', 'admincp'); 
define('IN_ENCODE', '20111228'); 
require dirname(__FILE__).'/core/init.php'; 
$_G['loader']->helper('admincp'); 
define('MUDDER_ADMIN', MUDDER_CORE . 'admin' . DS); 
$_G['loader']->model('admin',FALSE); 
$_G['admin'] =& $_G['loader']->model('cpuser'); 
$admin =& $_G['admin']; 
$admin->licensed = cp_licck(); 

if(_get('logout')) 
{ 
	$admin->logout(); exit; 
} 

if(empty($admin->access)) 
{ 
	if(!$_POST['loginsubmit']) 
    { 
	    include MUDDER_ADMIN.'cplogin.inc.php'; 
		exit; 
    } 
	else 
	{ 
		$admin->login(); 
	} 
} 
elseif($admin->access == '1') 
{ 
	if(!_post('admin_pw') || (md5(_post('admin_pw')) != $admin->password)) 
	{ 
		include MUDDER_ADMIN.'cplogin.inc.php'; 
		exit; 
	} 
	else 
	{
		$admin->update_sessions(); 
		redirect('admincp_login_wait', SELF); 
	}
} 
elseif($admin->access == '2') 
{ 
	redirect('admincp_login_op_without', SELF.'?logout=yes'); 
} 
elseif($admin->access == '3') 
{ 
	redirect('admincp_cpuser_colsed', SELF.'?logout=yes'); 
} 
elseif($admin->access == '4') 
{ 
	redirect(lang('admincp_cpuser_city_access',$_CITY['name']), SELF.'?logout=yes'); 
} 
		
if(empty($admin->id) || $admin->id < 0 || !$admin->isLogin) 
{ 
	redirect('admincp_not_login', SELF); 
} 
$module = _input('module'); $act = _input('act'); 
$in_ajax = 0; $in_ajax = _input('in_ajax'); 
/**		
if(!cp_licck() && $_GET['act'] && !in_array($_GET['act'], array('cpmenu','cphome','cpheader','license'))) 
{ 
	redirect('admincp_license_empty', cpurl('modoer','cphome')); 
} 
**/
$_G['loader']->helper('form'); 
		
if(empty($module) || $module == 'modoer') 
{ 
	$module = 'modoer';
    if(empty($act)) 
    { 
		$tab = 'home'; 
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html><head>

<title><?=lang('admincp_title')?></title>

<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>">

<script type="text/javascript" src="./static/javascript/jquery.js"></script>

<script type="text/javascript" src="./static/javascript/admin.js"></script>

<script type="text/javascript">

var IN_ADMIN = true;

$(document).ready(function() {

	$(document).keydown(resetEscAndF5);

});

</script>

</head>

<body style="margin: 0px" scroll="no">

<div style="position: absolute;top: 0px;left: 0px; z-index: 2;height: 55px;width: 100%">

<iframe frameborder="0" id="header" name="header" src="<?=cpurl('modoer','cpheader')?>" scrolling="no" style="height: 55px; visibility: inherit; width: 100%; z-index: 1;"></iframe>

</div>

<table border="0" cellPadding="0" cellSpacing="0" height="100%" width="100%" style="table-layout: fixed;">

<tr><td width="173" height="55"></td><td></td></tr>

<tr>

<td width="173"><iframe frameborder="0" id="menu" name="menu" src="<?=SELF?>?module=modoer&act=cpmenu&tab=<?=$tab?>" scrolling="auto" style="height:100%;visibility:inherit;width:100%;z-index:1;overflow-x:hidden;overflow-y:auto; "></iframe></td>

<td width="*"><iframe frameborder="0" id="main" name="main" src="<?=cpurl('modoer','cphome')?>" scrolling="yes" style="height: 100%; visibility: inherit; width: 100%; z-index: 1;overflow: auto;"></iframe></td>

</tr></table>

</body>

</html>

<?php
        exit(0); 
	}
    if($act=='cphome') $___include_js = TRUE; 
	if(!$admin->check_access('modoer') && !in_array($act, array('cpheader','cpmenu','cphome','help','admin'))) redirect('global_op_access'); 
	if($act == 'license') 
	{ 
	    cp_licensepost(); 
    } 
    else 
    { 
	    $actfile = MUDDER_ADMIN . $act . '.inc.php'; 
	    if(!is_file($actfile)) show_error(lang('global_file_not_exist', '[ADMIN_DIR]' . DS . $act . '.inc.php')); 
	    include $actfile; 
    } 
    $acts = array('cpheader','cpmenu'); 
    if(!$in_ajax && !in_array($act,$acts)) cpheader(); 
    if($admin->tplname) 
    { 
	    if(!is_file(MUDDER_CORE . $admin->tplname)) 
	    { 
		    show_error(sprintf(lang('global_file_not_exist'), $admin->tplname)); 
	    } 
	    include MUDDER_CORE . $admin->tplname; 
    } 
    if(!$in_ajax && !in_array($act,$acts)) cpfooter(); 
    if($___include_js) 
    { 
	    $output = ob_get_contents(); 
	    ob_end_clean(); 
		$urls = str_replace('&amp;','&',http_query(cp_getmodoerinfo())); 
	    $jssrc = "<script type=\"text/javascript\" src=\"http://www.modoer.cn/version.php?$urls\"></script>"; 
	    if($i = strrpos($output, '</body>')) 
	    { 
		    $output = substr($output,0,$i) . $jssrc . substr($output,$i); 
	    } 
	    else 
	    { 
	        $output .= $jssrc; 
	    } 
	    $_G['cfg']['gzipcompress'] ? @ob_start('ob_gzhandler') : ob_start(); 
	    echo $output; exit; 
    } 
} 
elseif(isset($_G['modules'][$module])) 
{ 
	if(!$admin->check_access($module)) redirect('global_op_access'); $adminfile_path = 'modules' . DS . $module; require_once MUDDER_CORE . $adminfile_path . DS . 'common.php'; 
	if(preg_match("/^[0-9a-z\_\.]+$/i", $act)) 
	{  
		$actfile = MOD_ROOT . 'admin' . DS . $act.'.inc.php'; 
		if(!is_file($actfile)) 
		{ 
			show_error(lang('global_file_not_exist', $_G['modules'][$module]['directory'] . DS . 'admin' . DS . $act . '.inc.php')); 
		} 
		include $actfile; 
		if(!$in_ajax) cpheader(); 
		if($admin->tplname) 
		{ 
		    if(!is_file(MUDDER_CORE . $admin->tplname)) 
			{ 
			    show_error(lang('global_file_not_exist', $admin->tplname)); 
				include MUDDER_CORE . $admin->tplname; 
			} 
			include MUDDER_CORE . $admin->tplname; 
		} 
		if(!$in_ajax) cpfooter(); 
	} 
	else 
	{ 
		show_error(lang('global_op_unkown')); 
	} 
} 
else 
{ 
	show_error(lang('global_not_found_module', $module)); 
} 
	
function cp_getmodoerinfo() 
{
	global $_G,$_MODULES; $params = array(); 
	$params['v'] = $_G['modoer']['version']; 
	$params['b'] = $_G['modoer']['build']; 
	$params['m'] = $_G['db']->version(); 
	$params['d'] = get_fl_domain(); 
	$params['t'] = _G('cfg','sitename'); 
	$params['w'] = $_SERVER['SERVER_SOFTWARE']; 
	$params['c'] = _G('charset'); $params['p'] = phpversion(); 
	$params['ms'] = implode(',', array_keys($_MODULES)); 
	return $params;
} 
	
function cp_licensepost() 
{ 
	global $_G; 
	if(empty($_FILES['licfile'])) 
	{ 
		redirect('global_upload_error_4'); 
	} 
	elseif($_FILES['licfile']['error'] > 0) 
	{ 
		redirect('global_upload_error_'.$_FILES['licfile']['error']); 
	} 
	elseif(!is_uploaded_file($_FILES['licfile']['tmp_name'])) 
	{ 
		redirect('global_upload_unkown'); 
	} 
	if(!$licstr = file_get_contents($_FILES['licfile']['tmp_name'])) redirect('admincp_license_code_empty'); 
	if(!cp_licck($licstr)) redirect('admincp_license_code_invalid'); 
	$licstr = "<?php exit();?>\r\n" . $licstr; 
	$licfilename = 'lic_'.random(8,'NUM').'.php'; 
	file_put_contents(MUDDER_DATA . $licfilename, $licstr); 
	$config = $_G['loader']->model('config'); 
	$config->save(array('liccode' => $licfilename));
	redirect('global_op_succeed', cpurl($module,'cphome')); 
} 
	
function cp_licck($str=null) 
{ 
	global $_G; 
	$CODE =& $_G['loader']->lib('liccode'); 
	if(cplocalck())return true; 
	if(!$str) 
	{ 
	    if($licfielname = $_G['cfg']['liccode']) 
		{
			if(is_file(MUDDER_DATA . $licfielname)) 
			{
				$str = @file_get_contents(MUDDER_DATA . $licfielname); 
				$str = str_replace("<?php exit();?>\r\n", '', $str); 
			} 
		} 
	} 
	if(!$str) return false; 
	if(!defined('IN_LICCODE')) 
	{ 
	    cp_license_delete(); return false; 
	} 
	list($info,,,) = $CODE->decode($str); 
	list($domain,$adminid,$bbsuid,$dateline,$expiry) = explode('|', $info); $$dateline=abs((int)$dateline);
	$expiry=abs((int) $expiry); 
	if(!$dateline || !$expiry) return false; 
	$cur_expiry = $_G['timestamp'] - $dateline; 
	if($cur_expiry < 0) return false; if($cur_expiry > ($expiry*24*3600)) return false; 
	if(cpequalck($domain)) return array('licensed'=>true,'domain'=>$domain,'bbsuid'=>$bbsuid,'starttime'=>$dateline,'expiry'=>$expiry,); 
	return false; 
} 
	
function cplocalck() 
{ 
    $cd = get_current_domain(); 
	$lt = array('localhost','127.0.0.1'); 
	return in_array(strtolower($cd), $lt); 
} 
	
function cpequalck($dm) 
{ 
    $dm = strtolower($dm); 
	$domain = get_fl_domain(); 
	if($domain == $dm) return true; 
	if(strlen($dm) > strlen($domain)) if(substr($dm,-(strlen($domain)+1)) == '.' . $domain) return true; 
	if(strlen($domain) > strlen($dm)) if(substr($domain,-(strlen($dm)+1)) == '.' . $dm) return true; return false; 
} 
	
function cp_license_delete() 
{
	global $_G; 
	if($licfielname = $_G['cfg']['liccode']) 
	{
		if(is_file(MUDDER_DATA . $licfielname)) 
		{
			@unlink(MUDDER_DATA . $licfielname); 
		} 
		$_G['db']->from('dbpre_config');
		$_G['db']->set('module', 'modoer'); 
		$_G['db']->set('variable', 'liccode');
		$_G['db']->set('value', ''); $_G['db']->insert(1); 
	} 
} 
	
?>
