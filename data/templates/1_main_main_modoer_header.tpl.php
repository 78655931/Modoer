<? !defined('IN_MUDDER') && exit('Access Denied'); ?><?php if(SCRIPTNAV != 'index' && $_G['show_sitename']):
        $_HEAD['title'] .= $_G['cfg']['titlesplit'] . $_G['cfg']['sitename'];
    endif;
    if(!$_HEAD['keywords']):
        $_HEAD['keywords'] = $_G['cfg']['meta_keywords'];
    endif;
    if(!$_HEAD['description']):
        $_HEAD['description'] = $_G['cfg']['meta_description'];
    endif;
 ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$_G['charset']?>" />
<meta http-equiv="x-ua-compatible" content="ie=7" />
<title><?=$_HEAD['title']?> - Powered by Modoer</title>
<meta name="keywords" content="<?=$_HEAD['keywords']?>,����ϵͳ" />
<meta name="description" content="<?=$_HEAD['description']?>" /><? if($_CFG['headhtml']) { ?>
<?="\r\n"?><?=$_CFG['headhtml']?><?="\r\n"?><? } ?>
<link rel="stylesheet" type="text/css" href="<?=URLROOT?>/<?=$_G['tplurl']?>css_common.css" />
<link rel="stylesheet" type="text/css" href="<?=URLROOT?>/static/images/mdialog.css" />
<script type="text/javascript" src="<?=URLROOT?>/data/cachefiles/config.js"></script>
<script type="text/javascript"><? if(!empty($MOD)) { ?>
var mod = modules['<?=$MOD['flag']?>'];<? } ?>
</script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/jquery.js"></script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/common.js"></script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/mdialog.js"></script>
</head>
<body><? if(SCRIPTNAV == 'index') { ?>
<style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_index.css");</style>
<? } elseif(SCRIPTNAV == 'assistant'||SCRIPTNAV == 'manage') { ?>
<style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_assistant.css");</style>
<? } elseif(!empty($MOD) && is_file(MUDDER_ROOT . $_G['tplurl'] . 'css_' . $MOD['flag'] . '.css')) { ?>
<style type="text/css">@import url("<?=URLROOT?>/<?=$_G['tplurl']?>css_<?=$MOD['flag']?>.css");</style><? } ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/member.js"></script><? if(!empty($MOD) && $MOD['flag']!='member' && is_file(MUDDER_ROOT . 'static/javascript/' . $MOD['flag'] . '.js')) { ?>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/<?=$MOD['flag']?>.js"></script><? } ?>
<div id="gtop">
    <div class="maintop">
        <div class="maintop-left">
            <a href="javascript:;" onclick="this.style.behavior='url(#default#homepage)';this.setHomePage('<?=$_CFG['siteurl']?>')">��Ϊ��ҳ</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript:;" onclick="window.external.addFavorite('<?=$_CFG['siteurl']?>','<?=$_CFG['sitename']?>');">�����ղ�</a>
        </div>
        <div class="maintop-right">
            <a href="<?php echo url("item/map"); ?>">�����ͼ</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo url("item/detail/random/1"); ?>">��㿴��</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo url("item/member/ac/subject_add"); ?>">��������</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo url("review/member/ac/add"); ?>">��Ҫ����</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo url("item/search"); ?>">����</a>
        </div>
        <div class="clear"></div>
    </div>
</div>
<div id="header">
    <div class="mainmenu">
        <div class="logo">
            <a href="<?php echo url("modoer/index"); ?>"><img src="<?=URLROOT?>/static/images/logo.jpg" alt="<?=$_CFG['sitename']?>" title="<?=$_CFG['sitename']?>"></a>
        </div>
		<div class="nav_citys">
			<h2><?=$_CITY['name']?></h2>
			<span><a href="#" id="city_menu_src" rel="city_menu_box">[�л�����]</a></span>
			<div class="clear"></div>
		</div>
        <div class="charmenu">
            <? if($_GET['act']!='reg'&&$_GET['act']!='login') { ?>
            <div id="login_0" style="display:none;">
                <? if($_G['passport_apis']) { ?>
                <div class="passport_login">
                    <span class="passport_api" id="passport_api">
                        <?php $ix=0; ?>                        
<? if(is_array($_G['passport_apis'])) { foreach($_G['passport_apis'] as $passport_name => $passport_title) { ?>
                        <span <? if($ix++) { ?>
 class="none" <? } ?>
 onclick="document.location='<?php echo url("member/login/op/passport_login/type/{$passport_name}"); ?>';"><img src="<?=URLROOT?>/static/images/passport/<?=$passport_name?>_n.png" /><?=$passport_title?>��¼</span>
                        
<? } } ?>
                        <div class="clear"></div>
                    </span>
                    <span>�ʺŻ��������ٵ�¼</span>
                </div>
                <? } ?>
                <div class="mainlogin">
                    <?php $membercfg=$_G['loader']->variable('config','member'); ?>                    <form method="post" id="main_frm_login" action="<?php echo url("member/login/op/login/rand/{$_G['random']}"); ?>">
                    <dl>
                        <dd>
                            <input type="text" name="username" id="main_username" class="t_input mainlogin-u" onfocus="main_username_check(this);" onblur="main_username_check(this);" tabindex=1>
                            <input type="checkbox" name="life" value="2592000" tabindex=4 /><label for="life">��ס</label>&nbsp;
                            <a href="<?php echo url("member/login/op/forget"); ?>">�һ�����</a>
                        </dd>
                        <dd>
                            <input type="password" name="password" id="main_password" class="t_input mainlogin-p"<? if($membercfg['seccode_login']) { ?>
onfocus="main_show_seccode(this,'main_frm_seccode');"<? } ?>
 tabindex=2>&nbsp;
                            <input type="hidden" name="onsubmit" value="yes">
                            <input type="button" value="��¼" onclick="main_login();" tabindex=5>&nbsp;
                            <input type="button" value="ע��" onclick="document.location='<?php echo url("member/reg"); ?>';" tabindex=6>
                        </dd>
                    </dl>
                    <? if($membercfg['seccode_login']) { ?>
                    <div id="main_frm_seccode" style="padding:5px;border:1px solid #ddd; background:#fff;position:absolute; left:0; top:0; visibility:hidden;">
                        <input type="text" name="seccode" id="main_seccode" class="t_input" style="width:118px;" onblur="check_seccode(this.value);" tabindex=3 /><div id="login_seccode" style="margin-top:5px;"></div>
                    </div>
                    <? } ?>
                    </form>
                </div>
            </div>
            <? } ?>
            <div id="login_1" class="mainuser" style="display:none;">
                <div class="mainuser-face">
                    <a href="<?php echo url("space"); ?>"><img src="<?php echo get_face($user->uid); ?>" title="���˿ռ�" /></a>
                </div>
                <div class="mainuser-operation">
                    <div class="mainuser-operation-foo"><span class="member-ico"><a href="<?php echo url("space"); ?>" id="login_name"></a></span><span class="xsplit">|</span><span class="arrwd-ico" id="assistant_menu" rel="assistant_menu_box"><a href="<?php echo url("member/index"); ?>">�ҵ�����</a></span><span class="xsplit">|</span><a href="<?php echo url("item/member/ac/g_subject"); ?>">�������</a><span class="xsplit">|</span><span><a href="<?php echo url("member/index/ac/task"); ?>">����</a><span id="login_task" style="display:none;"></span></span><span class="xsplit">|</span><a href="<?php echo url("member/index/ac/pm/folder/inbox"); ?>">������</a><span id="login_newmsg" style="display:none;">(0)</span><span class="xsplit">|</span><span><a href="<?php echo url("member/index/ac/notice"); ?>">����</a><span id="login_notice" style="display:none;"></span></span><span class="xsplit">|</span><a href="<?php echo url("member/login/op/logout"); ?>">�˳�</a></div>
                    <div>�ȼ�����<b>:</b><span class="arrwd-ico" id="assistant_point" rel="<?php echo url("member/index/ac/point/op/headerget"); ?>"><span id="login_point"><?=$user->point?></span></span><span class="xsplit">|</span>�û���<b>:</b><span id="login_group"><?php echo template_print('member','group',array('groupid'=>"{$user->groupid}",));?></span></div>
                </div>
            </div>
            <div id="login_2" style="display:none;">
                <span id="login_activation"></span>,<a href="<?php echo url("ucenter/activation/auth/_activationauth_"); ?>" id="login_activation_a">�����ʺ���Ҫ����</a> [<a href="<?php echo url("member/login/op/logout"); ?>">�˳�</a>]
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <?php $main_menus = $_CFG['main_menuid'] ? $_G['loader']->variable('menu_' . $_CFG['main_menuid']) : ''; ?>    <ul class="tabmenu">
        
<? if(is_array($main_menus)) { foreach($main_menus as $val) { ?>
        <li<? if(SCRIPTNAV==$val['scriptnav']) { ?>
 class="current"<? } ?>
><a href="<?php echo url("{$val['url']}"); ?>"<? if($val['target']) { ?>
 target="<?=$val['target']?>"<? } ?>
 onfocus="this.blur()"><?=$val['title']?></a></li>
        
<? } } ?>
    </ul>
    <div class="search">
        <form method="get" action="<?=URLROOT?>/index.php">
        <input type="hidden" name="act" value="search" />
        <input type="hidden" name="ordersort" value="addtime" />
        <input type="hidden" name="ordertype" value="desc" />
        <input type="hidden" name="searchsubmit" value="yes" />
        <select name="module_flag">
          <option value="item"<? if($MOD['flag']=='item') { ?>
selected="selected"<? } ?>
>����</option>
          <option value="product"<? if($MOD['flag']=='product') { ?>
selected="selected"<? } ?>
>��Ʒ</option>
          <option value="review"<? if($MOD['flag']=='review') { ?>
selected="selected"<? } ?>
>����</option>
          <option value="article"<? if($MOD['flag']=='article') { ?>
selected="selected"<? } ?>
>��Ѷ</option>
          <option value="coupon"<? if($MOD['flag']=='coupon') { ?>
selected="selected"<? } ?>
>�Ż�ȯ</option>
        </select>
        &nbsp;
        <input type="text" name="keyword" value="" class="input" x-webkit-speech="x-webkit-speech" />&nbsp;
        <input type="image" src="<?=URLROOT?>/<?=$_G['tplurl']?>img/search.png" class="btn" title="����" />&nbsp;
        </form>
        <div class="s_right">
            <a href="<?php echo url("member/login"); ?>" id="login_btn_0" class="none"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>img/login.png" title="��Ա��¼" alt="login" class="btn" /></a>&nbsp;
            <a href="<?php echo url("item/tag"); ?>"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>img/tag.png" title="TAG��ǩ" alt="tag" class="btn" /></a>&nbsp;
            <a href="<?php echo url("item/rss/catid/{$catid}"); ?>" target="_blank"><img src="<?=URLROOT?>/<?=$_G['tplurl']?>img/rss.png" title="RSS�ۺ�" alt="rss" class="btn" /></a>
        </div>
    </div>
</div>
<!-- �л����� �����˵� -->
<div id="city_menu_box" class="comm-dropdown-city">
    <ul>
        <?php $index=0; ?>        
<?php $_QUERY['get_val']=$_G['datacall']->datacall_get('citys',array('num'=>9,),'');
if(is_array($_QUERY['get_val']))foreach($_QUERY['get_val'] as $val_k => $val) { ?>
        <li<? if($_CITY['aid']==$val['aid']) { ?>
 class="current"<? } ?>
><a href="<?php echo template_print('','cityurl',array('city_id'=>$val['aid'],));?>"><?=$val['name']?></a></li>
        <?php $index++; ?>        <?php } ?>
    </ul>
    <? if($index>1) { ?>
<div class="morecitys"><a href="<?php echo url("index/city"); ?>">ȫ������</a></div><? } ?>
</div><? if($index) { ?>
<script type="text/javascript">
$("#city_menu_src").powerFloat({offsets:{x:0,y:2}});
</script><? } ?>
<!-- �ҵ����� �����˵� -->
<ul id="assistant_menu_box" class="dropdown-menu">
    <li><a href="<?php echo url("member/index/ac/point"); ?>">�ҵĻ���</a></li>
    <li><a href="<?php echo url("member/index/ac/follow"); ?>">�ҵĹ�ע</a></li>
    <li><a href="<?php echo url("member/index/ac/task"); ?>">�ҵ�����</a></li>
    <li><a href="<?php echo url("member/index/ac/myset"); ?>">��������</a></li>
    <li><a href="<?php echo url("member/index/ac/face"); ?>">�޸�ͷ��</a></li>
</ul>
<!-- �������� -->
</script>
<script type="text/javascript" src="<?=URLROOT?>/static/javascript/login.js"></script>
<?="\r\n"?>