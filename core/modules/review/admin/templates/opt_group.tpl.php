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
    <input type="hidden" name="gid" value="<?=$gid?>" />
    <?endif;?>
    <div class="space">
        <div class="subtitle">新增/点评项组</div>
        <?if($_GET['op']=='edit'):?>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,'opt_group','edit',array('gid'=>$gid))?>" onfocus="this.blur()">编辑属性组</a></li>
            <li><a href="<?=cpurl($module,'opt','',array('gid'=>$gid))?>" onfocus="this.blur()">值管理</a></li>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>组名称:</strong>设置点评项的组名称，只在后台显示</td>
                <td width="55%"><input type="text" name="name" class="txtbox2" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>组说明:</strong>组的具体说明，旨在后台显示，没有作用</td>
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
        <div class="subtitle">点评项组管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="40">组ID</td>
                <td width="20%">组名称</td>
                <td width="30%">说明</td>
                <td width="*">操作</td>
            </tr>
            <? if($list): ?>
            <? while($val = $list->fetch_array()): ?>
            <tr>
                <td><?=$val['gid']?></td>    
                <td><?=$val['name']?></td>
                <td><?=$val['des']?></td>
                <td>
                    <a href="<?=cpurl($module, 'opt','',array('gid'=>$val['gid']))?>">点评项管理</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'edit',array('gid'=>$val['gid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'delete',array('gid'=>$val['gid']))?>" onclick="return confirm('删除点评项组前，请确认当前组没有被主题等分类使用，您确定要进行删除操作吗？')">删除</a>
                </td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <? endif; ?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location='<?=cpurl($module, $act,'add')?>';"> 新增点评项组 </button>
        </center>
    </div>
</form>
<? endif; ?>
</div>