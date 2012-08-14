<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function cp_set_album_thumb(picid) {
    $.post('<?=cpurl($module,$act,'set_thumb')?>', { picid:picid, in_ajax:1}, function (data) {
		if (data.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(data);
        } else if (data=='OK') {
            alert('设置成功！');
        }
    })
}
</script>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">图片筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">所属城市</td>
                <td width="350">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">不限</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
                <td width="100" class="altbg1">主题ID(sid)</td>
                <td width="*">
					<input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">图片标题</td>
                <td><input type="text" name="title" class="txtbox2" value="<?=$_GET['title']?>" /></td>
                <td class="altbg1">相册ID(albumid)</td>
                <td><input type="text" name="albumid" class="txtbox3" value="<?=$_GET['albumid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">上传会员</td>
                <td><input type="text" name="username" class="txtbox2" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">图片大小(kb)</td>
                <td><?=form_input('size_min',$_GET['size_min'],'txtbox4')?>&nbsp;-&nbsp;<?=form_input('size_max',$_GET['size_max'],'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1">上传时间</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">结果排序</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="picid"<?=$_GET['orderby']=='picid'?' selected="selected"':''?>>ID排序</option>
                    <option value="size"<?=$_GET['orderby']=='size'?' selected="selected"':''?>>图片大小</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>上传时间</option>
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
<form method="post" name="myform" action="<?=cpurl($module,$act,'list')?>">
    <div class="space">
        <div class="subtitle">图片管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">选?</td>
                <td width="110">图片</td>
				<td width="250">标题与说明</td>
                <td width="100">尺寸/大小</td>
                <td width="60">上传会员</td>
                <td width="170">主题名称/所属相册/上传时间</td>
                <td width="*">操作</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td class="picthumb"><a href="<?=$val['filename']?>" target="_blank"><img src="<?=$val['thumb']?>" /></a></td>
                <td>
					<div>标题：<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][title]" value="<?=$val['title']?>"  /></div>
					<div  style="margin:5px 0;">链接：<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][url]" value="<?=$val['url']?>" /></div>
					<div>说明：<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][comments]" value="<?=$val['comments']?>" /></div>
                </td>
                <td><?=round($val['size']/1024)?> KB<br /><?=$val['width']?> × <?=$val['height']?></td>
                <td><img src="<?=get_face($val['uid'])?>" /><br /><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><span class="font_2">[<?=display('modoer:area',"aid/$val[city_id]")?>]</span><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['subjectname'].($val['subname']?"($val[subname])":'')?></a><br /><a href="<?=url("item/album/id/$val[albumid]")?>" target="_blank"><?=$val['albumname']?></a><br /><?=date('Y-m-d H:i', $val['addtime'])?></td>
                <td><a href="javascript:cp_set_album_thumb('<?=$val['picid']?>');">设为封面</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="2"><button type="button" onclick="checkbox_checked('picids[]');" class="btn2">全选</button></td>
				<td colspan="5" style="text-align:right;"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="10">暂无信息。</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','update',null)">更新修改</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">删除所选</button>
	</center>
	<?endif;?>
</form>
</div>