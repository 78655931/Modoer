<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'checkup')?>">
    <div class="space">
        <div class="subtitle">��Ա�����봦��</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100">�����Ա��</td>
                <td width="*"><a href="<?=url("space/index/uid/$detail[uid]")?>" target="_blabk"><?=$detail['username']?></a></td>
            </tr>
            <tr>
                <td class="altbg1">��ϵ�ˣ�</td>
                <td><?=$detail['linkman']?></td>
            </tr>
            <tr>
                <td class="altbg1">��ϵ�绰��</td>
                <td><?=$detail['tel']?></td>
            </tr>
            <tr>
                <td class="altbg1">�ֻ����룺</td>
                <td><?=$detail['mobile']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">��ϵ��ַ��</td>
                <td><?=$detail['address']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">��������</td>
                <td><?=$detail['postcode']?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top">����˵����</td>
                <td><?=$detail['comment']?></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ�䣺</td>
                <td><?=date('Y-m-d H:i:s',$detail['dateline'])?></td>
            </tr>
            <tr>
                <td class="altbg1">��������</td>
                <td>
                    <input type="radio" id="p_g" name="status" value="1" <?php if($detail['status']==1)echo' checked';?> /><label for="p_g">ͨ��</label>
                    <input type="radio" id="p_r" name="status" value="2" <?php if($detail['status']==3)echo' checked';?> /><label for="p_r">��ͨ��</label>
                </td>
            </tr>
            <tr id="status_returned">
                <td class="altbg1" valign="top">����˵����</td>
                <td>
                	<textarea name="checkmsg" rows="5" cols="60"><?=$detail['checkmsg']?></textarea><br />
                	<input type="checkbox" name="pm" id="pm" value="1" /><label for="pm">��������Ϣ����Ϣ�ķ�ʽ���͸�������</label>&nbsp;<span class="font_2">��Ա���������в鿴������Ա�Ĵ���˵��</span>
                </td>
            </tr>
            <?php if($detail[checktime]): ?>
            <tr>
                <td class="altbg1">�����ߣ�</td>
                <td><?=$detail['checker']?></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ�䣺</td>
                <td><?=date('Y-m-d H:i:s',$detail['checktime'])?></td>
            </tr>
            <?php endif;?>
        </table>
        <center>
            <input type="hidden" name="applyid" value="<?=$applyid?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" />�ύ����</button>&nbsp;
            <button type="button" onclick="history.go(-1);" class="btn" />����</button>&nbsp;
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