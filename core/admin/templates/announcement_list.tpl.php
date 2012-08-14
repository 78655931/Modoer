<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">公告管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">删?</td>
                <td width="80">排序</td>
                <td width="*">标题</td>
                <td width="120">作者</td>
                <td width="100">城市</td>
                <td width="120">发布时间</td>
                <td width="60">可见</td>
                <td width="60">编辑</td>
            </tr>
            <? if($total) : ?>
            <? while($val = $list->fetch_array()) : ?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><input type="text" name="ann[<?=$val['id']?>][orders]" value="<?=$val['orders']?>" class="txtbox5" /></td>
                <td><?=$val['title']?></td>
                <td><?=$val['author']?></td>
                <td><?=template_print('modoer','area',array('aid'=>$val['city_id']))?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><input type="checkbox" name="ann[<?=$val['id']?>][available]" value="1"<?=$val['available']?' checked="checked"':''?> /></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('id'=>$val['id']))?>">编辑</a></td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr>
                <td colspan="8">暂无信息。</td>
            </tr>
            <? endif; ?>
        </table>
        <?if($multipage):?><div class="multipage"><?=$multipage?></div><?endif;?>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <? if($total) : ?>
            <input type="button" value="更新操作" class="btn" onclick="easy_submit('myform', 'update', null);" />
            <input type="button" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'ids[]');" />
            <? endif; ?>
            <input type="button" value="增加公告" class="btn" onclick="document.location.href='<?=cpurl($module,$act,"add")?>'" />
        </center>
    </div>
</form>
</div>