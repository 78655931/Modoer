<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">���������Ż�</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>��������ģ��:</strong>������Ҫʹ�û�Ա��ģ�������ģ�͡�<span class="font_1">ע�⣺ÿ�θĶ����ã�����������ģ���ֶ������ӻ���ɾ����Ա����Ϣ�ֶΣ��ر���ȡ����������ֱ��ɾ�����ϻ�Ա����Ϣ�ֶΡ�</span></td>
                <?php 
                    $models = $_G['loader']->variable('model','item');
                    $modcfg['modelids'] = !$modcfg['modelids'] ? array() : unserialize($modcfg['modelids']);
                ?>
                <td width="*"><?=form_check('modcfg[modelids][]',$models,$modcfg['modelids'],'','&nbsp;');?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>���û�Ա������:</strong>��Ա������ǰ̨ͨ����д��Ա�����������ȡ<?=$MOD['name']?>��</td>
                <td width="*"><?=form_bool('modcfg[apply]',$modcfg['apply']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����������֤��:</strong></td>
                <td><?=form_bool('modcfg[applyseccode]',$modcfg['applyseccode']);?></td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>�����Ա���۳���������:</strong>���û������͡�</td>
                <td>
                    <select name="modcfg[pointgroup]">
                        <option value="">ѡ���������</option>
                        <?=form_member_pointgroup($modcfg['pointgroup']);?>
                    </select>
                </td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>�����Ա���۳�����:</strong>����Ա�ύ��Ա������ʱ���۳���Ա����Ӧ���֣����������ʧ�ܣ����˻���Ա���֡�</td>
                <td><input name="modcfg[coin]" type="text" class="txtbox5" value="<?=$modcfg['coin']?>" /></td>
            </tr>
            <tr>
            	<td class="altbg1"><strong>������������:</strong>ÿ����Ա���ֻ����������Ż�Ա����</td>
                <td><input name="modcfg[applynum]" type="text" class="txtbox5" value="<?=$modcfg['applynum']?>" /> ��</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>����˵��:</strong>��Ա�ύ����ʱ����ʾ���û���������˵����</td>
                <td><textarea name="modcfg[applydes]" rows="5" cols="10"><?=$modcfg['applydes']?></textarea></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>ģ����ҳ������:</strong>&lt;title&gt;����ʾ�ĸ�����</td>
                <td width="*"><input type="text" name="modcfg[subtitle]" value="<?=$modcfg['subtitle']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ����</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ������</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>