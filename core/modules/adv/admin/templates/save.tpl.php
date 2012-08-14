<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/validator.js"></script>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
function showAttr(id) {
    $("tr").each(function(i){
        if(this.id.length>0 && (this.id==id||this.id.substr(0,id.length+1)==id+'_')) {
            this.style.display="";
        }
        if(this.id.length>0 && (this.id==id||this.id.substr(0,id.length+1)!=id+'_')) {
            this.style.display="none";
        }
    });
}
window.onload=function() {
	var v=$('#adsort').val();
	showAttr(v);
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data">
	<div class="space">
		<div class="subtitle">����/�༭���</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="altbg1" width="25%"><strong>����:</strong>����Ϊ��̨���������ǰ̨չʾ��</td>
				<td width="75%"><input name="ad[adname]" type="text" class="txtbox" value="<?=$detail['adname']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>���λ��</strong>ѡ��ǰ����չʾλ�á�</td>
				<td>
					<select name="ad[apid]">
						<?=form_adv_place($detail['apid'])?>
					</select>
                </td>
			</tr>
			<tr>
				<td class="altbg1"><strong>������ͣ�</strong>ѡ�������ʾý�顣</td>
				<td>
					<select id="adsort" name="ad[sort]" onchange="showAttr(this.value);">
						<?=form_adv_sort($detail['sort'])?>
					</select>
                </td>
			</tr>
            <tr>
                <td class="altbg1"><strong>Ͷ�ų���:</strong>ѡ������������ѡ��ȫ�֡���ʾ����ʾ�����г��з�վ��</td>
                <td>
                    <select name="ad[city_id]" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                </td>
            </tr>
			<tr>
				<td class="altbg1"><strong>��Ч�ڣ�</strong>��ʽ��2010-11-11������Ϊ������Ч��</td>
				<td>
					<input type="tetx" name="ad[begintime]" class="txtbox4" value="<?=$detail['begintime']?date('Y-m-d',$detail['begintime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
					&nbsp;-&nbsp;
					<input type="tetx" name="ad[endtime]" class="txtbox4" value="<?=$detail['endtime']?date('Y-m-d',$detail['endtime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
				</td>
			</tr>
            <!--------------------------����-------------------------->
			<tr id="word_title"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>���ֱ��⣺</strong></td>
				<td><input name="ad[config][word_title]" type="text" class="txtbox" value="<?=_T(str_replace('\\"','"',$detail['config']['word_title']))?>" /></td>
			</tr>
			<tr id="word_src"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>���ִ�С��</strong></td>
				<td><input name="ad[config][word_size]" type="text" class="txtbox4" value="<?=$detail['config']['word_size']?>" />&nbsp;px</td>
			</tr>
			<tr id="word_href"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>�������ӣ�</strong></td>
				<td><input name="ad[config][word_href]" type="text" class="txtbox" value="<?=$detail['config']['word_href']?>" /></td>
			</tr>
            <!--------------------------ͼƬ-------------------------->
			<tr id="img_title"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>ͼƬ���⣺</strong>������ͼƬ��alt����</td>
				<td><input name="ad[config][img_title]" type="text" class="txtbox" value="<?=_T(str_replace('\\"','"',$detail['config']['img_title']))?>" /></td>
			</tr>
			<tr id="img_src"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>ͼƬ��ַ��</strong><div>ͼƬ��ַ�����ϴ�������дͼƬ��URL��ַ�����ͬʱ��д����Ĭ��ѡ���ϴ���ͼƬ��<br />����ѵĽ��ж�ѡһ��</div></td>
				<td>
					<div>�ϴ���<input type="file" name="picture" /></div>
					<div>URL��ַ��<input name="ad[config][img_src]" type="text" class="txtbox" value="<?=$detail['config']['img_src']?>" /></div>
				</td>
			</tr>
			<tr id="img_size"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>ͼƬ�ߴ�(��x��)��</strong>����ҳ����ʾ�̶���ͼƬ�ߴ磬����ڱ��زü��ɺ��ʵĴ�С���ϴ�</td>
				<td>
					<input name="ad[config][img_width]" type="text" class="txtbox4" value="<?=$detail['config']['img_width']?>" />
					&nbsp;x&nbsp;
					<input name="ad[config][img_height]" type="text" class="txtbox4" value="<?=$detail['config']['img_height']?>" />
				</td>
			</tr>
			<tr id="img_href"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>������ӣ�</strong>��д������ӵ�ַ</td>
				<td><input name="ad[config][img_href]" type="text" class="txtbox" value="<?=$detail['config']['img_href']?>" /></td>
			</tr>
            <!--------------------------Flash-------------------------->
			<tr id="flash_src"<?if($ad['adtype']!=3)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>Flash��ַ��</strong>����дFlash������չʾ��ַ</td>
				<td><input name="ad[config][flash_src]" type="text" class="txtbox" value="<?=$detail['config']['flash_src']?>" /></td>
			</tr>
			<tr id="flash_size"<?if($ad['adtype']!=3)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>Flash(��x��)��</strong>����Flash��ͼ�ĸ߶�</td>
				<td>
					<input name="ad[config][flash_width]" type="text" class="txtbox4" value="<?=$detail['config']['flash_width']?>" />
					&nbsp;x&nbsp;
					<input name="ad[config][flash_height]" type="text" class="txtbox4" value="<?=$detail['config']['flash_height']?>" />
				</td>
			</tr>
            <!--------------------------����-------------------------->
			<tr id="code_code"<?if($ad['adtype']!=4)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>���룺</strong>����ʻ��HTML���룬����Google��棬�Ա����˹���Js���롣</td>
				<td><textarea name="ad[config][code]" style="height:80px;width:400px;"><?=$detail['code']?></textarea></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>�Զ�������:</strong>����������������Զ�����ʾ��ʽʱʹ�á�</td>
				<td><input type="tetx" name="ad[attr]" class="txtbox5" value="<?=$detail['attr']?>" />&nbsp;�� 10 �ַ���</td>
			</tr>
			<tr>
				<td class="altbg2" colspan="2"><center><b>��ϵ��Ϣ</b></center></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>�����ϵ��:</strong></td>
				<td><input type="tetx" name="ad[ader]" class="txtbox2" value="<?=$detail['ader']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>��ϵ��Email:</strong></td>
				<td><input type="tetx" name="ad[ademail]" class="txtbox2" value="<?=$detail['ademail']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>��ϵ�˵绰:</strong></td>
				<td><input type="tetx" name="ad[adtel]" class="txtbox2" value="<?=$detail['adtel']?>" /></td>
			</tr>
		</table>
	    <center>
			<input type="hidden" name="do" value="<?=$op?>" />
			<?if($op=='edit'):?>
			<input type="hidden" name="adid" value="<?=$detail['adid']?>" />
			<?endif;?>
			<input type="hidden" name="forward" value="<?=get_forward()?>" />
			<?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
			<input type="button" class="btn" value="<?=lang('admincp_return')?>" onclick="document.location='<?=cpurl($module,$act)?>';" />
        </center>
	</div>
</form>
</div>