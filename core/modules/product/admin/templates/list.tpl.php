<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="admincp.php">
<input type="hidden" name="action" value="<?=$action?>" />
<input type="hidden" name="file" value="<?=$file?>" />
<div class="space">
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="altbg1">
                ״̬��<select name="search[status]">
                        <option value="">=ȫ��=</option>
                        <option value="1"<?if($search['status']==1)echo' selected';?>>�����</option>
                        <option value="2"<?if($search['status']==2)echo' selected';?>>δ���</option>
                    </select>&nbsp;
                shopid��<input type="text" name="search[shopid]" value="<?=$search[shopid]?>" class="txtbox5" />&nbsp;
                ����<select name="search[order]">
                        <option value="dateline"<?if($search['order']=='dateline')echo' selected';?>>����ʱ��</option>
                        <option value="pageview"<?if($search['order']=='pageview')echo' selected';?>>�����</option>
                        <option value="pid"<?if($search['order']=='pid')echo' selected';?>>����ID</option>
                        <option value="digg"<?if($search['order']=='digg')echo' selected';?>>��һ��</option>
                    </select>&nbsp;<select name="search[ordertype]">
                        <option value="DESC"<?if($search['ordertype']=='DESC')echo' selected';?>>����</option>
                        <option value="ASC"<?if($search['ordertype']=='ASC')echo' selected';?>>˳��</option>
                    </select>&nbsp;
                    <button type="submit" class="btn2">��ѯ</button>
            </td>
        </tr>
    </table>
</div>
</form>
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>">
<div class="space">
    <div class="subtitle">��Ʒ�б�</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        <tr class="altbg1">
            <td width="25">ѡ</td>
            <td width="180">��������</td>
            <td width="*">����</td>
            <td width="50">�۸�</td>
            <td width="100">����ʱ��</td>
            <td width="50">״̬</td>
            <td width="100">����</td>
        </tr>
        <?if($total > 0) { 
        while($val = $db->fetch_array($query)) { ?>
        <tr>
            <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
            <td><a href="<?=url("shop/shop/shopid/$val[shopid]")?>" target="_blank"><?=$val['shopname'] . $val['subname']?></a></td>
            <td><a href="<?=url('product/detail/pid/'.$val['pid'])?>" target="_blank"><?=$val['subject']?></a></td>
            <td><?=$val['price1']?></td>
            <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
            <td><?if($val['status']==1)echo'<span class="font_3">�����</span>';elseif($val['status']==2)echo'<span class="font_1">δ���</span>';?></td>
            <td>
                <select id="select" name="select" onChange="selectOperation(this);">
                    <option value="">==����==</option>
                    <?if($val['status']==2){?>
                    <option value="admincp.php?action=product&file=list&op=check&pid=<?=$val['pid']?>&dosubmit=yes">���ͨ��</option>
                    <?}?>
                    <option value="admincp.php?action=product&file=product_edit&pid=<?=$val['pid']?>">�༭</option>
                    <option value="admincp.php?action=product&file=list&op=delete&pid=<?=$val['pid']?>&dosubmit=yes" 
                        cfm="ɾ���������ɻָ�����ȷ��Ҫɾ����ƪ������">ɾ��</option>
                    <option value="admincp.php?action=product&file=pic&pid=<?=$val['pid']?>">��ƷͼƬ</option>
                    <option value="admincp.php?action=product&file=pic&op=upload&pid=<?=$val['pid']?>">�ϴ�ͼƬ</option>
                </select>
            </td>
        </tr>
        <? } ?>
        <? $db->free_result($query); ?>
        <tr class="altbg1">
            <td colspan="5">
                <button class="btn2" onclick="checkbox_checked('pids[]');">ȫѡ</button>&nbsp;
                <input type="radio" name="op" id="op_delete" value="delete" checked /><label for="op_delete">����ɾ��</label>
                <?if($search['status']==2){?>
                <input type="radio" name="op" id="op_check" value="check" checked /><label for="op_check">�������</label>
                <? } ?>
                <input type="radio" name="op" id="op_att" value="att" checked /><label for="op_att">������������ att=<input type="text" name="att" value="1" class="txtbox6" /></label>
            </td>
            <td colspan="6" style="text-align:right"><?=$multipage?></td>
        </tr>
        <? } else { ?>
        <tr><td colspan="11">������Ϣ</td></tr>
        <? } ?>
    </table>
    <center>
        <button type="submit" name="dosubmit" value="yes" class="btn"> �ύ���� </button>
    </center>
</div>
</form>
</div>