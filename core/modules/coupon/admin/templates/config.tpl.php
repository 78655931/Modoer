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
                <td class="altbg1" width="45%"><strong>���ú�̨���:</strong>����Աͨ����̨��ǰ̨�������Ż�ȯ������ˡ�</td>
                <td width="*"><?=form_bool('modcfg[check]',$modcfg['check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����̼ҷ���:</strong>�����Ż�ȯֻ����ӵ�����̵Ĺ���Ա��������������<br /><span class="font_1">���������ú󣬻�Ա���ڵ����Ȩ�޽�ʧЧ��</span></td>
                <td><?=form_bool('modcfg[post_item_owner]',$modcfg['post_item_owner'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ż�ȯԭͼˮӡ:</strong>�Ƿ����Ż�ȯ�ϼ���ˮӡ��ע�⣺����ˮӡ���Ż�ȯ����ʧЧ��</td>
                <td><?=form_bool('modcfg[watermark]',$modcfg['watermark'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ż�ȯԭͼ�Ƿ���вü�:</strong>�Ƿ�������ϴ����Ż�ȯԭͼ����ͼƬ��ü�����ϵͳĬ�ϵ����ߴ�ü���</td>
                <td><?=form_bool('modcfg[autosize]',$modcfg['autosize'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ż�ȯ����ͼ��Ⱥ͸߶�:</strong>�Ż�ȯ�ϴ����Զ���ȡ��С����� x �߶ȣ����飨Ĭ�ϣ�Ϊ160 x 100 px</td>
                <td><input type="text" name="modcfg[thumb_width]" value="<?=$modcfg['thumb_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[thumb_height]" value="<?=$modcfg['thumb_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ǰ̨�����Ż�ȯ������֤��:</strong>ǰ̨���������Ż�ȯʱ������������֤�롣</td>
                <td><?=form_bool('modcfg[seccode]',$modcfg['seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����Ż�ȯ���۹���:</strong>��Ա���Զ��Ż�ȯ���У���Ҫ��װ����ģ�飩</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ż�ȯÿҳ��ʾ����:</strong>Ĭ��ÿҳ��ʾ5�������ձ�ʾĬ�ϡ�</td>
                <td><input type="text" name="modcfg[listnum]" value="<?=$modcfg['listnum']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ż�ȯ������֤:</strong>�Ż�ȯ������֤˵�����Ա��ⲻ��Ҫ�ķ�������</td>
                <td><input type="text" name="modcfg[des]" value="<?=$modcfg['des']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����ѹ���������Ż�ȯʹ��������:</strong>���Ѿ�������������Ż�ȯ���б������ݣ��������������õ�������</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
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