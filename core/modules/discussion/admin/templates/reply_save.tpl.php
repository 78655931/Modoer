<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>">
    <div class="space">
        <div class="subtitle">回复编辑</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='discussion:topic')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        	<tr>
        		<td class="altbg1" width="120">内容：</td>
        		<td width="*"><textarea name="content" style="height:100px;width:99%;" ><?=$detail[content]?></textarea></td>
        	</tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="rpid" value="<?=$rpid?>" />
		<button type="submint" class="btn">提交</button>
		<button type="button" class="btn" onclick="history.go(-1);">返回</button>
		<input type="hidden" name="forward" value="<?=get_forward()?>" />
	</center>
</form>
</div>