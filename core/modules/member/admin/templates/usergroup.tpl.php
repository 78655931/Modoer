<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/minicolors.js"></script>
<link rel="stylesheet" type="text/css" href="./static/images/minicolors.css">
<script type="text/javascript">
$(document).ready( function() {
    $(".colors").miniColors();
});
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�û������</div>
        <ul class="cptab">
            <li<?=$_GET['type']=='member'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'member'))?>">��Ա�û���</a></li>
            <li<?=$_GET['type']=='special'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'special'))?>">�����û���</a></li>
            <li<?=$_GET['type']=='system'?' class="selected"':''?>><a href="<?=cpurl($module,$act,$op,array('type'=>'system'))?>">ϵͳ�û���</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">&nbsp;ɾ?</td>
                <td width="45">groupid</td>
                <td width="230">����ͷ��</td>
                <?php if($_GET['type']=='member'):?>
                <td width="130">�ȼ����ָ���</td>
                <?elseif($_GET['type']=='special'):?>
                <td width="130">�ۼ�(<a href="<?=cpurl($module,'config')?>"><?=display('member:point',"point/$MOD[sellgroup_pointtype]")?>/<?=$MOD['sellgroup_useday']?>��</a>)</td>
                <?php endif;?>
                <td width="150">������ɫ</td>
                <td width="*">����</td>
            </tr>

            <?php if($list) { ?>
            <?php while($val = $list->fetch_array()) { ?>
            <tr>
                <input type="hidden" name="groups[<?=$val['groupid']?>][grouptype]" value="<?=$val['grouptype']?>">
                <?php $disabled = ($val['grouptype']=='system') ? " disabled" : ""; ?>
                <td><input type="checkbox" name="groupids[]" value="<?=$val['groupid']?>"<?=($val['grouptype']=='system'||$val['groupid']=='10')?' disabled':''?> /></td>
                <td><?=$val[groupid]?></td>
                <td><input type="text" name="groups[<?=$val['groupid']?>][groupname]" value="<?=$val['groupname']?>" class="txtbox3"<?=$disabled?> /></td>
                <?php if($_GET['type']=='member'):?>
                <td><input type="text" name="groups[<?=$val['groupid']?>][point]" value="<?=$val['point']?>" class="txtbox4"<?=($val['groupid']=='10')?' disabled':''?> /></td>
                <?elseif($_GET['type']=='special'):?>
                <td><input type="text" name="groups[<?=$val['groupid']?>][price]" value="<?=$val['price']?>" class="txtbox4" /></td>
                <?php endif;?>
                <td><input type="text" name="groups[<?=$val['groupid']?>][color]" value="<?=$val['color']?>" class="colors txtbox4"  /></td>
                <td>[<a href="<?=cpurl($module,$act,'edit',array('groupid'=>$val['groupid']))?>">Ȩ�޹���</a>]</td>
            </tr>
            <?php } $list->free_result; } else { ?>
            <tr>
                <td colspan="6">��ʱû��</td>
            </tr>
            <?php } ?>
            <?php if($_GET['type']!='system'){?>
            <tr>
                <td class="altbg1" colspan="2">����:</td>
                <td class="altbg1"><input type="text" name="newgroup[groupname]" class="txtbox3" /></td>
                <?php if($_GET['type']=='member'):?>
                <td class="altbg1"><input type="text" name="newgroup[point]" class="txtbox4" /></td>
                <?elseif($_GET['type']=='special'):?>
                <td class="altbg1"><input type="text" name="newgroup[price]" class="txtbox4" /></td>
                <?php endif;?>
                <td class="altbg1" colspan="3"><input type="text" name="newgroup[color]" class="colors txtbox4" /></td>
                <input type="hidden" name="newgroup[grouptype]" value="<?=$_GET['type']?>">
            </tr>
            <?php }?>
        </table>
        <center>
            <input type="hidden" name="dosubmit" value="name" />
            <input type="hidden" name="op" value="post" />
            <input type="button" value="�ύ����/����" class="btn" onclick="easy_submit('myform','post',null)" />&nbsp;
            <?php if($list) { ?>
                <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform','delete','groupids[]')" />
            <?php } ?>
        </center>
    </div>
</form>
</div>