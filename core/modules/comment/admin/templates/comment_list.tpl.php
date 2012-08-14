<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">评论筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">评论类型</td>
                <td width="300">
                    <select name="idtype">
                    <option value="">全部评论</option>
                    <?=form_comment_idtype($_GET['idtype'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">评论对象ID</td>
                <td width="*"><input type="text" name="id" class="txtbox3" value="<?=$_GET['id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">评论标题</td>
                <td colspan="3"><input type="text" name="title" class="txtbox" value="<?=$_GET['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">评论者</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">评论者IP</td>
                <td colspan="3"><input type="text" name="ip" class="txtbox3" value="<?=$_GET['ip']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">发布时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="cid"<?=$_GET['orderby']=='cid'?' selected="selected"':''?>>默认排序</option>
                    <option value="dateline"<?=$_GET['orderby']=='dateline'?' selected="selected"':''?>>评论时间</option>
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
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">审核评论</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">选</td>
                <td width="60">类型</td>
				<td width="140">标题</td>
				<td width="80">评论者</td>
				<td width="25">评分</td>
				<td width="*">内容</td>
                <td width="70">ip</td>
				<td width="110">评论时间</td>
				<td width="50">编辑</td>
			</tr>
            <?if($total):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="cids[]" value="<?=$val[cid]?>" /></td>
                <td><a href="<?=url_replace(cpurl($module,$act,'list',$_GET),'idtype',$val['idtype'])?>"><?=$val['idtype']?></a></td>
                <td><a href="<?=$CM->get_url($val['idtype'],$val['id'])?>" target="_blank"><?=$val['title']?></a></td>
                <td><?=$val['username']?></td>
                <td><?=$val['grade']?></td>
                <td><?=trimmed_title($val['content'],50,'...')?></td>
                <td><?=$val['ip']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,'comment_list','edit',array('cid'=>$val['cid']))?>">编辑</a></td>
            </tr>
            <?endwhile;?>
			<tr class="altbg1">
				<td colspan="3" class="altbg1">
					<button type="button" onclick="checkbox_checked('cids[]');" class="btn2">全选</button>
				</td>
				<td colspan="8" style="text-align:right;"><?=$multipage?></td>
			</tr>
            <?else:?>
            <td colspan="8">暂无信息。</td>
            <?endif;?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
		<button type="button" class="btn" onclick="easy_submit('myform','delete','cids[]')">删除所选</button>
	</center>
	<?endif;?>
<?=form_end()?>
<?endif;?>
</div>