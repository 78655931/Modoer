<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">�ʼ�����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>�ʼ����͵��Թ���:</strong>��������ʼ�����ʧ��ʱ�����Դ򿪱����ܣ��ȿ��Կ�������ʧ�ܵľ������⡣</td>
                <td width="*">
                    <?=form_bool('setting[mail_debug]',$config['mail_debug'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ƿ����� SMTP ��ʽ�����ʼ�:</strong>���������֧��PHP�� mail ����������Ҫ��������</td>
                <td>
                    <?=form_bool('setting[mail_use_stmp]',$config['mail_use_stmp'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SMTP ������:</strong>����ʹ����ҵ�ʾ֣���Ҫʹ��������䣬һ�㶼�з�������</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp]" value="<?=$config['mail_stmp']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>SMTP �������˿�:</strong>Ĭ��Ϊ�˿�Ϊ25</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_port]" value="<?=($config['mail_stmp_port']?$config['mail_stmp_port']:'25')?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�û�����:</strong>��ҵ�ʾ���������д��������Ҫ@������׺</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_email]" value="<?=$config['mail_stmp_email']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�û��ʺ�:</strong>һ���û�������û��˺�����ͬ��</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_username]" value="<?=$config['mail_stmp_username']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�û�����:</strong>��д�����˺�����</td>
                <td><input type="text" class="txtbox" name="setting[mail_stmp_password]" value="<?=$config['mail_stmp_password']?'******':''?>" /></td>
            </tr>
        </table>
        <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
    </div>
<?=form_end()?>
</div>