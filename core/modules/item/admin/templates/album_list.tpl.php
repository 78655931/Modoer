<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">���ɸѡ</div>
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
                <td width="100" class="altbg1">����SID</td>
                <td width="*">
					<input type="text" name="sid" class="txtbox3" value="<?=$_GET['sid']?>" />
                </td>
            </tr>
            <tr>
                <td class="altbg1">������</td>
                <td colspan="2"><input type="text" name="name" class="txtbox" value="<?=$_GET['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">������ʱ��</td>
                <td colspan="3"><input type="text" name="starttime" class="txtbox3" value="<?=$_GET['starttime']?>" />&nbsp;~&nbsp;<input type="text" name="endtime" class="txtbox3" value="<?=$_GET['endtime']?>" />&nbsp;(YYYY-MM-DD)</td>
            </tr>
            <tr>
                <td class="altbg1">�������</td>
                <td colspan="3">
                <select name="orderby">
                    <option value="albumid"<?=$_GET['orderby']=='albumid'?' selected="selected"':''?>>ID����</option>
                    <option value="lastupdate"<?=$_GET['orderby']=='lastupdate'?' selected="selected"':''?>>������ʱ��</option>
                    <option value="num"<?=$_GET['orderby']=='num'?' selected="selected"':''?>>������</option>
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
<form method="post" name="myform" action="<?=cpurl($module,$act,'',array('pid'=>$pid))?>">
    <div class="space">
        <div class="subtitle">����б�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ?</td>
                <td width="110">����</td>
				<td width="320">����/˵��</td>
				<td width="*">��������</td>
                <td width="50" align="center">ͼƬ��</td>
                <td width="110">������</td>
                <td width="80">����</td>
            </tr>
			<?if($total):?>
			<?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="albumids[]" value="<?=$val['albumid']?>" /></td>
                <td class="picthumb"><img src="<?=$val['thumb']?$val['thumb']:'./static/images/noimg.gif'?>" /></td>
                <td>
					<div>���⣺<br /><input type="text" class="txtbox2" name="album[<?=$val['albumid']?>][name]" value="<?=$val['name']?>"  /></div>
					<div>˵����<br /><input type="text" class="txtbox2" name="album[<?=$val['albumid']?>][des]" value="<?=$val['des']?>" /></div>
                </td>
                <td><span class="font_2">[<?=display('modoer:area',"aid/$val[city_id]")?>]</span><a href="<?=url("item/detail/id/$val[sid]")?>" target="_blank"><?=$val['subjectname'].($val['subname']?"($val[subname])":'')?></a></td>
				<td align="center"><?=$val['num']?></td>
                <td><?=date('Y-m-d H:i', $val['lastupdate'])?></td>
                <td><a href="<?=cpurl($module,'picture_list','list',array('albumid'=>$val['albumid'],'dosubmit'=>'yes'))?>">����ͼƬ</a></td>
            </tr>
			<?endwhile;?>
			<tr class="altbg1">
				<td colspan="2"><button type="button" onclick="checkbox_checked('albumids[]');" class="btn2">ȫѡ</button></td>
				<td colspan="5" style="text-align:right;"><?=$multipage?></td>
			</tr>
			<?else:?>
			<tr>
				<td colspan="8">������Ϣ��</td>
			</tr>
			<?endif;?>
        </table>
    </div>
	<?if($total):?>
	<center>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="checkup" />
		<button type="button" class="btn" onclick="easy_submit('myform','update',null)">�����޸�</button>&nbsp;
		<button type="button" class="btn" onclick="easy_submit('myform','delete','albumids[]')">ɾ����ѡ</button>
	</center>
	<?endif;?>
</form>
</div>