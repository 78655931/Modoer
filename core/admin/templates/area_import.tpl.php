<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
    <form method="post" action="<?=cpurl($module, $act, $op)?>" enctype="multipart/form-data">
        <div class="space">
            <div class="subtitle">�����������</div>
            <table class="maintable" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="45%" class="altbg1"><strong>����XML�ļ���</strong>ѡ�������Modoerϵͳվ��������վ���������ļ�</td>
                    <td width="*"><input type="file" name="area_import_file"></td>
                </tr>
                <tr>
                    <td class="altbg1"><strong>�Ѵ��ڳ��д���</strong>�ڵ����������ʱ�������ǰϵͳ�Ѵ���������У������ѡ���Բ���</td>
                    <td><?=form_radio('city_exists',array('0'=>'ȫ����ӣ������ж�',1=>'�����Ѵ��ڵĳ���'),0)?></td>
                </tr>
            </table>
        </div>
        <center>
            <input type="submit" name="dosubmit" value=" ��ʼ���� " class="btn" />&nbsp;
            <input type="button" value=" ���� " onclick="javascript:history.go(-1);" class="btn" />
        </center>
    </form>
</div>