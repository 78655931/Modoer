<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">礼品筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">城市/分类</td>
                <td width="350">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                    <option value="">==城市==</option>
                    <?=form_city($_GET['city_id']);?>
                    </select>&nbsp;
					<?endif;?>
                    <select name="catid">
                    <option value="">==分类==</option>
                    <?=form_exchange_category($_GET['catid']);?>
                    </select>
                </td>
                <td width="100" class="altbg1">主题SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">礼品名称</td>
                <td>
                    <input type="text" name="name" class="txtbox3" value="<?=$_GET['name']?>" />
                </td>
                <td class="altbg1">兑换模式</td>
                <td>
                    <select name="pattern">
                        <option value="">==选择兑换模式==</option>
                        <option value="1"<?=$_GET['pattern']==1?' selected="selected"':''?>>自由兑换</option>
                        <option value="2"<?=$_GET['pattern']==2?' selected="selected"':''?>>抽奖模式</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="displayorder"<?=$_GET['orderby']=='displayorder'?' selected="selected"':''?>>默认排序</option>
                    <option value="pageview"<?=$_GET['orderby']=='pageview'?' selected="selected"':''?>>浏览量</option>
                    <option value="salevolume"<?=$_GET['orderby']=='salevolume'?' selected="selected"':''?>>兑换量</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">礼品管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="60">排序</td>
                <td width="30">可用</td>
                <td width="*">名称</td>
				<td width="80">兑换模式</td>
				<td width="250">价格</td>
				<td width="60">库存</td>
				<td width="60">已兑换</td>
				<td width="100">类型</td>
                <td width="60">操作</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="giftids[]" value="<?=$val['giftid']?>" /></td>
                <td><input type="text" name="gifts[<?=$val['giftid']?>][displayorder]" value="<?=$val['displayorder']?>" class="txtbox5" /></td>
                <td><input type="checkbox" name="gifts[<?=$val['giftid']?>][available]" value="1"<?if($val['available'])echo' checked="checked"';?> /></td>
                <td><a href="<?=url("exchange/gift/id/$val[giftid]")?>" target="_blank"><?=$val['name']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><?=$val['pattern']=='1'?'自由兑换':'抽奖模式'?></td>
                <td><?=$val['price']?>&nbsp;<?=template_print('member','point',array('point'=>$val['pointtype']))?><?if($val['point']):?> 或 <?=$val['point']?>&nbsp;<?=template_print('member','point',array('point'=>$val['pointtype2']))?><?endif;?><?if($val['point3']):?> 或 <?=$val['point3']?>&nbsp;<?=template_print('member','point',array('point'=>$val['pointtype3']))?> + <?=$val['point4']?>&nbsp;<?=template_print('member','point',array('point'=>$val['pointtype4']))?><?endif;?></td>
                <td><?=$val['num']?></td>
                <td><?=$val['salevolume']?></td>
                <td><?=$val['sort']=='1'?'实物':'虚拟'?></td>
                <td><a href="<?=cpurl($module,'gift','edit',array('giftid'=>$val['giftid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('giftids[]');" class="btn2">全选</button>
				</td>
				<td colspan="10" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="10">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新信息</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','giftids[]')">删除所选</button>&nbsp;
        <?endif;?>
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'add')?>'">增加礼品</button>
	</center>
</form>
</div>