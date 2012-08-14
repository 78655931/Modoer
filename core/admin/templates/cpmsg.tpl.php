<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<?if(DEBUG==FALSE && $url_forward) {?>
<script type="text/javascript">
window.onload = function() {
    setTimeout(do_location, 2000);
}

function do_location() {
    document.location.href = '<?=$url_forward?>';
}
</script>
<? } ?>
<style type="text/css">
.redirect_navs { list-style:none;margin:0;padding:0; }
.redirect_navs li { line-height:20px; }
.redirect_navs li a { background:url('./static/images/admin/images/arrow.gif') 0px 2px no-repeat; padding-left:15px; }
</style>
<script type="text/javascript" src="./static/javascript/jquery.js"></script>
<div id="body">
    <table cellspacing="0" cellpadding="0" align="center" style="border: 1px solid #CCCCCC;background:#FFF;width:100%;">
		<tr>
			<td width="100" align="right" valign="top" style="padding:38px 10px 0 0;" rowspan="4">
				<img src="./static/images/admin/images/information.gif" />
			</td>
			<td style="padding:32px 0;">
				<p style="margin:15px 0 10px 0;padding:0;font-size:14px;"><?=$message?></p>
				<?if($url_forward):?>
					<div style="margin:5px 0;"><a href="<?=$url_forward?>"><?=lang('global_message_des')?></a></div>
				<?endif;?>
				<?if($navs):?>
					<ul class="redirect_navs">
					<?foreach($navs as $nav):?>
					<li><a href="<?=$nav['url']?>"><?=lang($nav['name'])?></a></li>
					<?endforeach;?>
					</ul>
				<?endif;?>
			</td>
		</tr>
    </table>
</div>