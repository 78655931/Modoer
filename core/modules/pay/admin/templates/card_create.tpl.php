<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'create')?>&">
	<div class="space">
		<div class="subtitle">批量生成充值卡</div>
		<table class="maintable" border="0" cellspacing="0" cellpadding="0">
			<tr>
                <td width="45%" class="altbg1"><strong>生成数量：</strong>单次最大允许生成100张</td>
                <td width="*"><input type="text" name="create[num]" class="txtbox4" value="30" />&nbsp;1-100</td>
            </tr>
            <?if($MOD['card_prefix']):?>
			<tr>
                <td class="altbg1"><strong>使用卡前缀：</strong>在本次生成的卡号中，加入卡前缀。</td>
                <td><?=form_bool('create[use_prefix]',0)?></td>
            </tr>
            <?endif;?>
			<tr>
                <td class="altbg1"><strong>面值：</strong>充值金币数额</td>
                <td><input type="text" name="create[price]" class="txtbox4" value="100" /></td>
            </tr>
			<tr>
                <td class="altbg1"><strong>充值类型：</strong>选择充值卡充值类型</td>
                <td>
					<select name="create[type]" id="cztype" onchange="change_cztype();">
						<?if(in_array('rmb', $cz_type)):?><option value="rmb" ratio="1" unit="元">现金</option><?endif;?>
						<?=form_pay_groups()?>
					</select>
                </td>
            </tr>
			<tr>
                <td class="altbg1"><strong>有效期至：</strong>充值卡的有效期，过期失效</td>
                <td><input type="text" name="create[endtime]" class="txtbox2" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" value="<?=date('Y-m-d',$_G['timestamp']+30*24*3600)?>" readonly /></td>
            </tr>
			<tr>
                <td class="altbg1"><strong>无密码：</strong>类似刮刮卡，即只有卡号，没有密码</td>
                <td><?=form_bool('create[no_pw]',0)?></td>
            </tr>
		</table>
    </div>
    <center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="createsubmit" value="yes" class="btn">开始生成</button>&nbsp;
        <button type="button" class="btn" onclick="history.go(-1);">返回</button>
    </center>
</form>
</div>