<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'checkup')?>">
    <div class="space">
        <div class="subtitle">会员卡申请处理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100">认领会员：</td>
                <td width="*"><a href="<?=url("space/index/uid/$detail[uid]")?>" target="_blabk"><?=$detail['username']?></a></td>
            </tr>
            <tr>
                <td class="altbg1">联系人：</td>
                <td><?=$detail['linkman']?></td>
            </tr>
            <tr>
                <td class="altbg1">联系电话：</td>
                <td><?=$detail['tel']?></td>
            </tr>
            <tr>
                <td class="altbg1">手机号码：</td>
                <td><?=$detail['mobile']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">联系地址：</td>
                <td><?=$detail['address']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">邮政编码</td>
                <td><?=$detail['postcode']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">申请说明：</td>
                <td><?=$detail['comment']?></td>
            </tr>
            <tr>
                <td class="altbg1">申请时间：</td>
                <td><?=date('Y-m-d H:i:s',$detail['dateline'])?></td>
            </tr>
            <tr>
                <td class="altbg1">处理结果：</td>
                <td>
                    <input type="radio" id="p_g" name="status" value="1" <?php if($detail['status']==1)echo' checked';?> /><label for="p_g">通过</label>
                    <input type="radio" id="p_r" name="status" value="2" <?php if($detail['status']==3)echo' checked';?> /><label for="p_r">不通过</label>
                </td>
            </tr>
            <tr id="status_returned">
                <td class="altbg1" valign="top">处理说明：</td>
                <td>
                	<textarea name="checkmsg" rows="5" cols="60"><?=$detail['checkmsg']?></textarea><br />
                	<input type="checkbox" name="pm" id="pm" value="1" /><label for="pm">将处理信息短消息的方式发送给申请者</label>&nbsp;<span class="font_2">会员可在助手中查看到管理员的处理说明</span>
                </td>
            </tr>
            <?php if($detail[checktime]): ?>
            <tr>
                <td class="altbg1">处理者：</td>
                <td><?=$detail['checker']?></td>
            </tr>
            <tr>
                <td class="altbg1">处理时间：</td>
                <td><?=date('Y-m-d H:i:s',$detail['checktime'])?></td>
            </tr>
            <?php endif;?>
        </table>
        <center>
            <input type="hidden" name="applyid" value="<?=$applyid?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" />提交操作</button>&nbsp;
            <button type="button" onclick="history.go(-1);" class="btn" />返回</button>&nbsp;
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
function myreturned(obj) {
    if(obj.value==3) {
        $("#status_returned").css("display","");
    } else {
        $("#status_returned").css("display","none");
    }
}
</script>