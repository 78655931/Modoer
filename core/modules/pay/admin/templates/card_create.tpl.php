<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'create')?>&">
	<div class="space">
		<div class="subtitle">�������ɳ�ֵ��</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
                <td width="45%" class="altbg1"><strong>����������</strong>���������������100��</td>
                <td width="*"><input type="text" name="create[num]" class="txtbox4" value="30" />&nbsp;1-100</td>
            </tr>
            <?if($MOD['card_prefix']):?>
			<tr>
                <td class="altbg1"><strong>ʹ�ÿ�ǰ׺��</strong>�ڱ������ɵĿ����У����뿨ǰ׺��</td>
                <td><?=form_bool('create[use_prefix]',0)?></td>
            </tr>
            <?endif;?>
			<tr>
                <td class="altbg1"><strong>��ֵ��</strong>��ֵ�������</td>
                <td><input type="text" name="create[price]" class="txtbox4" value="100" /></td>
            </tr>
			<tr>
                <td class="altbg1"><strong>��ֵ���ͣ�</strong>ѡ���ֵ����ֵ����</td>
                <td>
					<select name="create[type]" id="cztype" onchange="change_cztype();">
						<?if(in_array('rmb', $cz_type)):?><option value="rmb" ratio="1" unit="Ԫ">�ֽ�</option><?endif;?>
						<?=form_pay_groups()?>
					</select>
                </td>
            </tr>
			<tr>
                <td class="altbg1"><strong>��Ч������</strong>��ֵ������Ч�ڣ�����ʧЧ</td>
                <td><input type="text" name="create[endtime]" class="txtbox2" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?=date('Y-m-d',$_G['timestamp']+30*24*3600)?>" readonly /></td>
            </tr>
			<tr>
                <td class="altbg1"><strong>�����룺</strong>���ƹιο�����ֻ�п��ţ�û������</td>
                <td><?=form_bool('create[no_pw]',0)?></td>
            </tr>
		</table>
    </div>
    <center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="createsubmit" value="yes" class="btn">��ʼ����</button>&nbsp;
        <button type="button" class="btn" onclick="history.go(-1);">����</button>
    </center>
</form>
</div>