<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <div class="space">
        <div class="subtitle"><?=$_VERSION['name']?> ���ݱ���Ϣ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20%">���ݱ�����</td>
                <td width="20%">����ʱ��</td>
                <td width="20%">������ʱ��</td>
                <td width="10%">��¼��</td>
                <td width="10%">����</td>
                <td width="10%">����</td>
                <td width="10%">��Ƭ</td>
            </tr>
            <? if($mudder_table_info) { foreach($mudder_table_info as $info) { ?>
            <tr>
                <td><?=$info['Name']?></td>
                <td><?=$info['Create_time']?></td>
                <td><?=$info['Check_time']?></td>
                <td><?=$info['Rows']?></td>
                <td><?=size_unit($info['Data_length'])?></td>
                <td><?=size_unit($info['Index_length'])?></td>
                <td><?=size_unit($info['Data_free'])?></td>
            </tr>
            <? } ?>
            <tr class="altbg2">
                <td><strong>�ϼ�:</strong>&nbsp;<?=count($mudder_table_info)?>����</td>
                <td></td>
                <td></td>
                <td><?=$mudder_rows_total?></td>
                <td><?=size_unit($mudder_Data_length)?></td>
                <td><?=size_unit($mudder_Index_length)?></td>
                <td><?=size_unit($mudder_Data_free)?></td>
            </tr>
            <? } ?>
        </table>
    </div>
    <div class="space">
        <div class="subtitle">���� ���ݱ���Ϣ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="20%">���ݱ�����</td>
                <td width="20%">����ʱ��</td>
                <td width="20%">������ʱ��</td>
                <td width="10%">��¼��</td>
                <td width="10%">����</td>
                <td width="10%">����</td>
                <td width="10%">��Ƭ</td>
            </tr>
            <? if($other_table_info) { foreach($other_table_info as $info) { ?>
            <tr>
                <td><?=$info['Name']?></td>
                <td><?=$info['Create_time']?></td>
                <td><?=$info['Check_time']?></td>
                <td><?=$info['Rows']?></td>
                <td><?=size_unit($info['Data_length'])?></td>
                <td><?=size_unit($info['Index_length'])?></td>
                <td><?=size_unit($info['Data_free'])?></td>
            </tr>
            <? } ?>
            <tr class="altbg2">
                <td><strong>�ϼ�:</strong>&nbsp;<?=count($other_table_info)?>����</td>
                <td></td>
                <td></td>
                <td><?=$other_rows_total?></td>
                <td><?=size_unit($other_Data_length)?></td>
                <td><?=size_unit($other_Index_length)?></td>
                <td><?=size_unit($other_Data_free)?></td>
            </tr>
            <? } else { ?>
            <tr >
                <td colspan="7">û���������ݱ�</td>
            </tr>
            <? } ?>
        </table>
    </div>
</div>