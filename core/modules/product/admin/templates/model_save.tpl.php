<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>ģ�����ƣ�</strong></td>
                <td width="55%"><input type="text" name="t_model[name]" class="txtbox2" value="<?=$t_model['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�������ݱ�:</strong>ÿһ��ģ�͵��Զ����ֶν�������һ���½��ı��У�<br />���ݱ�������Ӣ����ĸ[a-z]������[0-9]��ɡ�<br /><span class="font_1">������д���޷��ٴθĶ�</span></td>
                <td>
                    <?if($disabled){?>
                        <?=$_G['dns']['dbpre'].$t_model['tablename']?>
                    <? }else{?>
                        <?=$_G['dns']['dbpre']?>product_data_<input type="text" name="t_model[tablename]" class="txtbox3" />
                    <?}?>
                </td>
            </tr>
        </table>
        <center>
            <?if($modelid>0){?><input type="hidden" name="modelid" value="<?=$modelid?>" /><?}?>
            <button type="submit" name="dosubmit" value="yes" class="btn" /> �� �� </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> �� �� </button>
        </center>
    </div>
</form>
</div>