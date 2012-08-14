<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">产品审核</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选</td>
                <td width="200">名称</td>
                <td width="200">所属主题</td>
                <td width="*">简介</td>
                <td width="110">提交时间</td>
                <td width="60">操作</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><a href="<?=url('item/detail/id/'.$val['sid'])?>" target="_blank"><?=$val['name'].$val['subname']?></a></td>
                <td src="<?=$val['thumb']?>" onmouseover="tip_start(this);"><?=$val['description']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('pid'=>$val['pid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
            <tr class="altbg1">
                <td colspan="3">
                    <button type="button" onclick="checkbox_checked('pids[]');" class="btn2">全选</button>
                </td>
                <td colspan="3" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?else:?>
            <tr><td colspan="8">暂时没有信息</td></tr>
            <?endif?>
        </table>
    </div>
    <?if($total):?>
    <div class="multipage"><?=$multipage?></div>
    <center>
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','pids[]')">审核通过</button>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','pids[]')">删除所选</button>
    </center>
    <?endif;?>
<?=form_end()?>
</div>