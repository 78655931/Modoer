<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>&">
    <div class="space">
        <div class="subtitle">�����б�</div>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,$act)?>" onfocus="this.blur()">�����б�</a></li>
            <li><a href="<?=cpurl($module,$act,'type')?>" onfocus="this.blur()">��������</a></li>
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
                <td width="50">��ʾ˳��</td>
                <td width="30">����</td>
                <td width="200">����</td>
                <td width="80">����</td>
                <td width="*">����ʱ��</td>
                <td width="100" align="center">������/�����</td>
                <td width="*">����</td>
            </tr>
            <?if($total) while($val = $list->fetch_array()):?>
            <tr>
                <td><?=form_input("task[{$val[taskid]}][listorder]",$val['listorder'],'txtbox5')?></td>
                <td><input type="checkbox" name="task[<?=$val['taskid']?>][enable]"<?if($val['enable'])echo' checked="checked"';?> value="1"></td>
                <td><?=$val['title']?></td>
                <td><?=display('member:point',"point/$val[pointtype]")?><span class="font_1"><?=$val['point']?></span></td>
                <td>��ʼ��<?=date('Y-m-d H:i',$val['starttime'])?><br />������<?=$val['endtime']?date('Y-m-d H:i',$val['endtime']):'����'?></td>
                <td align="center"><?=$val['applys'].'/'.$val['completes']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('taskid'=>$val['taskid']))?>">�༭</a> 
                    <a href="<?=cpurl($module,$act,'delete',array('taskid'=>$val['taskid']))?>" onclick="return confirm('��ȷ��Ҫɾ��������񣨱���������ɾ���������뱾����Ļ�Ա��¼����');">ɾ��</a>
                </td>
            </tr>
            <?endwhile;?>
            <?if(empty($list)):?>
            <tr>
                <td colspan="10">������Ϣ</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <center>
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
    </center>
</form>
</div>