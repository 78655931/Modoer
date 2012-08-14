<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?if($_GET['op'] == 'add' || $_GET['op'] == 'edit'):?>
<script type="text/javascript">
function select_sort() {
    var sort = $('#sort').val();
    if(sort=='1') {
        $('#tr_sort1').css('display','none');
    } else if (sort=='2') {
        $('#tr_sort1').css('display','');
    }
}

$(document).ready(function() {
   select_sort(); 
});
</script>
<form method="post" action="<?=cpurl($module, $act, 'save')?>">
    <?if($_GET['op']=='edit'):?>
    <input type="hidden" name="catid" value="<?=$catid?>" />
    <?endif;?>
    <div class="space">
        <div class="subtitle">新增/编辑属性组</div>
        <?if($_GET['op']=='edit'):?>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,'att_cat','edit',array('catid'=>$catid))?>" onfocus="this.blur()">编辑属性组</a></li>
            <li><a href="<?=cpurl($module,'att_list','',array('catid'=>$catid))?>" onfocus="this.blur()">值管理</a></li>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>属性组名称:</strong></td>
                <td width="55%"><input type="text" name="name" class="txtbox2" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>属性说明:</strong></td>
                <td><input type="text" name="des" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
        </table>
        <center>
            <button type="submit" class="btn" name="dosubmit" value="yes"> 提交 </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);"> 返回 </button>
        </center>
    </div>
</form>
<? else: ?>
<form method="post" action="<?=cpurl($module, $act, $_GET['op'])?>">
    <div class="space">
        <div class="subtitle">属性组设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20%">属性组名称</td>
                <td width="30%">说明</td>
                <td width="*">操作</td>
            </tr>
            <? if($list): ?>
            <? while($val = $list->fetch_array()): ?>
            <tr>
                <td><?=$val['name']?></a></td>
                <td><?=$val['des']?></td>
                <td>
                    <a href="<?=cpurl($module, 'att_list','',array('catid'=>$val['catid']))?>">管理值</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'edit',array('catid'=>$val['catid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'delete',array('catid'=>$val['catid']))?>" onclick="return confirm('您确定要进行删除操作码？')">删除</a>
                </td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <? endif; ?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location='<?=cpurl($module, $act,'add')?>';"> 新增属性组 </button>
        </center>
    </div>
</form>
<? endif; ?>
</div>