<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.maintable td img { max-width:270px; max-height:100px; 
	_width:expression(this.width > 270 ? 270 : true); _height:expression(this.height > 100 ? 100 : true); }
</style>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'')?>">
    <div class="space">
        <div class="subtitle">橱窗管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_setting_bcastr')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="65">排序</td>
                <td width="300">图片</td>
                <td width="*">标题/链接</td>
                <td width="80">操作</td>
            </tr>
            <?foreach($bcastrs as $flag => $val):?>
		    <tr id="f_<?=$flag?>">
                <td><input type="text" name="bcastrs[<?=$flag?>][listorder]" value="<?=$val[listorder]?>" class="t_input" size="1"></td>
                <td><img src="<?=URLROOT.'/'.$val[picture]?>" /></td>
                <td><?=$val[title]?><br /><?=$val[url]?></td>
                <td>
                	<a href="<?=cpurl($module,$act,'edit_bcastr',array('flag'=>$flag,'sid'=>$sid))?>">编辑</a>
                	<a href="<?=cpurl($module,$act,'delete_bcastr',array('flag'=>$flag,'sid'=>$sid))?>" onclick="return confirm('您确定要删除吗？');">删除</a>
               	</td>
            </tr>
            <?endforeach;?>
        </table>
    </div>
	<center>
		<input type="hidden" name="sid" value="<?=$sid?>">
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update_banner" />
		<button type="button" class="btn" onclick="easy_submit('myform','update_bcastr',null)">提交</button>&nbsp;
		<button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add_bcastr',array('sid'=>$sid))?>';">上传图片</button>
	</center>
</form>
</div>