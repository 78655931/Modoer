<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save');?>">
    <div class="space">
        <div class="subtitle">����/�༭����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="10%">���⣺</td>
                <td width="*">
                    <input type="text" name="title" value="<?=$detail['title']?>" class="txtbox" />
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
                <td class="altbg1">����</td>
                <td><input type="text" name="orders" value="<?=$detail['orders']?>" class="txtbox5"/></td>
            </tr>
            <tr>
                <td class="altbg1">�ɼ���</td>
                <td><?=form_bool('available',$detail['available'])?></td>
            </tr>
            <tr>
                <td class="altbg1">���ݣ�</td>
                <td><?=$edit_html?></td>
            </tr>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <?if($op=='edit'):?>
            <input type="hidden" name="id" value="<?=$_GET['id']?>" />
            <?endif;?>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="submit" name="dosubmit" value=" �ύ " class="btn" onclick="KE.util.setData('content');" />&nbsp;
            <input type="button" value=" ���� " class="btn" onclick="history.go(-1);" />
        </center>
    </div>
</form>
</div>