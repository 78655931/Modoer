<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">图片轮换 [ <?=$gn?> ]</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">&nbsp;<a href="javascript:checkbox_checked('ids[]');">选</a></td>
                <td width="80">城市</td>
                <td width="80">排序</td>
                <td width="50">显示</td>
                <td width="250">标题</td>
                <td width="*">地址</td>
                <td width="80">操作</td>
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
                <td><a href="<?=cpurl($module,$act,'edit',array('bcastr_id'=>$val['bcastr_id']))?>">编辑</a></td>
            </tr>
            <? } $list->free_result(); ?>
            <? } else {?>
            <tr class="altbg1">
                <td colspan="6">暂无信息。</td>
            </tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="提交更新" class="btn" onclick="easy_submit('myform', 'update', null);" />
            <input type="button" value="增加图片" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>';" />
            <input type="button" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'bcastr_ids[]');" />&nbsp;
            <button type="button" onclick="document.location='<?=cpurl($module,$act)?>';" class="btn">返回</button>
        </center>
    </div>
</form>
</div>