<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    	<tr><td>Modoer ����û�и��� UCenter �ͻ��˰����뵽 www.comsenz.com ���ؿͻ��˰����뽫�ͻ��˰��� "uc_client" Ϊ���ƴ���� Modoer ϵͳ�ĸ�Ŀ¼�����������̳Ϊ Discuz 6.1.0 ��ֱ�ӽ� Discuz �µ� "uc_client" �ļ��и��Ƶ� Modoer ��Ŀ¼�¼��ɡ���д����������Ϣ��ʹ UCenter ��ͨ���ܳ��� UCenter Server ������һ�£��������˰�װ����� UCenter ��װ�ֲ��<a href="http://www.modoer.com/bbs" target="_blank">��̳</a>������</td></tr>
    </table>
</div>
<?if(!is__writable(MUDDER_ROOT . 'uc_client' . DS . 'data' . DS . 'cache')):?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_system_msg_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span class="font_4">�ļ��� uc_client/data/cache û��д��Ȩ�ޣ���Ӱ������ϵͳ��¼ͬ���������ø��ļ��к��ļ����ֿɶ�д״̬������ʾͬ������������ϵͳ�µ�uc_client</span>
                </td>
            </tr>
        </table>
    </div>
<?endif;?>
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">UCenter</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>����Ucenter����:</strong>����ǰ����ȷ�����ͻ�����������˶���װ��ʹ�á�</td>
                <td width="55%"><?=form_bool('modcfg[uc_enable]', $modcfg['uc_enable']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���û��ֶһ�����:</strong>����ǰ����ȷ��UCenter�������������ֶһ�����ͬ��������UCenter�������˻��ֶһ�����ε��ͬ�����á�</td>
                <td><?=form_bool('modcfg[uc_exange]', $modcfg['uc_exange']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����¼�Feed���͵�UC:</strong>���������ܣ��ɽ���Ա��Modoer�ϵĲ����¼���ʾ�� UCHome/Discuz!X �С�</td>
                <td><?=form_bool('modcfg[uc_feed]', $modcfg['uc_feed']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ø�����ҳ��תUCHome:</strong>���ú󣬵��Modoer�Ļ�Ա�ռ佫ֱ����ת�� UCHome/Discuz!X �ĸ�����ҳ��</td>
                <td><?=form_bool('modcfg[uc_uch]', $modcfg['uc_uch']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCHome����ַ:</strong>��дUChome��URL��ַ������벻Ҫ���"/"����������������תUChmoe���ܺ���Ч��</td>
                <td><input type="text" class="txtbox2" name="modcfg[uc_uch_url]" value="<?=$modcfg['uc_uch_url']?>" /></td>
            </tr>
        </table>
        <div class="subtitle">��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($disabled) {?>
            <tr><td class="altbg2" colspan="2"><span class="font_1">�����ļ�����д���޷��༭������Ϣ��</span></td></tr>
            <? } ?>
            <tr>
                <td width="45%" class="altbg1"><strong>UCenter Ӧ�� ID:</strong>��ֵΪ��ǰϵͳ�� UCenter ��Ӧ�� ID</td>
                <td width="55%"><input type="text" class="txtbox2" name="uc[appid]" value="<?=UC_APPID?>" <?=$disabled?> /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ͨ����Կ:</strong>ͨ����Կ������ UCenter �� Modoer ֮�䴫����Ϣ�ļ��ܣ��ɰ����κ���ĸ�����֣����� UCenter �� Modoer ������ȫ��ͬ��ͨѶ��Կ����ȷ������ϵͳ�ܹ�����ͨ��</td>
                <td><input type="text" class="txtbox2" name="uc[key]" value="<?=UC_KEY?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter �������˷��ʵ�ַ:</strong>���� UCenter ��ַ����Ŀ¼�ı������£��޸Ĵ���<br />����: http://www.sitename.com/uc_server (���Ҫ��'/')��discuz!X Ĭ������http://www.discuzxsite.com/uc_server</td>
                <td><input type="text" class="txtbox2" name="uc[api]" value="<?=UC_API?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter IP��ַ:</strong>������ķ������޷�ͨ���������� UCenter���������� UCenter �������� IP ��ַ</td>
                <td><input type="text" class="txtbox2" name="uc[ip]" value="<?=UC_IP?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ӷ�ʽ:</strong>��������ķ��������绷��ѡ���ʵ������ӷ�ʽ</td>
                <td><input type="radio" name="uc[connect]" value="mysql" <?if(UC_CONNECT=='mysql') echo 'checked="checked"' ?><?=$disabled?>/> ���ݿⷽʽ&nbsp;&nbsp;<input type="radio" name="uc[connect]" value="" <?if(UC_CONNECT==NULL) echo 'checked="checked"' ?> <?=$disabled?>/> �ӿڷ�ʽ</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ݿ������:</strong>�����Ǳ���Ҳ������Զ�����ݿ����������� MySQL �˿ڲ���Ĭ�ϵ� 3306������д������ʽ��127.0.0.1:6033</td>
                <td><input type="text" class="txtbox2" name="uc[dbhost]" value="<?=UC_DBHOST?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ݿ��û���:</strong>UCenter ���ӷ�ʽΪ���ݿⷽʽʱ��Ч</td>
                <td><input type="text" class="txtbox2" name="uc[dbuser]" value="<?=UC_DBUSER?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ݿ�����:</strong>UCenter ���ӷ�ʽΪ���ݿⷽʽʱ��Ч</td>
                <td><input type="text" class="txtbox2" name="uc[dbpw]" value="<?=$ucdbpw?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ݿ���:</strong>UCenter�����ݿ����ƣ���д���󣬽��޷�ע��͵�¼������SQL����</td>
                <td><input type="text" class="txtbox2" name="uc[dbname]" value="<?=UC_DBNAME?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ���ݿ��ַ���:</strong>UCenter ���ӷ�ʽΪ���ݿⷽʽʱ��Ч</td>
                <td><input type="text" class="txtbox2" name="uc[dbcharset]" value="<?=UC_DBCHARSET?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter ��ǰ׺:</strong>����ϸȷ��UCenter��ǰ׺��Discuz!X��UCenterĬ�ϱ�ǰ׺Ϊ pre_ucenter_�������discuzһ���Ĭ���� dbc_uc_������Ĭ��Ϊ uc_</td>
                <td><input type="text" class="txtbox2" name="uc[dbtablepre]" value="<?=$uctablepre?>" <?=$disabled?> /></td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value=" �ύ " class="btn" /></center>
    </div>
</form>
</div>