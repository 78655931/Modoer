<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <div class="space">
        <div class="subtitle">�û���Ȩ�޹���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>�û�������:</strong>��д�û�������</td>
                <td width="*">
                    <input type="text" name="groupname" value="<?=$detail['groupname']?>"<?if($detail['grouptype']=='system')echo' disabled="disabled"';?> />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ֹ������ַ:</strong>ѡ���ǡ������׽�ֹ�û�������վ���κ�ҳ��</td>
                <td><?=form_bool('access[member_forbidden]',$detail['groupid']==2?1:(bool)$access['member_forbidden'])?></td>
            </tr>
            <?php foreach($_G['modules'] as $k  => $v) :
                if($v['flag'] == MOD_FLAG) continue;
                $hookfile  = MUDDER_MODULE . $v['flag']  . DS .'admin' . DS . 'templates' . DS . 'hook_usergroup_save.tpl.php';
                if(!is_file($hookfile)) continue;
            ?>
            <tr class="altbg2"><td colspan="2"><center><b><?=$v['name']?></b></center></td></tr>
            <?php include $hookfile ?>
            <?php endforeach;?>
        </table>
        <center>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="hidden" name="groupid" value="<?=$_GET['groupid']?>" />
            <input type="submit" name="dosubmit" value=" �ύ " class="btn" />
            <input type="button" value=" ���� " onclick="history.go(-1);" class="btn" />
        </center>
    </div>
</form>
</div>