<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">图片轮换组</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="250">图片组标记</td>
                <td width="100">图片数量</td>
                <td width="*">操作</td>
            </tr>
            <?php if($groups):?>
            <?php foreach($groups as $key => $val) :?>
            <tr>
                <td><?=$key?></td>
                <td><?=$val?></td>
                <td><a href="<?=cpurl($module,$act,'list',array('gn'=>$key))?>">管理</a></td>
            </tr>
            <?php endforeach;?>
            <?php else:?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <?php endif; ?>
        </table>
        <center>
            <input type="button" value="新建图片轮换" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>';" />
        </center>
    </div>
</form>
</div>