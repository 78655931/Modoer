<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">ɾ?</td>
                <td width="80">����</td>
                <td width="*">����</td>
                <td width="120">����</td>
                <td width="100">����</td>
                <td width="120">����ʱ��</td>
                <td width="60">�ɼ�</td>
                <td width="60">�༭</td>
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
                <td><a href="<?=cpurl($module,$act,'edit',array('id'=>$val['id']))?>">�༭</a></td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr>
                <td colspan="8">������Ϣ��</td>
            </tr>
            <? endif; ?>
        </table>
        <?if($multipage):?><div class="multipage"><?=$multipage?></div><?endif;?>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <? if($total) : ?>
            <input type="button" value="���²���" class="btn" onclick="easy_submit('myform', 'update', null);" />
            <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'ids[]');" />
            <? endif; ?>
            <input type="button" value="���ӹ���" class="btn" onclick="document.location.href='<?=cpurl($module,$act,"add")?>'" />
        </center>
    </div>
</form>
</div>