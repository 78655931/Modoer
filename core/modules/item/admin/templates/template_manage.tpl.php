<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">ģ�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="*">ģ������</td>
                <td width="110">����ʱ��</td>
                <td width="110">����ʱ��</td>
                <td width="80">����</td>
            </tr>
            <?if($list):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><?=$val[name]?></td>
                <td><?=date('Y-m-d',$val[buytime])?></td>
                <td><?=$val[endtime]>3000000000?'����':date('Y-m-d',$val[endtime])?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'manage_edit',array(id=>$val['id']))?>">�༭</a>
                    <a href="<?=cpurl($module,$act,'manage_delete',array('id'=>$val['id']))?>" onclick="return confirm('��ȷ��Ҫɾ����');">ɾ��</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <tr>
                <td colspan="5">������Ϣ��</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'manage_add',array('sid'=>$sid))?>';">���</button>
    </center>
</form>