<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'update')?>&">
    <div class="space">
        <div class="subtitle">任务列表</div>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,$act)?>" onfocus="this.blur()">任务列表</a></li>
            <li><a href="<?=cpurl($module,$act,'type')?>" onfocus="this.blur()">任务类型</a></li>
            <li><a id="create_task" rel="create_task_menu" href="#" onfocus="this.blur()"><span class="font_1">创建任务</span></a></li>
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
                <td width="50">显示顺序</td>
                <td width="30">可用</td>
                <td width="200">名称</td>
                <td width="80">奖励</td>
                <td width="*">任务时间</td>
                <td width="100" align="center">申请数/完成数</td>
                <td width="*">操作</td>
            </tr>
            <?if($total) while($val = $list->fetch_array()):?>
            <tr>
                <td><?=form_input("task[{$val[taskid]}][listorder]",$val['listorder'],'txtbox5')?></td>
                <td><input type="checkbox" name="task[<?=$val['taskid']?>][enable]"<?if($val['enable'])echo' checked="checked"';?> value="1"></td>
                <td><?=$val['title']?></td>
                <td><?=display('member:point',"point/$val[pointtype]")?><span class="font_1"><?=$val['point']?></span></td>
                <td>开始：<?=date('Y-m-d H:i',$val['starttime'])?><br />结束：<?=$val['endtime']?date('Y-m-d H:i',$val['endtime']):'永久'?></td>
                <td align="center"><?=$val['applys'].'/'.$val['completes']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('taskid'=>$val['taskid']))?>">编辑</a> 
                    <a href="<?=cpurl($module,$act,'delete',array('taskid'=>$val['taskid']))?>" onclick="return confirm('您确定要删除这个任务（本操作将会删除所有申请本任务的会员记录）？');">删除</a>
                </td>
            </tr>
            <?endwhile;?>
            <?if(empty($list)):?>
            <tr>
                <td colspan="10">暂无信息</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <center>
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
    </center>
</form>
</div>