<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">优惠券筛选</div>
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
                    <?=form_coupon_category($_GET['catid']);?>
                    </select>
                </td>
                <td width="100" class="altbg1">主题SID</td>
                <td width="*"><input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">优惠券名称</td>
                <td>
                    <input type="text" name="subject" class="txtbox3" value="<?=$_GET['subject']?>" />
                </td>
                <td class="altbg1">上传会员</td>
                <td>
                    <input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">优惠时间</td>
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
<?if($_GET['dosubmit']):?>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">筛选结果</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="25">删?</td>
                <td width="180">所属主题</td>
                <td width="150">名称</td>
                <td width="*">优惠说明</td>
                <td width="100">上传会员</td>
                <td width="110">发布时间</td>
                <td width="60">状态</td>
                <td width="60">操作</td>
            </tr>
            <?php if($total) { ?>
            <?php while ($val=$list->fetch_array()) { ?>
            <tr>
                <td><input type="checkbox" name="couponids[]" value="<?=$val['couponid']?>" /></td>
                <td><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['name'].$val['subname']?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
                <td><a href="<?=url("coupon/detail/id/$val[couponid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td><?=$val['des']?></td>
                <td><?=$val['username']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><?=lang('coupon_status_'.$val['status'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('couponid'=>$val['couponid']))?>">编辑</a></td>
            </tr>
            <? } ?>
            <tr class="altbg1"><td colspan="10">
                <button type="button" class="btn2" onclick="checkbox_checked('couponids[]');">全选</button>&nbsp;
            </td></tr>
            <? } else { ?>
            <tr><td colspan="10">暂无信息。</td></tr>
            <? } ?>
        </table>
        <?if($multipage){?><div class="multipage"><?=$multipage?></div><?}?>
        <center>
            <?php if($total) { ?>
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="hidden" name="op" value="delete" />
            
            <button type="button" class="btn" onclick="easy_submit('myform','delete','couponids[]')">删除所选</button>
            <? } ?>
        </center>
    </div>
</form>
<?endif;?>
</div>