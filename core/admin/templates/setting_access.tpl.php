<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��ȫ����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>��վ������:</strong>Cookie��ͨ��֤�����룬�޸ĺ󣬻�Ա�ͺ�̨�û���Ҫ���µ�¼������������벻Ҫ�޸ġ�</td>
                <td width="*"><?=form_input("setting[authkey]", $config['authkey'], "txtbox")?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>IP �����б�:</strong>ֻ�е��û����ڱ��б��е� IP ��ַʱ�ſ��Է��ʱ���վ���б�����ĵ�ַ���ʽ���Ϊ IP ����ֹ����������������ҵ��ѧУ�ڲ���վ�ȼ����𳡺ϡ������ܶԹ���Աû���������������Ա���ڴ��б�Χ�ڽ�ͬ�����ܵ�¼�����������ʹ�ñ����ܡ�ÿ�� IP һ�У�������������ַ������ "192.168.1.241"������Ϊ���� IP ����ȷ��ֹ��������ɷ���</td>
                <td><textarea name="setting[useripaccess]" rows="8" cols="40" class="txtarea"><?=$config['useripaccess']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����Ա��̨ IP �����б�:</strong>ֻ�е�����Ա���ڱ��б��е� IP ��ַʱ�ſ��Է�����վϵͳ���ã��б�����ĵ�ַ���ʽ��޷����ʣ����Կɷ�����վǰ���û����棬���������ʹ�ñ����ܡ�ÿ�� IP һ�У�������������ַ������ "192.168.1.241"������Ϊ���� IP ����ȷ��ֹ��������ɷ���ϵͳ����</td>
                <td><textarea name="setting[adminipaccess]" rows="6" cols="40" class="txtarea"><?=$config['adminipaccess']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ֹ IP �����б�:</strong>���οʹ��ڱ��б��е� IP ��ַʱ���޷�������վ��ÿ�� IP һ�У�������������ַ������ "192.168.1.241"������Ϊ����ֹ���κ� IP ����</td>
                <td><textarea name="setting[ban_ip]" rows="6" cols="40" class="txtarea"><?=$config['ban_ip']?></textarea></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>