<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
var g;
function reload() {
    var obj = document.getElementById('reload');
    var btn = document.getElementById('switch');
    if(obj.innerHTML.match(/^<.+href=.+>/)) {
        g = obj.innerHTML;
        obj.innerHTML = '<input type="file" name="picture" size="20">';
        btn.innerHTML = 'ȡ���ϴ�';
    } else {
        obj.innerHTML = g;
        btn.innerHTML = '�����ϴ�';
    }
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,'save')?>" enctype="multipart/form-data">
    <div class="space">
        <div class="subtitle">���/�༭ͼƬ�ֻ�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="120" class="altbg1">ͼƬ�����ƣ�</td>
                <td width="*">
                    <input type="text" name="groupname" id="groupname" value="<?=$detail['groupname']?>" class="txtbox4" />&nbsp;
                    <select onchange="$('#groupname').val($(this).val());">
                        <option value="">==�Ѵ��ڵ���=</option>
                        <?=form_bcastr_group($detail['groupname'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td>
                    <select name="city_id" id="city_id">
                        <?=form_city($detail['city_id'], TRUE, !$admin->is_founder);?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">���⣺</td>
                <td><input type="text" name="itemtitle" value="<?=$detail['itemtitle']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1">��ʾ��</td>
                <td><?=form_bool('available',isset($detail['available'])?$detail['available']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1">˳��</td>
                <td><input type="text" name="orders" value="<?=$detail['orders']?>" class="txtbox4" /></td>
            </tr>
            <tr>
                <td class="altbg1">��ַ��</td>
                <td><input type="text" name="item_url" value="<?=$detail['item_url']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1">ͼƬ��</td>
                <td>
                    <?if(!$detail['link']):?>
                    <input type="file" name="picture" size="20" />
                    <?else:?>
                    <span id="reload"><a href="<?=$detail['link']?>" target="_blank" src="<?=$detail['link']?>" onmouseover="tip_start(this);"><?=$detail['link']?></a></span>&nbsp;
                    [<a href="javascript:reload();" id="switch">�����ϴ�</a>]
                    <?endif;?>
                </td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <?if($op=='edit'):?>
            <input type="hidden" name="bcastr_id" value="<?=$bcastr_id?>" />
            <?endif;?>
            <button type="submit" name="dosubmit" value="yes" class="btn">�ύ����</button>
            <button type="button" onclick="history.go(-1);" class="btn">����</button>
        </center>
    </div>
</form>
</div>