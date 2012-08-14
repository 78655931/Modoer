<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">�������͹���</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,$act)?>" onfocus="this.blur()">�����б�</a></li>
            <li class="selected"><a href="<?=cpurl($module,$act,'type')?>" onfocus="this.blur()">��������</a></li>
            <li><a id="create_task" rel="create_task_menu" href="#" onfocus="this.blur()"><span class="font_1">��������</span></a></li>
        </ul>
        <?if($tasktypes):?>
        <ul id="create_task_menu" class="dropdown-menu">
            <?foreach($tasktypes as $key=>$val):?>
            <li><a href="<?=cpurl($module,$act,'add',array('flag'=>$val[flag]))?>"><?=$val[title]?></a></li>
            <?endforeach;?>
        </ul>
        <script type="text/javascript">
        $("#create_task").powerFloat();
        </script>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="150">��ʶ</td>
                <td width="150">����</td>
                <td width="120">�汾</td>
                <td width="250">��Ȩ��Ϣ</td>
                <td width="*">����</td>
            </tr>
            <?foreach($task_types as $tsk):?>
            <tr<?if(!$tsk->install):?> style="color:#808080;"<?endif;?>>
                <td><?=$tsk->flag?></td>
                <td><?=$tsk->title?></td>
                <td><?=$tsk->version?></td>
                <td><?=$tsk->copyright?></td>
                <td>
                    <?if($tsk->install):?>
                        <a href="<?=cpurl($module,$act,'unstall',array('ttid'=>$tsk->ttid))?>" onclick="return confirm('��ȷ��Ҫɾ������������ͼ����´��ڵ����������¼��');">ж��</a>
                    <?else:?>
                        <a href="<?=cpurl($module,$act,'install',array('flag'=>$tsk->flag))?>"><b>��װ</b></a>
                    <?endif;?>
                </td>
            </tr>
            <?endforeach;?>
            <?if(empty($task_types)):?>
            <tr>
                <td colspan="4">������Ϣ</td>
            </tr>
            <?endif;?>
        </table>
    </div>
</form>
</div>