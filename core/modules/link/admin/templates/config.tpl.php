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
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ����</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ������</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>ͼƬ��������:</strong>����ҳ��ʾͼƬ���ӵ�����</td>
                <td width="*"><input type="text" name="modcfg[num_logo]" class="txtbox4" value="<?=$modcfg['num_logo']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������������:</strong>����ҳ��ʾ�������ӵ�����</td>
                <td><input type="text" name="modcfg[num_char]" class="txtbox4" value="<?=$modcfg['num_char']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������������:</strong>�Ƿ������ο��ύ������������</td>
                <td><?=form_bool("modcfg[open_apply]",$modcfg['open_apply']);?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>