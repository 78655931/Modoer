<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="search" />
    <div class="space">
        <div class="subtitle">短信息通知</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="300"><strong>用户名:</strong>多个用户名用逗号“,”分隔</td>
                <td width="*"><input type="text" name="username" class="txtbox2" value="<?=$_GET['username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>用户组:</strong>选择允许参与搜索的用户组</td>
                <td><select name="groupid" rows="5">
                    <option value="" style="color:#CC0000;">==全部用户组==</option>
                    <?=form_member_usergroup($_GET['groupid'])?>
                </select>&nbsp;<button type="submit" name="dosubmit" value="yes" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<?if($_GET['dosubmit']):?>
<form method="post" action="<?=cpurl($module,$act,'send')?>">
    <div class="space">
        <div class="subtitle">符合要求的会员: <?=$total?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($total):?>
            <tr>
                <td class="altbg1" width="300"><strong>标题:</strong></td>
                <td width="*"><input type="text" name="title" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>内容:</strong></td>
                <td><textarea name="message" style="height:100px;width:500px;"></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>批量发送数量:</strong></td>
                <td><input type="text" name="offset" value="100" /></td>
            </tr>
            <?else:?>
            <tr>
                <td colspan="2">抱歉，没有找到任何信息。</td>
            </tr>
            <?endif;?>
        </table>
        <?if($total):?>
        <center>
            <input type="hidden" name="username" value="<?=$_GET['username']?>" />
            <input type="hidden" name="groupid" value="<?=$_GET['groupid']?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn">开始发送</button>
        </center>
        <?endif;?>
    </div>
</form>
<?endif;?>
</div>