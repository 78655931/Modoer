<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>
        <li>������ PHPWind ���� Discuz6.0 �����°汾�������ϡ�</li>
        <li>�ύ��ϵͳ���Զ����� ./api/mudder_passport_client.php �ļ���Ȼ���뽫����ļ����Ƶ�����ϵͳ��Ŀ¼�£������Ҫ�޸�����ϵͳע��͵�¼���ֵĴ��롣</li>
        <li>�������������ַ�������²���һ���ύ��ҳ�����Ƶ�����ϵͳ��Ŀ¼�Ĺ��̡�</li>
    </td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">ͨ��֤��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="15%" class="altbg1">����ͨ��֤���ϣ�</td>
                <td width="80%"><?=form_bool('passport[enable]',$passport['enable'])?></td>
            </tr>
            <tr>
                <td class="altbg1">ͨ��֤˽���ܳף�</td>
                <td><?=$_G['cfg']['authkey']?></td>
            </tr>
            <tr>
                <td class="altbg1">Cookieǰ׺��</td>
                <td><?=$_G['cookiepre']?></td>
            </tr>
            <tr>
                <td class="altbg1">����ϵͳ���ƣ�</td>
                <td><input type="text" name="passport[systemname]" class="txtbox" value="<?=$passport['systemname']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����ϵͳ��ҳ��</td>
                <td><input type="text" name="passport[index_url]" class="txtbox" value="<?=$passport['index_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Աע����ַ��</td>
                <td><input type="text" name="passport[reg_url]" class="txtbox" value="<?=$passport['reg_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ա��½��ַ��</td>
                <td><input type="text" name="passport[login_url]" class="txtbox" value="<?=$passport['login_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">��Ա�˳���ַ��</td>
                <td><input type="text" name="passport[logout_url]" class="txtbox" value="<?=$passport['logout_url']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����������ַ��</td>
                <td><input type="text" name="passport[cpwd_url]" class="txtbox" value="<?=$passport['cpwd_url']?>" /></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>