<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">����ɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="100" class="altbg1">��������</td>
                <td width="300">
                    <select name="idtype">
                    <option value="">ȫ������</option>
                    <?=form_review_idtype($_GET['idtype'])?>
                    </select>
                </td>
                <td width="100" class="altbg1">��������</td>
                <td width="*">
					<?if($admin->is_founder):?>
                    <select name="city_id">
                        <option value="">����</option>
                        <?=form_city($_GET['city_id'], TRUE)?>
                    </select>
					<?else:?>
					<?=$_CITY['name']?>
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">��������</td>
                <td><input type="text" name="title" class="txtbox3" value="<?=$_GET['title']?>" /></td>
                <td width="100" class="altbg1">��������ID</td>
                <td width="*"><input type="text" name="id" class="txtbox4" value="<?=$_GET['id']?>" /></td>

            </tr>
            <tr>
                <td class="altbg1">����</td>
                <td><input type="text" name="username" class="txtbox3" value="<?=$_GET['username']?>" /></td>
                <td class="altbg1">����IP</td>
                <td colspan="3"><input type="text" name="ip" class="txtbox3" value="<?=$_GET['ip']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="rid"<?=$_GET['orderby']=='rid'?' selected="selected"':''?>>Ĭ������</option>
                    <option value="posttime"<?=$_GET['orderby']=='posttime'?' selected="selected"':''?>>����ʱ��</option>
                    <option value="responds"<?=$_GET['orderby']=='responds'?' selected="selected"':''?>>��Ӧ����</option>
                    <option value="flowers"<?=$_GET['orderby']=='flowers'?' selected="selected"':''?>>�ʻ�����</option>
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
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">��������</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='review:list')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ɾ?</td>
				<td width="50">����</td>
				<td width="160">��������</td>
				<td width="*">��������</td>
                <td width="25">����</td>
				<td width="30">�ʻ�</td>
				<td width="30">��Ӧ</td>
                <td width="90">IP</td>
				<td width="110">����ʱ��</td>
				<td width="50">�༭</td>
			</tr>
			<?if($total && $list):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <input type="hidden" name="review[<?=$val['rid']?>][rid]" value="<?=$val['rid']?>" />
                <td><input type="checkbox" name="rids[]" value="<?=$val['rid']?>" /></td>
				<td><a href="<?=url("space/index/uid/$val[uid]")?>" target="_blank"><img src="<?=get_face($val['uid'])?>" /></a><br /><?=$val['username']?></td>
                <td><a href="<?=url("review/detail/id/$val[rid]")?>" target="_blank"><?=$val['title']?$val['title']:'N/A'?></a><span class="font_2">[<?=template_print('modoer','area',array('aid'=>$val['city_id']))?>]</span></td>
				<td><?=trimmed_title($val['content'], 50, '...')?></td>
                <td><input type="checkbox" name="review[<?=$val['rid']?>][digest]" value="1"<?if($val['digest'])echo'checked="checked"';?> /></td>
                <td><?=$val['flowers']?></td>
				<td><a href="<?=cpurl($module,'respond','list',array('rid' => $val['rid']))?>"><?=$val['responds']?></a></td>
				<td><?=$val['ip']?></td>
                <td><?=date('Y-m-d H:i', $val['posttime'])?></td>
				<td><a href="<?=cpurl($module, 'review', 'edit', array('rid' => $val['rid']))?>">�༭</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="12" class="altbg1">
					<button type="button" onclick="checkbox_checked('rids[]');" class="btn2">ȫѡ</button>
					<input type="checkbox" name="delete_point" id="delete_point" value="1" /><label for="delete_point">ɾ����ͬʱ�������߻���</label>
				</td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="12">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
    <div class="multipage"><?=$multipage?></div>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">�ύ����</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','rids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>