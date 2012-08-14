<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.maintable td img { max-width:700px; max-height:150px; 
    _width:expression(this.width > 700 ? 700 : true); _height:expression(this.height > 150 ? 150 : true); }
</style>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">横幅管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_setting_banner')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        	<?if($banner):?>
        	<tr>
        		<td align="center"><img src="<?=URLROOT.'/'.$banner?>" /></td>
        	</tr>
        	<?endif;?>
        	<tr>
        		<td>
        			<div>重新上传：<input type="file" name="picture" /></div>
        			<div>
        				<span class="font_2">横幅规定尺寸：<?=$banner_width?>（宽） × <?=$banner_height?>（高）；最大允许上传的图片大小<span class="font_2"><?=$_CFG[picture_upload_size]?>KB</span>，允许上传的图片格式 <?=$_CFG[picture_ext]?></span>
                    </div>
        		</td>
        	</tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="sid" value="<?=$sid?>">
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update_banner" />
		<button type="button" class="btn" onclick="easy_submit('myform','update_banner',null)">上传</button>&nbsp;
		<?if($banner):?>
		<button type="button" class="btn" onclick="easy_submit('myform','delete_banner',null)">删除Banner</button>
		<?endif;?>
	</center>
</form>
</div>