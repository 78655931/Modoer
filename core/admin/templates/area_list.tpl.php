<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function collapsing(areacode) {
    var len = 3 + areacode.length;
    $("table tr").each(function(i) {
        if(this.id!='' && this.id.length>len && this.id.substr(0,len)=='tr_'+areacode) {
            this.style.display = this.style.display=='none' ? '' : 'none';
        }
    });
}
</script>
<div id="body">
    <form method="post" action="<?=cpurl($module,$act,$op)?>&">
    <div class="space">
        <div class="subtitle">地区管理：第<?=$level?>层&nbsp;<?=$detail['name']?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="40">编号</td>
                <td width="60">序号</td>
                <?if($level==1):?>
                <td width="30">启用</td>
                <td width="50">首字母</td>
                <td width="120">二级域名</td>
                <?endif;?>
                <td width="150">名称</td>
                <td width="*">地图中心坐标</td>
                <td width="200">操作</td>
            </tr>
            <? if($list) {?>
            <? foreach($list as $val) { ?>
            <tr>
                <td><?=$val['aid']?></td>
                <td><input type="text" class="txtbox5" name="area[<?=$val['aid']?>][listorder]" value="<?=$val['listorder']?>" /></td>
                <?if($level==1):?>
                <td><input type="checkbox" name="area[<?=$val[aid]?>][enabled]" value="1"<?=$val['enabled']?'checked="checked"':''?>></td>
                <td><?=form_input("area[$val[aid]][initial]",$val['initial'],'txtbox5')?></td>
                <td><?=form_input("area[$val[aid]][domain]",$val['domain'],'txtbox4 width')?></td>
                <?endif;?>
                <td><?=form_input("area[$val[aid]][name]",$val['name'],'txtbox4 width')?></td>
                <td><?=form_input("area[$val[aid]][mappoint]",$val['mappoint'],'txtbox3 width')?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('aid' => $val['aid']))?>">编辑</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'delete',array('aid' => $val['aid']))?>" onclick="return confirm('如果当前地区下面还有主题，文章，分类信息，活动等数据时，请先删除这些数据，再删除当前地区数据。您确定要删除吗？');">删除</a>&nbsp;
                    <?if($level < 3) :?>
                    <a href="<?=cpurl($module,$act,'add',array('pid' => $val['aid']))?>">添加下级</a>&nbsp;
                    <a href="<?=cpurl($module,$act,'',array('pid' => $val['aid']))?>">查看下级</a>
                    <?endif;?>
                </td>
            </tr>
            <? } ?>
            <? } else { ?>
            <tr><td colspan="4">暂无信息。</td></tr>
            <? } ?>
        </table>
    </div>
    <center>
        <? if($list):?><input type="submit" name="dosubmit" value=" 更新编辑 " class="btn" />&nbsp;<? endif; ?>
        <? if($level>1) :?>
        <input type="button" value=" 新建本级地区 " class="btn" onclick="document.location.href='<?=cpurl($module,$act,'add',array('pid'=>$pid))?>'" />
        <input type="button" value=" 返回上一级 " class="btn" onclick="document.location.href='<?=cpurl($module,$act,'',array('pid'=>$detail['pid']))?>'" />
        <?else:?>
        <input type="button" value=" 新建城市 " class="btn" onclick="document.location.href='<?=cpurl($module,$act,'add',array('level'=>'1'))?>'" />&nbsp;
        <input type="button" value=" 导入数据 " class="btn" onclick="document.location.href='<?=cpurl($module,$act,'import')?>'" />&nbsp;
        <input type="button" value=" 导出数据 " class="btn" onclick="window.open('<?=cpurl($module,$act,'export')?>');" />
        <? endif; ?>
    </center>
</form>
</div>