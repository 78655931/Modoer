<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
	<div class="space">
		<div class="subtitle">充值卡管理</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr class="altbg2">
                <th colspan="10">
                    <ul class="subtab">
                        <li<?=$status==1?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>1))?>">正常</a></li>
                        <li<?=$status==2?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>2))?>">已使用</a></li>
                        <li<?=$status==3?' class="current"':''?>><a href="<?=cpurl($module,$act,'',array('status'=>3))?>">失效</a></li>
                    </ul>
                </th>
            </tr>
            <tr class="altbg1">
				<td width="20">删?</td>
                <td width="140">卡号</td>
				<td width="140">密码</td>
                <td width="80">类型</td>
				<td width="60">面值</td>
                <td width="80">有效期</td>
                <td width="120">生成日期</td>
				<td width="60">状态</td>
                <?if($status==2):?>
                <td width="120">充值会员</td>
                <td width="110">充值时间</td>
                <?endif;?>
			</tr>
			<?if($total>0){while($row=$list->fetch_array()) {?>
			<tr>
                <td><input type="checkbox" name="cardids[]" value="<?=$row['cardid']?>" /></td>
                <td><?=$row['number']?></td>
                <td><?=$row['password']=='NULL'?'<span class="font_2">无密码</span>':$row['password']?></td>
                <td><?=$row['cztype']=='rmb'?'现金':template_print('member','point',array('point'=>$row['cztype']))?></td>
                <td><?=$row['price']?></td>
                <td><?=date('Y-m-d',$row['endtime'])?></td>
                <td><?=date('Y-m-d H:i:s',$row['dateline'])?></td>
                <td><?=$row['status']==1?'正常':($row['status']==2?'已使用':'失效')?></td>
                <?if($status==2):?>
                <td><a href="<?=url("space/index/uid/$row[uid]")?>" target="blank"><?=$row['username']?></a></td>
                <td><?=date('Y-m-d H:i:s',$row['usetime'])?></td>
                <?endif;?>
            </tr>
            <?} $list->free_result();?>
			<tr>
                <td colspan="10" class="altbg1">
                    <button type="button" class="btn2" onclick="checkbox_checked('cardids[]');">全选</button>&nbsp;
                    <button type="button" class="btn2" onclick="window.open('<?=str_replace('&amp;','&',cpurl($module,$act,'export',$_GET))?>');">导出全部</button>&nbsp;
                    <?if($status==1):?><span class="font_2">不可随意删除已发放的未使用的卡号，否则会使卡号失效。</span><?endif;?>
                </td>
            </tr>
            <?} else {?>
			<tr><td colspan="10">暂无信息。</td></tr>
            <?}?>
		</table>
    </div>
    <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
    <center>
        <?if($total) {?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','delete','cardids[]')">删除所选</button>&nbsp;
        <?}?>
        <button type="button" class="btn" onclick="location.href='<?=cpurl($module,$act,'create')?>';">批量生成充值卡</button>
    </center>
</form>
</div>