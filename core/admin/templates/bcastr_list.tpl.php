<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">ͼƬ�ֻ� [ <?=$gn?> ]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">&nbsp;<a href="javascript:checkbox_checked('ids[]');">ѡ</a></td>
                <td width="80">����</td>
                <td width="80">����</td>
                <td width="50">��ʾ</td>
                <td width="250">����</td>
                <td width="*">��ַ</td>
                <td width="80">����</td>
            </tr>
            <?php if($list) {?>
            <?php while($val = $list->fetch_array()) {?>
            <tr>
                <td><input type="checkbox" name="bcastr_ids[]" value="<?=$val['bcastr_id']?>" /></td>
                <td><?=template_print('modoer','area',array('aid'=>$val['city_id']))?></td>
                <td><input type="text" name="bcastr[<?=$val['bcastr_id']?>][orders]" class="txtbox3 width" value="<?=$val['orders']?>" /></td>
                <td><input type="checkbox" name="bcastr[<?=$val['bcastr_id']?>][available]" value="1"<?if($val['available'])echo' checked="checked"';?> /></td>
                <td><input type="text" name="bcastr[<?=$val['bcastr_id']?>][itemtitle]" class="txtbox3 width" value="<?=$val['itemtitle']?>" /></td>
                <td><input type="text" name="bcastr[<?=$val['bcastr_id']?>][item_url]" class="txtbox3 width" value="<?=$val['item_url']?>" /></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('bcastr_id'=>$val['bcastr_id']))?>">�༭</a></td>
            </tr>
            <? } $list->free_result(); ?>
            <? } else {?>
            <tr class="altbg1">
                <td colspan="6">������Ϣ��</td>
            </tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="�ύ����" class="btn" onclick="easy_submit('myform', 'update', null);" />
            <input type="button" value="����ͼƬ" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>';" />
            <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'bcastr_ids[]');" />&nbsp;
            <button type="button" onclick="document.location='<?=cpurl($module,$act)?>';" class="btn">����</button>
        </center>
    </div>
</form>
</div>