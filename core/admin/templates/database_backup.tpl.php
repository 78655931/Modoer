<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <input type="hidden" name="dobackup" value="yes" />
    <div class="space">
        <div class="subtitle">��վ���ݱ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">��������ʽ:</td>
                <td width="*"><select name="sqlcompat">
                    <option value="" selected="selected">Ĭ��</option>
                    <option value="MYSQL40">MySQL 3.23/4.0.x</option>
                    <option value="MYSQL41">MySQL 4.1.x/5.x</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">ʹ����չ����(Extended Insert)��ʽ:</td>
                <td><select name="extendins">
                    <option value="1">��</option>
                    <option value="0" selected="selected">��</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">����ַ����޶�(SET NAMES):</td>
                <td><select name="addsetnames">
                    <option value="" selected="selected">Ĭ��</option>
                    <option value="1">��</option>
                    <option value="0">��</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">���ݷ�Χ:</td>
                <td><select name="backuplimit">
                    <option value="modoer" selected="selected">Modoer���ݱ�</option>
                    <option value="all">ȫ�����ݱ�</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">�־��� - ÿ���ļ���������Ϊ:</td>
                <td><input type="text" name="sizelimit" value="2048" class="txtbox4" /> KB</td>
            </tr>
            <tr>
                <td class="altbg1">�����ļ���:</td>
                <td><input type="text" name="filename" value="<?=$backfilename?>" class="txtbox2" /> .sql</td>
            </tr>
        </table>
    </div>
    <center><input type="submit" name="dosubmit" value=" �ύ " class="btn" /></center>
</form>
</div>