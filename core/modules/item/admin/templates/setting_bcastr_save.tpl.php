<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.maintable td img { max-width:270px; max-height:100px; 
	_width:expression(this.width > 270 ? 270 : true); _height:expression(this.height > 100 ? 100 : true); }
</style>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save_bcastr')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">上传橱窗图片</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_setting_bcastr')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr>
            	<td width="80" class="altbg1">标题：</td>
                <td width="320">
                    <input type="text" name="title" id="title" value="<?=$detail[title]?>" class="txtbox2">
                </td>
                <td width="*" class="font_2">填写橱窗图片标题</td>
            </tr>
            <tr>
            	<td class="altbg1">排序：</td>
                <td>
                    <input type="text" name="listorder" id="listorder" value="<?=$detail[listorder]?>" class="txtbox2">
                </td>
                <td class="font_2">图片轮换显示的顺序</td>
            </tr>
            <tr>
            	<td class="altbg1">链接：</td>
                <td>
                    <input type="text" name="url" id="url" value="<?=$detail[url]?$detail[url]:'http://'?>" class="txtbox2">
                </td>
                <td class="font_2">填写图片点击后的网页链接；开头必须加http://</td>
            </tr>
            <tr>
            	<td class="altbg1">图片：</td>
                <td>
                    <?if($detail[picture]):?>
                    <div><img src="<?=URLROOT.'/'.$detail[picture]?>" /></div>
                    <?endif;?>
                    <div><input type="file" name="picture" id="picture"></div>
                </td>
                <td class="font_2">本地选择图片上传为橱窗图片；图片规定尺寸：<?=$bcastr_width?>px（宽） × <?=$bcastr_height?>px（高）</td>
            </tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="sid" value="<?=$sid?>">
		<?if($flag):?><input type="hidden" name="flag" value="<?=$flag?>" /><?endif;?>
		<input type="hidden" name="op" value="update_banner" />
		<button type="button" class="btn" onclick="easy_submit('myform','save_bcastr',null)">提交</button>
		<button type="button" class="btn" onclick="history.go(-1);">返回</button>
	</center>
</form>
</div>