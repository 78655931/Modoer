<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <input type="hidden" name="classsort" value="1" />
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - ��������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>����һ��Ļ������ͺͻ���:</strong>��������һ��Ļ�����ͳ�ֵ�ֽ�֮��Ķһ�����</td>
                <td width="*">
					<input type="checkbox" name="modcfg[cz_type][]"<?if(in_array('rmb',$modcfg['cz_type']))echo' checked="checked"';?> value="rmb" />�ֽ�<br />
					<?foreach($pointgroups as $k => $v):?>
					<?if(!$v['enabled']) continue;?>
					<input type="checkbox" name="modcfg[cz_type][]" <?if(in_array($k,$modcfg['cz_type']))echo' checked="checked"';?> value="<?=$k?>" /><?=$v['name']?>&nbsp;&nbsp;�һ����� <input type="text" name="modcfg[ratio_<?=$k?>]" value="<?=$modcfg['ratio_'.$k]?>" class="txtbox5" /><br />
					<?endforeach;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong>���ó�ֵ�Ļ������ƣ����磺����ң���Ԫ��ŷԪ��</td>
                <td><input type="text" name="modcfg[pricename]" class="txtbox4" value="<?=$modcfg['pricename']?$modcfg['pricename']:'�����'?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������С��ֵ:</strong>������������ֵ���������</td>
                <td><input type="text" name="modcfg[czmin]" class="txtbox4" value="<?=$modcfg['czmin']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ֵ:</strong>�����������ɳ�ֵ���������</td>
                <td><input type="text" name="modcfg[czmax]" class="txtbox4" value="<?=$modcfg['czmax']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����Զ��ر�ʱ��:</strong>��Ա�µ���û�����֧���Ķ�����һ��ʱ�����Զ��رգ�Ĭ��Ϊ24Сʱ��</td>
                <td><input type="text" name="modcfg[staletime]" class="txtbox4" value="<?=$modcfg['staletime']>0?$modcfg['staletime']:24?>" />&nbsp;Сʱ</td>
            </tr>
            <tr class="altbg2">
                <td></td><td><b><?=form_bool('modcfg[card]',$modcfg['card'])?>&nbsp;<b>���ֿ���ֵ����</b></td></tr>
            <tr>
                <td class="altbg1"><strong>����λ��:</strong>���ֿ�����λ����λ������15-20��</td>
                <td><input type="text" name="modcfg[card_numlen]" class="txtbox4" value="<?=$modcfg['card_numlen']?$modcfg['card_numlen']:15?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong>���ÿ��ŵ��������</td>
                <td><?=form_radio('modcfg[card_no_type]',array('numeric'=>'������','character'=>'����ĸ','mix'=>'����+��ĸ'),$modcfg['card_no_type'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ǰ׺:</strong>����ÿ�ų�ֵ�����ŵ�ǰ׺����������ĸ��ɣ����ܳ���10���ַ���</td>
                <td><input type="text" name="modcfg[card_prefix]" class="txtbox4" value="<?=$modcfg['card_prefix']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������λ��:</strong>���ֿ�������λ����λ������6-20��</td>
                <td><input type="text" name="modcfg[card_pwnum]" class="txtbox4" value="<?=$modcfg['card_pwnum']?$modcfg['card_pwnum']:6?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[alipay]',$modcfg['alipay'])?>&nbsp;<b>֧������ʱ���ʽӿ�����</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>֧�����ʺ�:</strong>�����ʺţ����ȵ� www.alipay ����֧�����ʺŲ������̼ҷ�����ļ�ʱ���ʽӿڡ�</td>
                <td><input type="text" name="modcfg[alipay_id]" class="txtbox2" value="<?=$modcfg['alipay_id']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������(partnerID):</strong>֧�����̼���ݺʹ�����ID��</td>
                <td><input type="text" name="modcfg[alipay_partnerid]" class="txtbox" value="<?=$modcfg['alipay_partnerid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���װ�ȫУ����(key):</strong>֧�����̼ҽ�������Ҫ�Ľ��װ�ȫУ���롣</td>
                <td><input type="text" name="modcfg[alipay_key]" class="txtbox" value="<?=$modcfg['alipay_key']?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[tenpay]',$modcfg['tenpay'])?>&nbsp;<b>�Ƹ�ͨ�ӿ�����</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ƹ�ͨ�ʺ�:</strong>�����ʺţ����ȵ� mch.tenpay.com ����Ƹ�ͨ�̼��ʺš�</td>
                <td><input type="text" name="modcfg[spid]" class="txtbox2" value="<?=$modcfg['spid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ƹ�ͨ�̼��ܳ�:</strong>�̼ҵ�֧���ܳף�ȷ���˲Ƹ�ͨ��ҵ��̨һ�£����������뵽�Ƹ�ͨ��ҵ��̨�����µģ���ͬ�������</td>
                <td><input type="text" name="modcfg[spkey]" class="txtbox" value="<?=$modcfg['spkey']?>" /></td>
            </tr>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[chinabank]',$modcfg['chinabank'])?>&nbsp;<b>�������߽ӿ�����</b></td></tr>
            <tr>
                <td class="altbg1"><strong>�����̻���:</strong>�����ʺţ����ȵ� www.chinabank.com.cn �����̼��ʺš�</td>
                <td><input type="text" name="modcfg[cb_mid]" class="txtbox2" value="<?=$modcfg['cb_mid']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����̼�MD5��Կ:</strong>�̼ҵ�MD5��Կ��ȷ����������̨һ�£����������뵽������̨�����µģ���ͬ�������</td>
                <td><input type="text" name="modcfg[cb_key]" class="txtbox" value="<?=$modcfg['cb_key']?>" /></td>
            </tr>
            <?if(is_file(MOD_ROOT.'model'.DS.'paypal_class.php')):?>
            <tr class="altbg2">
                <td></td><td><?=form_bool('modcfg[paypal]',$modcfg['paypal'])?>&nbsp;<b>PayPal֧���ӿ�����</b></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>PayPal�ʺ�:</strong>�����ʺţ����ȵ� www.paypal.com �����ʺ�(Email)��</td>
                <td><input type="text" name="modcfg[paypal_email]" class="txtbox2" value="<?=$modcfg['paypal_email']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>֧������:</strong>PayPal��֧�����֣�����վ���ʺ�ֻ����ȡ�����(CNY)����������У�CNY,USD��</td>
                <td><input type="text" name="modcfg[paypal_currency]" class="txtbox" value="<?=$modcfg['paypal_currency']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ʹ�ò����ʺ�(sandbox):</strong>ʹ�ò����ʺţ����Խ��������ʺų�ֵ�����ڵ��ԣ������ʺ�ע���ַ��www.sandbox.paypal.com</td>
                <td><?=form_bool('modcfg[paypal_test]', $modcfg['paypal_test'])?></td>
            </tr>
            <?endif;?>
        </table>
        <center><button type="submit" name="dosubmit" value="yes" class="btn" /> �ύ </button></center>
    </div>
</form>
</div>