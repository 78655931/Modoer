<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">��ӷ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>�������ƣ�</strong></td>
                <td width="*"><input type="text" name="t_cat[name]" class="txtbox" value="" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ѡ������ģ�ͣ�</strong>ָ��һ���Ѵ��ڵ�ģ�ͣ�ģ�Ͱ��������ֶκ͵����<br /><span class="font_1">������ѡ����޷��޸ġ�</span></td>
                <td><select name="t_cat[modelid]">
                    <option value="">==ģ���б�==</option>
                    <?foreach($models as $val){?>
                    <option value="<?=$val['modelid']?>"><?=$val['name']?></option>
                    <?}?>
                </select>&nbsp;<a href="<?=cpurl($module,'model_add')?>">�½�����ģ��</a><br /><span id="des"></span></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ѡ��������飺</strong>ָ��һ����������ڻ�Ա������Ĵ�����ݡ�</td>
                <td><select name="t_cat[review_opt_gid]">
                    <option value="">==���������б�==</option>
					<?=form_review_opt_group()?>
                </select>&nbsp;<a href="<?=cpurl('review','opt_group')?>">�½���������</a><br /><span id="des"></span></td>
            </tr>
        </table>
        <center>
            <button type="submit" name="dosubmit" value="yes" class="btn" /> �� �� </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /> �� �� </button>
        </center>
    </div>
</form>
</div>