<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>�ȼ�������ϵͳ���ù�����Ա�ȼ��Ĳ�����չ�ֶΣ�<br />�һ�����Ϊ������ֶ�Ӧһ����λ��׼���ֵ�ֵ������ point1 �ı���Ϊ 1.5(�൱�� 1.5 ����λ��׼����)��point2 �ı���Ϊ 3(�൱�� 3 ����λ��׼����)��point3 �ı���Ϊ 15(�൱�� 15 ����λ��׼����)���� point3 �� 1 ���൱�� point2 �� 5 �ֻ� point1 �� 10 �֡�һ�����öһ����ʣ����û��������ڸ����������жһ����������˶һ����ʵĻ��֣��粻ϣ��ʵ�л������ɶһ����뽫��һ���������Ϊ 0</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg1">
                <td width="30">����</td>
                <td width="80">�ֶ�����</td>
                <td width="120">��������</td>
                <td width="120">���ֵ�λ</td>
                <td width="50">����</td>
                <td width="50">�ҳ�</td>
                <td width="*">�һ�����</td>
            </tr>
            <?foreach(array('point1','point2','point3','point4','point5','point6') as $key) {?>
            <tr>
                <td><input type="checkbox" name="point_group[<?=$key?>][enabled]" value="1"<?if($point_group[$key]['enabled'])echo' checked="checked"'?> /></td>
                <td><?=$key?></td>
                <td><input type="text" name="point_group[<?=$key?>][name]" value="<?=$point_group[$key]['name']?>" class="txtbox4" /></td>
                <td><input type="text" name="point_group[<?=$key?>][unit]" value="<?=$point_group[$key]['unit']?>" class="txtbox4" /></td>
                <td><input type="checkbox" name="point_group[<?=$key?>][in]" value="1"<?if($point_group[$key]['in'])echo' checked="checked"'?> /></td>
                <td><input type="checkbox" name="point_group[<?=$key?>][out]" value="1"<?if($point_group[$key]['out'])echo' checked="checked"'?> /></td>
                <td><input type="text" name="point_group[<?=$key?>][rate]" value="<?=$point_group[$key]['rate']?>" class="txtbox5" /></td>
            </tr>
            <?}?>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>