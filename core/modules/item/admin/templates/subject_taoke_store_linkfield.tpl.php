<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.linkfield-table { width:100%; }
.linkfield-table td { padding:5px; }
.linkfield-table td.altbg1 { text-align:right; padding-right:10px; }
.linkfield-table td img { max-height:150px; max-height:100px; }
</style>
<form method="post" action="<?=cpurl($module,$act,'store_recode',array('in_ajax'=>1))?>" class="post" 
	id="frm_linkfield" name="frm_linkfield" target="ajaxiframe">
    <input type="hidden" name="catid" value="<?=$cid?>" />
	<div id="store_linkfield_id" style="height:320px; overflow:auto; overflow-x:hidden;">
		<table class="linkfield-table" border="0" cellspacing="0" cellpadding="0" >
			<tr class="altbg1">
				<td width="46%" align="right">系统内置模型字段</td>
				<td width="8%"></td>
				<td width="46%">淘宝客API提供数据</td>
			</tr>
			<?php
				$stores = array(
					'title'=>'商铺标题',
					'seller_credit'=>'信用等级',
					'wangwang'=>'旺旺ID',
					'address'=>'所在地',
					'content'=>'店铺描述',
					'user_id'=>'店主id',
					'nick'=>'店主昵称',
					'commission_rate'=>'佣金比率',
					'created'=>'开店时间',
					'auction_count'=>'商品数量',
					'click_url'=>'推广链接',
				)
			?>
			<?php foreach($use_fields as $val):?>
			<tr>
				<td align="right"><?=($val['allownull']?'':'<span class="font_1">*</span>').$val['title']?>[<?=$val['fieldname']?>]</td>
				<td align="center">-&gt;</td>
				<td>
					<select name="fields[<?=$val['fieldid']?>]">
						<option value="">==不导入==</option>
					<?php foreach($stores as $k=>$v):?>
						<?php
							$selected = strposex($val['fieldname'], $k) ? ' selected="selected"' : '';
							if($k=='title' && $val['fieldname']=='name') $selected=' selected="selected"';
						?>
						<option value="<?=$k?>"<?=$selected?>><?=$v?>[<?=$k?>]</option>
					<?endforeach;?>
					</select>
				</td>
			</tr>
			<?endforeach;?>
		</table>
	</div>
	<center>
		<button type="button" class="btn" onclick="ajaxPost('frm_linkfield', '', 'taoke_save_start', null);">提交</button>&nbsp;
		<button type="button" class="btn" onclick="dlgClose();">关闭</button>
	</center>
</form>