<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function display(d) {
    $('#tr_gbook_guest').css("display",d);
    $('#tr_seccode').css("display",d);
}
</script>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>����������� Modoer ���˿ռ���ת�� UCHome �ĸ��˿ռ�ʱ����ģ�鹦�ܽ�ʧЧ��</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?> - ��������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��ʾ����</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="45%"><strong>���˿ռ�Ĳ˵���:</strong>����ǰ̨���˿ռ�ĵ����˵���<span class="font_2">ģ���֧�� 1 �����࣬��ַ��֧�ֲ���<span class="font_1">(uid)</span>�滻�ɷ��ʿռ����ʵuid��</span></td>
                <td width="*">
                <select name="modcfg[space_menuid]">
                    <option value="">==ѡ��˵���==</option>
                    <?=form_menu_main($modcfg['space_menuid'])?>
                </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���˿ռ�Ĭ�Ϸ��:</strong>��Աע��ʱĬ�����õĸ��˿ռ���</td>
                <td><select name="modcfg[templateid]">
                    <option value="0">��(��ʹ�÷��)</option>
                    <?=form_template('space', $modcfg['templateid'])?>
                </select></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>�����οͼ�¼:</strong>�������οͼ�¼���ܣ����˿ռ佫��¼�ο͵�ID��ǰ���Ǹ��ο��ѵ�¼��վ��</td>
                <td width="*"><?=form_bool('modcfg[recordguest]', $modcfg['recordguest'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ռ�Ĭ�ϱ���</strong>���ñ���˵������վ����{sitename}��ע���Ա����{username}</td>
                <td><input type="text" name="modcfg[spacename]" value="<?=$modcfg['spacename']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ռ�Ĭ��˵��</strong>���ñ���ͬ��</td>
                <td><input type="text" name="modcfg[spacedescribe]" value="<?=$modcfg['spacedescribe']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ҳ������ʾ����Ŀ:</strong>�ڸ��˿ռ���ҳ�У���ʾ�ĵ�����Ŀ</td>
                <td><?=form_radio('modcfg[index_reviews]',array('5'=>'5��','10'=>'10��','20'=>'20��'),$modcfg['index_reviews'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ҳ������ʾ����Ŀ:</strong>�ڸ��˿ռ���ҳ�У���ʾ��������Ŀ</td>
                <td><?=form_radio('modcfg[index_gbooks]',array('5'=>'5��','10'=>'10��','20'=>'20��'),$modcfg['index_gbooks'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳ����ʾ����Ŀ:</strong>�ڸ��˿ռ������Ŀ�У���ʾ�ĵ�����Ŀ</td>
                <td><?=form_radio('modcfg[reviews]',array('10'=>'10��','20'=>'20��','40'=>'40��'),$modcfg['reviews'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ҳ����ʾ����Ŀ:</strong>�ڸ��˿ռ������Ŀ�У���ʾ��������Ŀ</td>
                <td><?=form_radio('modcfg[gbooks]',array('10'=>'10��','20'=>'20��','40'=>'40��'),$modcfg['gbooks'])?></td>
            </tr>
        </table>
    </div>

    <center>
        <input type="submit" name="dosubmit" value=" �ύ " class="btn" />
    </center>
<?=form_end()?>
</div>