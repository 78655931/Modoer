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
                <td class="altbg1" valign="top" width="45%"><strong>������Ʒ������֤:</strong>������Ʒʱ��������д��֤�� </td>
                <td width="*"><?=form_bool('modcfg[seccode_product]', $modcfg['seccode_subject'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ò�Ʒ�������:</strong>������Ʒ�����ͨ����̨���</td>
                <td><?=form_bool('modcfg[check_product]',$modcfg['check_product']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ò�Ʒ���������ۣ�����:</strong>��Ա���ԶԲ�Ʒ���е�������Ҫ��װ����ģ�飩</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>ͼƬ�ߴ�:</strong>�ϴ����������ͼƬʱ�����������ߴ磬��ʽΪ���� x �ߣ�Ĭ�ϣ�200 x 150</td>
                <td width="*"><input type="text" name="modcfg[thumb_width]" value="<?=$modcfg['thumb_width']?>" class="txtbox5" />&nbsp;x&nbsp;<input type="text" name="modcfg[thumb_height]" value="<?=$modcfg['thumb_height']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����ѹ�������Ĳ�Ʒʹ��������:</strong>���Ѿ�����������Ĳ�Ʒ���б������ݣ��������������õ�������</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>�����������Ա�����Ʒ����:</strong>�������Ա���Լ�����Ĳ�Ʒ������</td>
                <td><?=form_bool('modcfg[manage_comment]',$modcfg['manage_comment']);?></td>
            </tr>
            -->
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>