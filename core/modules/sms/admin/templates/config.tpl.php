<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>�ֻ�������֤:</strong>ʹ��������ʽ��֤�ֻ�����ĸ�ʽ��ȷ�ԡ�</td>
                <td><input type="text" name="modcfg[preg_match]" value="<?=$modcfg['preg_match']?$modcfg['preg_match']:"/^1[3|5|8]{1}[0-9]{9}$/"?>" class="txtbox" /></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>