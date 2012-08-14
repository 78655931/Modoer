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
		<div class="subtitle">增加/编辑广告</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="altbg1" width="25%"><strong>名称:</strong>仅作为后台浏览，不在前台展示。</td>
				<td width="75%"><input name="ad[adname]" type="text" class="txtbox" value="<?=$detail['adname']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>广告位：</strong>选择当前广告的展示位置。</td>
				<td>
					<select name="ad[apid]">
						<?=form_adv_place($detail['apid'])?>
					</select>
                </td>
			</tr>
			<tr>
				<td class="altbg1"><strong>广告类型：</strong>选择广告的显示媒介。</td>
				<td>
					<select id="adsort" name="ad[sort]" onchange="showAttr(this.value);">
						<?=form_adv_sort($detail['sort'])?>
					</select>
                </td>
			</tr>
            <tr>
                <td class="altbg1"><strong>投放城市:</strong>选择所属地区，选择“全局”表示可显示在所有城市分站内</td>
                <td>
                    <select name="ad[city_id]" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                </td>
            </tr>
			<tr>
				<td class="altbg1"><strong>有效期：</strong>格式：2010-11-11，留空为长期有效。</td>
				<td>
					<input type="tetx" name="ad[begintime]" class="txtbox4" value="<?=$detail['begintime']?date('Y-m-d',$detail['begintime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
					&nbsp;-&nbsp;
					<input type="tetx" name="ad[endtime]" class="txtbox4" value="<?=$detail['endtime']?date('Y-m-d',$detail['endtime']):''?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" />
				</td>
			</tr>
            <!--------------------------文字-------------------------->
			<tr id="word_title"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>文字标题：</strong></td>
				<td><input name="ad[config][word_title]" type="text" class="txtbox" value="<?=_T(str_replace('\\"','"',$detail['config']['word_title']))?>" /></td>
			</tr>
			<tr id="word_src"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>文字大小：</strong></td>
				<td><input name="ad[config][word_size]" type="text" class="txtbox4" value="<?=$detail['config']['word_size']?>" />&nbsp;px</td>
			</tr>
			<tr id="word_href"<?if($ad['adtype']!=1)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>文字连接：</strong></td>
				<td><input name="ad[config][word_href]" type="text" class="txtbox" value="<?=$detail['config']['word_href']?>" /></td>
			</tr>
            <!--------------------------图片-------------------------->
			<tr id="img_title"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>图片标题：</strong>即设置图片的alt属性</td>
				<td><input name="ad[config][img_title]" type="text" class="txtbox" value="<?=_T(str_replace('\\"','"',$detail['config']['img_title']))?>" /></td>
			</tr>
			<tr id="img_src"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>图片地址：</strong><div>图片地址可以上传或者填写图片的URL地址，如果同时填写，则默认选择上传的图片。<br />请艰难的进行二选一。</div></td>
				<td>
					<div>上传：<input type="file" name="picture" /></div>
					<div>URL地址：<input name="ad[config][img_src]" type="text" class="txtbox" value="<?=$detail['config']['img_src']?>" /></div>
				</td>
			</tr>
			<tr id="img_size"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>图片尺寸(宽x高)：</strong>在网页中显示固定的图片尺寸，最好在本地裁剪成合适的大小再上传</td>
				<td>
					<input name="ad[config][img_width]" type="text" class="txtbox4" value="<?=$detail['config']['img_width']?>" />
					&nbsp;x&nbsp;
					<input name="ad[config][img_height]" type="text" class="txtbox4" value="<?=$detail['config']['img_height']?>" />
				</td>
			</tr>
			<tr id="img_href"<?if($ad['adtype']!=2)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>广告连接：</strong>填写广告链接地址</td>
				<td><input name="ad[config][img_href]" type="text" class="txtbox" value="<?=$detail['config']['img_href']?>" /></td>
			</tr>
            <!--------------------------Flash-------------------------->
			<tr id="flash_src"<?if($ad['adtype']!=3)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>Flash地址：</strong>请填写Flash的完整展示地址</td>
				<td><input name="ad[config][flash_src]" type="text" class="txtbox" value="<?=$detail['config']['flash_src']?>" /></td>
			</tr>
			<tr id="flash_size"<?if($ad['adtype']!=3)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>Flash(宽x高)：</strong>设置Flash地图的高度</td>
				<td>
					<input name="ad[config][flash_width]" type="text" class="txtbox4" value="<?=$detail['config']['flash_width']?>" />
					&nbsp;x&nbsp;
					<input name="ad[config][flash_height]" type="text" class="txtbox4" value="<?=$detail['config']['flash_height']?>" />
				</td>
			</tr>
            <!--------------------------代码-------------------------->
			<tr id="code_code"<?if($ad['adtype']!=4)echo' style="display:none;"';?>>
				<td class="altbg1"><strong>代码：</strong>可以驶入HTML代码，例如Google广告，淘宝联盟广告的Js代码。</td>
				<td><textarea name="ad[config][code]" style="height:80px;width:400px;"><?=$detail['code']?></textarea></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>自定义属性:</strong>仅在特殊情况或者自定义显示方式时使用。</td>
				<td><input type="tetx" name="ad[attr]" class="txtbox5" value="<?=$detail['attr']?>" />&nbsp;限 10 字符。</td>
			</tr>
			<tr>
				<td class="altbg2" colspan="2"><center><b>联系信息</b></center></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>广告联系人:</strong></td>
				<td><input type="tetx" name="ad[ader]" class="txtbox2" value="<?=$detail['ader']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>联系人Email:</strong></td>
				<td><input type="tetx" name="ad[ademail]" class="txtbox2" value="<?=$detail['ademail']?>" /></td>
			</tr>
			<tr>
				<td class="altbg1"><strong>联系人电话:</strong></td>
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