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
                <td class="altbg1" width="45%"><strong>ģ����ҳ����:</strong>&lt;title&gt;����ʾ�ı���</td>
                <td width="*"><input type="text" name="modcfg[title]" value="<?=$modcfg['title']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ����</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ������</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>���ⷢ�����:</strong>ǰ̨�û���������ʱ����Ҫ������̨��ˡ�</td>
                <td width="*"><?=form_bool('modcfg[topic_check]',$modcfg['topic_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����ظ����:</strong>ǰ̨�û��ڻظ�����ʱ����Ҫ������̨��ˡ�</td>
                <td width="*"><?=form_bool('modcfg[reply_check]',$modcfg['reply_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>���ⷢ����֤��:</strong>ǰ̨�û���������ʱ����Ҫ��д����֤�롣</td>
                <td width="*"><?=form_bool('modcfg[topic_seccode]',$modcfg['topic_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����ظ���֤��:</strong>ǰ̨�û��ڻظ�����ʱ����Ҫ��д��֤�롣</td>
                <td width="*"><?=form_bool('modcfg[reply_seccode]',$modcfg['reply_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>����������������:</strong>����ǰ̨�������ۻ���ʱ�������������ơ�</td>
                <td width="*">
                    <input type="text" name="modcfg[topic_content_min]" value="<?=$modcfg['topic_content_min']>0?$modcfg['topic_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[topic_content_max]" value="<?=$modcfg['topic_content_max']>0?$modcfg['topic_content_max']:5000?>" class="txtbox5" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ӧ������������:</strong>����ǰ̨���ڻ�Ӧ����ʱ�������������ơ�</td>
                <td>
                    <input type="text" name="modcfg[reply_content_min]" value="<?=$modcfg['reply_content_min']>0?$modcfg['reply_content_min']:10?>" class="txtbox5" /> -
                    <input type="text" name="modcfg[reply_content_max]" value="<?=$modcfg['reply_content_max']>0?$modcfg['reply_content_max']:1000?>" class="txtbox5" />
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>