<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<style type="text/css">
.maintable td img { max-width:270px; max-height:100px; 
	_width:expression(this.width > 270 ? 270 : true); _height:expression(this.height > 100 ? 100 : true); }
</style>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save_bcastr')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">�ϴ�����ͼƬ</div>
        <?if($edit_links):?>
        <ul class="cptab">
            <?foreach($edit_links as $val):?>
            <li<?if($val['flag']=='item:subject_setting_bcastr')echo' class="selected"';?>><a href="<?=$val['url']?>" onfocus="this.blur()"><?=$val['title']?></a></li>
            <?endforeach;?>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr>
            	<td width="80" class="altbg1">���⣺</td>
                <td width="320">
                    <input type="text" name="title" id="title" value="<?=$detail[title]?>" class="txtbox2">
                </td>
                <td width="*" class="font_2">��д����ͼƬ����</td>
            </tr>
            <tr>
            	<td class="altbg1">����</td>
                <td>
                    <input type="text" name="listorder" id="listorder" value="<?=$detail[listorder]?>" class="txtbox2">
                </td>
                <td class="font_2">ͼƬ�ֻ���ʾ��˳��</td>
            </tr>
            <tr>
            	<td class="altbg1">���ӣ�</td>
                <td>
                    <input type="text" name="url" id="url" value="<?=$detail[url]?$detail[url]:'http://'?>" class="txtbox2">
                </td>
                <td class="font_2">��дͼƬ��������ҳ���ӣ���ͷ�����http://</td>
            </tr>
            <tr>
            	<td class="altbg1">ͼƬ��</td>
                <td>
                    <?if($detail[picture]):?>
                    <div><img src="<?=URLROOT.'/'.$detail[picture]?>" /></div>
                    <?endif;?>
                    <div><input type="file" name="picture" id="picture"></div>
                </td>
                <td class="font_2">����ѡ��ͼƬ�ϴ�Ϊ����ͼƬ��ͼƬ�涨�ߴ磺<?=$bcastr_width?>px���� �� <?=$bcastr_height?>px���ߣ�</td>
            </tr>
        </table>
    </div>
	<center>
		<input type="hidden" name="sid" value="<?=$sid?>">
		<?if($flag):?><input type="hidden" name="flag" value="<?=$flag?>" /><?endif;?>
		<input type="hidden" name="op" value="update_banner" />
		<button type="button" class="btn" onclick="easy_submit('myform','save_bcastr',null)">�ύ</button>
		<button type="button" class="btn" onclick="history.go(-1);">����</button>
	</center>
</form>
</div>