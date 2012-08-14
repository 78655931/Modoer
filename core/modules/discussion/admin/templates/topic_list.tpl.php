<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">话题筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">主题分类</td>
                <td width="350">
                    <select name="pid">
                    <option value="">==全部==</option>
                    <?=form_item_category($_GET['pid']);?>
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
                <td class="altbg1">作者UID</td>
                <td>
                    <input type="text" name="sid" class="txtbox3" value="<?=$_GET['uid']?>" />
                </td>
                <td class="altbg1">主题SID</td>
                <td>
                	<input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="sid"<?=$_GET['orderby']=='tpid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>发布时间</option>
                    <option value="replies"<?=$_GET['orderby']=='replies'?' selected="selected"':''?>>回复数量</option>
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
        <div class="subtitle">话题管理</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='discussion:topic')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
				<td width="180">主题名称</td>
				<td width="*">话题</td>
                <td width="100">作者</td>
				<td width="110">发布时间</td>
                <td width="80">回复数量</td>
                <td width="100">操作</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="tpids[]" value="<?=$val['tpid']?>" /></td>
				<td><?if($val['subjectname']):?><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['subjectname'].$val['subname']?></a><?else:?>主题信息不存在或已删除<?endif;?></td>
                <td><a href="<?=url("discussion/topic/id/$val[tpid]")?>" target="_blank"><?=$val['subject']?></a></td>
                <td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
				<td><?=date('Y-m-d H:i', $val['dateline'])?></td>
                <td><?=$val['replies']?></td>
                <td>
                    <a href="<?=cpurl($module,$act,'edit',array('tpid'=>$val['tpid']))?>">编辑</a>
                    <a href="<?=cpurl($module,'reply','list',array('tpid'=>$val['tpid']))?>">回复管理</a>
                </td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="7" class="altbg1">
					<button type="button" onclick="checkbox_checked('tpids[]');" class="btn2">全选</button>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','tpids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>