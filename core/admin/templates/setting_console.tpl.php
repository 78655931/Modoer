<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��̨����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1"><strong>��̨��½��֤��:</strong>��ֹ��̨���뱻�²�</td>
                <td><?=form_bool('setting[console_seccode]', $config['console_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������̨��ҳ����ͳ��:</strong>�ں�̨��¼����ҳ��ʾ���������ͳ�ƹ���</td>
                <td><?=form_bool('setting[console_total]', $config['console_total'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>ָ����ݲ˵��Ĳ˵���:</strong>���ú�̨��ݲ˵�����ʵ�ֺ�̨�Զ������ÿ�ݲ˵����ܣ�<br /><span class="font_2">��֧�ֶ������ģ�� url ��ǩ��ʽ����</span></td>
                <td width="*">
                <select name="setting[console_menuid]">
                    <option value="">==ѡ��˵���==</option>
                    <?=form_menu_main($config['console_menuid'])?>
                </select>
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>