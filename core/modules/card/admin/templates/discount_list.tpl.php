<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">会员卡筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">主题分类</td>
                <td width="350">
                    <select name="pid">
                    <option value="">==全部==</option>
                    <?=form_card_use_model($_GET['pid']);?>
                    </select>&nbsp;
                </td>
                <td width="100" class="altbg1">所属城市</td>
                <td width="*">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                    <option value="">==城市==</option>
                    <?=form_city($_GET['city_id'],TRUE);?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">会员卡类型</td>
                <td>
                    <select name="cardsort">
                        <option value="">==全部==</option>
                        <option value="discount"<?if($_GET['type']=='discount')echo' selected="selected"';?>>折扣方式</option>
                        <option value="largess"<?if($_GET['type']=='largess')echo' selected="selected"';?>>赠送方式</option>
                        <option value="both"<?if($_GET['type']=='both')echo' selected="selected"';?>>二者都有</option>
                    </select>&nbsp;
                </td>
                <td class="altbg1">主题SID</td>
                <td><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">加盟时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='sid'?' selected="selected"':''?>>默认排序</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>加盟时间</option>
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
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">筛选结果</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">删?</td>
                <td width="*">名称</td>
                <td width="60">类型</td>
                <td width="200">信息</td>
                <td width="200">备注</td>
                <td width="110">加盟时间</td>
                <td width="25">推荐</td>
                <td width="25">有效</td>
                <td width="50">管理</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <input type="hidden" name="cards[<?=$val['sid']?>][sid]" value="<?=$val['sid']?>" />
                <td><input type="checkbox" name="sids[]" value="<?=$val['sid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=trim($val['name'].$val['subname'])?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><span class="font_1"><?=$val['cardsort']?></span></td>
                <td>
                    <?if($val['cardsort']=='both'||$val['cardsort']=='discount'):?>
                    <?=$val['discount']?>折&nbsp;
                    <?endif;?>
                    <?if($val['cardsort']=='both'||$val['cardsort']=='largess'):?>
                    <?=$val['largess']?>
                    <?endif;?>
                </td>
                <td><?=$val['exception']?></td>
                <td><?=date('Y-m-d H:i',$val['addtime'])?></td>
                <td><input type="checkbox" name="cards[<?=$val['sid']?>][finer]" value="1" <?if($val['finer'])echo' checked="checked"';?> /></td>
                <td><input type="checkbox" name="cards[<?=$val['sid']?>][available]" value="1" <?if($val['available'])echo' checked="checked"';?> /></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('sid'=>$val['sid']))?>">编辑</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('sids[]');">全选</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="update" />
            <button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新操作</button>&nbsp;
            <button type="button" class="btn" onclick="easy_submit('myform','delete','sids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
</div>