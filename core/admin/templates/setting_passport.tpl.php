<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td><b>������ֻ�ʺ� Discuz 6.0�����°汾��Discuz 6.1 UC����ʹ�� UCenter ���ϡ�</b><br />������ͨ��֤�󣬻�Աע�ᡢ��¼���˳����������뽫��ʹ��Mudder�Ļ�Աϵͳ������ٷ�û���ṩ��ĳϵͳ�Ľӿ��ļ�����ο���ϵͳ��ͨ��֤�ӿڵ�˵���ĵ����ڵ�����ϵͳ���⼸������ģ���м�����ñ�ϵͳ�ṩ��Զ��API�ӿڼ��ɣ�Mudder�ķ���ӿ���������ϵͳ����վ�ڲ�ͬ�ķ�����������������ͬ�������������ǣ�bbs.abc.com �� www.abc.com �����Ĺ�ϵ��<br />
    </td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">ͨ��֤����(��������)</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="50%" class="altbg1">����ͨ��֤��</td>
                <td width="50%"><input type="radio" name="passport[enable]" value="1" <?=$passport_enable[1]?> /> ��&nbsp;&nbsp;<input type="radio" name="passport[enable]" value="0" <?=$passport_enable[0]?> /> ��</td>
            </tr>
            <tr>
                <td class="altbg1">ͨ��֤˽���ܳף�</td>
                <td><?=$_config['authkey']?></td>
            </tr>
            <tr>
                <td class="altbg1">Cookieǰ׺��</td>
                <td><?=$cookiepre?></td>
            </tr>
            <tr>
                <td class="altbg1">����ϵͳ���ƣ�</td>
                <td><input type="text" name="passport[systemname]" class="txtbox" value="<?=$systemname?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ϵͳ��ҳ��</td>
                <td><input type="text" name="passport[index_url]" class="txtbox" value="<?=$index_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Աע����ַ��</td>
                <td><input type="text" name="passport[reg_url]" class="txtbox" value="<?=$reg_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ա��½��ַ��</td>
                <td><input type="text" name="passport[login_url]" class="txtbox" value="<?=$login_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ա�˳���ַ��</td>
                <td><input type="text" name="passport[logout_url]" class="txtbox" value="<?=$logout_url?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����������ַ��</td>
                <td><input type="text" name="passport[cpwd_url]" class="txtbox" value="<?=$cpwd_url?>" /></td>
            </tr>
        </table>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    </div>
<?=form_end()?>
</div>