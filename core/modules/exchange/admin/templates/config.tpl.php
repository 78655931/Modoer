<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>��Ʒ����ͼ�ߴ�����:</strong>��ƷͼƬ�ϴ����Զ���ȡ���ߴ磬��� x �߶ȣ�Ĭ��160��100</td>
                <td><input type="text" name="modcfg[thumb_w]" value="<?=$modcfg['thumb_w']?$modcfg['thumb_w']:160?>" class="txtbox5" /> x <input type="text" name="modcfg[thumb_h]" value="<?=$modcfg['thumb_h']?$modcfg['thumb_h']:100?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>�һ��ύ��ʾ��֤��:</strong>�ύ�һ���ʱ����ʾ��֤��</td>
                <td width="*"><?=form_bool('modcfg[exchange_seccode]', $modcfg['exchange_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>�Ƹ�������ֶ�</strong>ǰ̨��ʾ�ĲƸ���Ĺ�����������</td>
                <td>
                    <select name="modcfg[pointgroup]">
                        <option value="">ѡ���������</option>
                        <?=form_member_pointgroup($modcfg['pointgroup']);?>
                    </select>
                </td>
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