<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">���ݻ���</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" class="altbg1" width="50%"><strong>���ݵ��û�����·��</strong>�뾡�����ڷ�./data�£����Ҫ��б��"/"��"\"��<br />Ĭ��Ϊ��./data/datacall</td>
                <td width="*"><input type="text" name="setting[datacall_dir]" value="<?=$config['datacall_dir']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>�Զ��������ݻ���</strong>����һ��ʱ������ɾ�����ݵ��õĻ��棬ʱ�������˹��̣���������ӷ�����������0��ʾ������</td>
                <td><input type="text" name="setting[datacall_clearinterval]" value="<?=(int)$config['datacall_clearinterval']?>" class="txtbox5" />&nbsp;Сʱ</td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>�������ݻ����ʱ�䷶Χ</strong>�����������ʱ��ķ�Χ�������������Զ��������ݻ��湦�ܺ���Ч��0��ʾɾ��ȫ������</td>
                <td><input type="text" name="setting[datacall_cleartime]" value="<?=(int)$config['datacall_cleartime']?>" class="txtbox5" />&nbsp;Сʱ</td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>������������ʱ��</strong>���û������Ľ���������л��棬Ĭ��Ϊ60���ӡ�</td>
                <td><input type="text" name="setting[search_life]" value="<?=$config['search_life']>0?$config['search_life']:60?>" class="txtbox5" />&nbsp;����</td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>