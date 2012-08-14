<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="space">
        <div class="subtitle">产品模型管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="50">模型ID</td>
                <td width="*">名称</td>
                <td width="200">数据表</td>
                <td width="200">操作</td>
            </tr>
            <?if($result) {
            foreach($result as $val) {?>
            <tr>
                <td><?=$val['modelid']?></td>
                <td><?=$val['name']?></td>
                <td><?=$_G['dns']['dbpre'].$val['tablename']?></td>
                <td>
                    <a href="<?=cpurl($module,'model_edit','',array('modelid'=>$val['modelid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('modelid'=>$val['modelid']))?>" onclick="return confirm('您确定要进行删除操作吗？');">删除</a>&nbsp;
                    <a href="<?=cpurl($module,'field_list','',array('modelid'=>$val['modelid']))?>">字段管理</a>
                </td>
            </tr>
            <?}?>
            <?} else {?>
            <tr><td colspan="7">暂无信息。</td></tr>
            <?}?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="location.href='<?=cpurl($module,'model_add')?>'">增加模型</button>
    </center>
</div>