<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <form method="post" name="myform" action="<?=cpurl($module,$act,'import')?>" enctype="multipart/form-data" >
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">����������ݿ�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="30%"><strong>�����ļ�:</strong>��������ݵ����ļ������ļ��ɵ������ܵ�����</td>
                <td width="*"><input type="file" name="importfile" /><br />
                <span class="font_1">ע�⣺���е��������������ձ��صĵ������ݡ�</span>
                </td>
                <td width="20%" class="altbg1" style="text-align:center;"><button type="button" class="btn" onclick="window.open('<?=cpurl($module,$act,'export')?>');" /><b>������������</b></button></td>
            </tr>
        </table>
        <center>
            <button type="submit" name="dosubmit" value="yes" class="btn" />����</button>&nbsp;
        </center>
    </div>
    </form>
</div>