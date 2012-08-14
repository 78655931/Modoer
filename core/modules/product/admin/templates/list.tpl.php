<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="admincp.php">
<input type="hidden" name="action" value="<?=$action?>" />
<input type="hidden" name="file" value="<?=$file?>" />
<div class="space">
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="altbg1">
                状态：<select name="search[status]">
                        <option value="">=全部=</option>
                        <option value="1"<?if($search['status']==1)echo' selected';?>>已审核</option>
                        <option value="2"<?if($search['status']==2)echo' selected';?>>未审核</option>
                    </select>&nbsp;
                shopid：<input type="text" name="search[shopid]" value="<?=$search[shopid]?>" class="txtbox5" />&nbsp;
                排序：<select name="search[order]">
                        <option value="dateline"<?if($search['order']=='dateline')echo' selected';?>>发布时间</option>
                        <option value="pageview"<?if($search['order']=='pageview')echo' selected';?>>浏览量</option>
                        <option value="pid"<?if($search['order']=='pid')echo' selected';?>>文章ID</option>
                        <option value="digg"<?if($search['order']=='digg')echo' selected';?>>顶一下</option>
                    </select>&nbsp;<select name="search[ordertype]">
                        <option value="DESC"<?if($search['ordertype']=='DESC')echo' selected';?>>倒序</option>
                        <option value="ASC"<?if($search['ordertype']=='ASC')echo' selected';?>>顺序</option>
                    </select>&nbsp;
                    <button type="submit" class="btn2">查询</button>
            </td>
        </tr>
    </table>
</div>
</form>
<form method="post" action="admincp.php?action=<?=$action?>&file=<?=$file?>">
<div class="space">
    <div class="subtitle">产品列表</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
        <tr class="altbg1">
            <td width="25">选</td>
            <td width="180">所属商铺</td>
            <td width="*">标题</td>
            <td width="50">价格</td>
            <td width="100">发布时间</td>
            <td width="50">状态</td>
            <td width="100">操作</td>
        </tr>
        <?if($total > 0) { 
        while($val = $db->fetch_array($query)) { ?>
        <tr>
            <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
            <td><a href="<?=url("shop/shop/shopid/$val[shopid]")?>" target="_blank"><?=$val['shopname'] . $val['subname']?></a></td>
            <td><a href="<?=url('product/detail/pid/'.$val['pid'])?>" target="_blank"><?=$val['subject']?></a></td>
            <td><?=$val['price1']?></td>
            <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
            <td><?if($val['status']==1)echo'<span class="font_3">已审核</span>';elseif($val['status']==2)echo'<span class="font_1">未审核</span>';?></td>
            <td>
                <select id="select" name="select" onChange="selectOperation(this);">
                    <option value="">==操作==</option>
                    <?if($val['status']==2){?>
                    <option value="admincp.php?action=product&file=list&op=check&pid=<?=$val['pid']?>&dosubmit=yes">审核通过</option>
                    <?}?>
                    <option value="admincp.php?action=product&file=product_edit&pid=<?=$val['pid']?>">编辑</option>
                    <option value="admincp.php?action=product&file=list&op=delete&pid=<?=$val['pid']?>&dosubmit=yes" 
                        cfm="删除操作不可恢复，您确定要删除这篇文章吗？">删除</option>
                    <option value="admincp.php?action=product&file=pic&pid=<?=$val['pid']?>">产品图片</option>
                    <option value="admincp.php?action=product&file=pic&op=upload&pid=<?=$val['pid']?>">上传图片</option>
                </select>
            </td>
        </tr>
        <? } ?>
        <? $db->free_result($query); ?>
        <tr class="altbg1">
            <td colspan="5">
                <button class="btn2" onclick="checkbox_checked('pids[]');">全选</button>&nbsp;
                <input type="radio" name="op" id="op_delete" value="delete" checked /><label for="op_delete">批量删除</label>
                <?if($search['status']==2){?>
                <input type="radio" name="op" id="op_check" value="check" checked /><label for="op_check">批量审核</label>
                <? } ?>
                <input type="radio" name="op" id="op_att" value="att" checked /><label for="op_att">批量设置属性 att=<input type="text" name="att" value="1" class="txtbox6" /></label>
            </td>
            <td colspan="6" style="text-align:right"><?=$multipage?></td>
        </tr>
        <? } else { ?>
        <tr><td colspan="11">暂无信息</td></tr>
        <? } ?>
    </table>
    <center>
        <button type="submit" name="dosubmit" value="yes" class="btn"> 提交操作 </button>
    </center>
</div>
</form>
</div>