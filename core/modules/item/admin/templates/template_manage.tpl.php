<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">模版管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="*">模版名称</td>
                <td width="110">购买时间</td>
                <td width="110">到期时间</td>
                <td width="80">操作</td>
            </tr>
            <?if($list):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><?=$val[name]?></td>
                <td><?=date('Y-m-d',$val[buytime])?></td>
                <td><?=$val[endtime]>3000000000?'永久':date('Y-m-d',$val[endtime])?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'manage_edit',array(id=>$val['id']))?>">编辑</a>
                    <a href="<?=cpurl($module,$act,'manage_delete',array('id'=>$val['id']))?>" onclick="return confirm('您确定要删除吗？');">删除</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <tr>
                <td colspan="5">暂无信息。</td>
            </tr>
            <?endif;?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'manage_add',array('sid'=>$sid))?>';">添加</button>
    </center>
</form>