<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.store-table { width:100% }
.store-table td { padding:5px; }
.store-table td.altbg1 { text-align:right; padding-right:10px; }
.store-table td img { max-height:150px; max-height:100px; }
</style>
<div class="space">
	<table class="store-table" border="0" cellspacing="1" cellpadding="1">
		<tr>
			<td width="100" class="altbg1">���̱�ţ�</td>
			<td width="*"><?=$detail['sid']?></td>
		</tr>

        <tr>
        
    <td class="altbg1">�����ǳƣ�</td>
            <td><?=$detail['nick']?></td>

        </tr>

        <tr>

            <td class="altbg1">���̱��⣺</td>

            <td><?=$detail['title']?></td>

        </tr>
		<?if($detail['pic_path']):?>
		<tr>
			<td class="altbg1">��꣺</td>
			<td><img src="<?=$detail['pic_path']?>" /></td>
		</tr>
		<?endif;?>
		<tr>
			<td class="altbg1">����ʱ�䣺</td>
			<td><?=$detail['created']?></td>
		</tr>
	</table>
</div>