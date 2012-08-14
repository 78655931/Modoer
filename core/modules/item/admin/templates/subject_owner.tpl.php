<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'owner',array('rand'=>$_G['random']))?>">
    <div class="space">
        <div class="subtitle">主题管理员设置[<?=$subject['name'].$subject['subname']?>]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <?if($owners){?>
            <tr class="altbg1">
                <td width="220">会员名</td>
                <td width="280">有效期 (yyyy-mm-dd 留空表示不设置期限)</td>
                <td width="*">操作</td>
            </tr>
            <?foreach($owners as $val){?>
            <tr>
                <td><input type="text" name="username" class="txtbox3" value="<?=$val['username']?>" /></td>
                <td><input type="text" name="expirydate" class="txtbox3" value="<?=$val['expirydate']?date('Y-m-d',$val['expirydate']):''?>" /></td>
                <td><a href="<?=cpurl($module,$act,'owner',array('do'=>'delete','sid'=>$sid,'uid'=>$val['uid']))?>" onclick="return confirm('您确定要删除主题管理员吗？');">删除</a></td>
            </tr>
            <?}}else{?>
            <tr>
                <td class="altbg1" width="100">会员名：</td>
                <td width="*"><input type="text" name="username" class="txtbox2" value="" /></td>
            </tr>
            <tr>
                <td class="altbg1">到期时间：</td>
                <td><input type="text" name="expirydate" value="" />&nbsp;yyyy-mm-dd&nbsp;留空表示不设置期限</td>
            </tr>
            <?}?>
        </table>
    </div>
    <center>
        <input type="hidden" name="sid" value="<?=$sid?>" />
        <?=form_submit('dosubmit',lang('global_submit'),'yes','btn')?>
        <input type="button" value="返回" class="btn" onclick="document.location='<?=cpurl($module,$act,'',array('sid'=>$sid))?>';" />
    </center>
</form>
</div>