<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function cp_set_album_thumb(picid) {
    $.post('<?=cpurl($module,$act,'set_thumb')?>', { picid:picid, in_ajax:1}, function (data) {
		if (data.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(data);
        } else if (data=='OK') {
            alert('���óɹ���');
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
        <div class="subtitle">ͼƬɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">��������</td>
                <td width="350">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">����</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
                <td width="100" class="altbg1">����ID(sid)</td>
                <td width="*">
					<input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">ͼƬ����</td>
                <td><input type="text" name="title" class="txtbox2" value="<?=$_GET['title']?>" /></td>
                <td class="altbg1">���ID(albumid)</td>
                <td><input type="text" name="albumid" class="txtbox3" value="<?=$_GET['albumid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">�ϴ���Ա</td>
                <td><input type="text" name="username" class="txtbox2" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">ͼƬ��С(kb)</td>
                <td><?=form_input('size_min',$_GET['size_min'],'txtbox4')?>&nbsp;-&nbsp;<?=form_input('size_max',$_GET['size_max'],'txtbox4')?></td>
            </tr>
            <tr>
                <td class="altbg1">�ϴ�ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="picid"<?=$_GET['orderby']=='picid'?' selected="selected"':''?>>ID����</option>
                    <option value="size"<?=$_GET['orderby']=='size'?' selected="selected"':''?>>ͼƬ��С</option>
                    <option value="addtime"<?=$_GET['orderby']=='addtime'?' selected="selected"':''?>>�ϴ�ʱ��</option>
                </select>&nbsp;
                <select name="ordersc">
                    <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>�ݼ�</option>
                    <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>����</option>
                </select>&nbsp;
                <select name="offset">
                    <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>ÿҳ��ʾ20��</option>
                    <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>ÿҳ��ʾ50��</option>
                    <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>ÿҳ��ʾ100��</option>
                </select>&nbsp;
                <button type="submit" value="yes" name="dosubmit" class="btn2">ɸѡ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" name="myform" action="<?=cpurl($module,$act,'list')?>">
    <div class="space">
        <div class="subtitle">ͼƬ����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ?</td>
                <td width="110">ͼƬ</td>
				<td width="250">������˵��</td>
                <td width="100">�ߴ�/��С</td>
                <td width="60">�ϴ���Ա</td>
                <td width="170">��������/�������/�ϴ�ʱ��</td>
                <td width="*">����</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="picids[]" value="<?=$val['picid']?>" /></td>
                <td class="picthumb"><a href="<?=$val['filename']?>" target="_blank"><img src="<?=$val['thumb']?>" /></a></td>
                <td>
					<div>���⣺<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][title]" value="<?=$val['title']?>"  /></div>
					<div  style="margin:5px 0;">���ӣ�<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][url]" value="<?=$val['url']?>" /></div>
					<div>˵����<input type="text" class="txtbox3" name="picture[<?=$val['picid']?>][comments]" value="<?=$val['comments']?>" /></div>
                </td>
                <td><?=round($val['size']/1024)?> KB<br /><?=$val['width']?> �� <?=$val['height']?></td>
                <td><img src="<?=get_face($val['uid'])?>" /><br /><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><?=$val['username']?></a></td>
                <td><span class="font_2">[<?=display('modoer:area',"aid/$val[city_id]")?>]</span><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['subjectname'].($val['subname']?"($val[subname])":'')?></a><br /><a href="<?=url("item/album/id/$val[albumid]")?>" target="_blank"><?=$val['albumname']?></a><br /><?=date('Y-m-d H:i', $val['addtime'])?></td>
                <td><a href="javascript:cp_set_album_thumb('<?=$val['picid']?>');">��Ϊ����</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="2"><button type="button" onclick="checkbox_checked('picids[]');" class="btn2">ȫѡ</button></td>
				<td colspan="5" style="text-align:right;"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="10">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','update',null)">�����޸�</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','picids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>