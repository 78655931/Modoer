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
    <input type="hidden" name="tgid" value="<?=$tgid?>" />
    <?endif;?>
    <div class="space">
        <div class="subtitle">新增标签组</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="20%" class="altbg1">标签组名称:</td>
                <td width="*"><input type="text" name="taggroup[name]" class="txtbox2" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">标签说明:</td>
                <td><input type="text" name="taggroup[des]" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">标签类型:</td>
                <td>
                    <select name="taggroup[sort]" id="sort" onchange="select_sort();">
                        <option value="1"<?if($detail['sort']==1)echo' selected="selected"';?>>自由填写</option>
                        <option value="2"<?if($detail['sort']==2)echo' selected="selected"';?>>固定值</option>
                    </select>
                </td>
            </tr>
            <tr id="tr_sort1">
                <td class="altbg1">设置固定值:</td>
                <td><input type="text" name="taggroup[options]" class="txtbox"  value="<?=$detail['options']?>" />&nbsp;多个标签请用逗号","分开</td>
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
        <div class="subtitle">标签设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20%">标签组名称</td>
                <td width="30%">说明</td>
                <td width="15%">类型</td>
                <td width="*">操作</td>
            </tr>
            <? if($list): ?>
            <? while($val = $list->fetch_array()): ?>
            <tr>
                <td><?=$val['name']?></a></td>
                <td><?=$val['des']?></td>
                <td><?=($val['sort']==1?'自由填写':'固定值')?></td>
                <td>
                    <a href="<?=cpurl($module, $act,'edit',array('tgid'=>$val['tgid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'delete',array('tgid'=>$val['tgid']))?>" onclick="return confirm('您确定要进行删除操作码？')">删除</a>
                </td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <? endif; ?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location='<?=cpurl($module, $act,'add')?>';"> 新增标签组 </button>
        </center>
    </div>
</form>
<? endif; ?>
</div>