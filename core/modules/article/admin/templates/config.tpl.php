<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">����й�������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>Meta Keywords:</strong>Keywords �������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��Ĺؼ��֣�����ؼ��ּ����ð�Ƕ��� "," ����</td>
                <td><input type="text" name="modcfg[meta_keywords]" value="<?=$modcfg['meta_keywords']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>Meta Description:</strong>Description ������ҳ��ͷ���� Meta ��ǩ�У����ڼ�¼��ҳ��ĸ�Ҫ��������</td>
                <td><input type="text" name="modcfg[meta_description]" value="<?=$modcfg['meta_description']?>" class="txtbox" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ʱ��������Զ��ͼƬ:</strong>ǰ��̨�û��ڷ�������ʱ����̨�Զ��ԷǱ�վ��ͼƬ����Զ�����ء�<br /><span class="font_1">���鲻�ڷ�������ʱ����ͼƬ����Ϊ�˹��ܻ�Ӱ�����µķ����ٶȣ������ٶȺ�ͼƬ������Ӱ��ͼƬ����ʱ�䣬���������޷��������µ����⣩</font></td>
                <td>
                    <div>ǰ̨��<?=form_bool('modcfg[dwon_image_bf]', $modcfg['dwon_image_bf'])?></div>
                    <div>��̨��<?=form_bool('modcfg[dwon_image_cp]', $modcfg['dwon_image_cp'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>��������RSS�ۺϷ���:</strong>��վ�����ṩRSS�ۺϷ���</td>
                <td width="*"><?=form_bool('modcfg[rss]', $modcfg['rss'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ͼƬ�ϴ�����:</strong>ǰ̨�û���������ʱ������ʹ�ñ༭����ͼƬ�ϴ����ܡ�</td>
                <td><?=form_bool('modcfg[editor_image]', $modcfg['editor_image'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������ݹ��˹���:</strong>�Է������������ݽ��д�����ˡ�<br />
                <span class="font_1">���˴ʿ����� ��վ����=&gt;������˹��� �н������á�</span></td>
                <td><?=form_bool('modcfg[post_filter]', $modcfg['post_filter'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���÷���������֤�빦��:</strong>ǰ̨�û��ڷ�������ʱ����Ҫ��д��֤��</td>
                <td><?=form_bool('modcfg[post_seccode]', $modcfg['post_seccode'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����������˹���:</strong>��ǰ̨�û��������������Ա�����������½��к�̨��ˣ�ֻ�о�����˵����²�����ǰ̨��ʾ��</td>
                <td><?=form_bool('modcfg[post_check]', $modcfg['post_check'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������Ա����������Ѷ:</strong>���ñ����ܺ��������Ա�������ˣ��Ϳ��Է�������������Ѷ��Ϣ������������ҳ����ʾ�������ݡ�<br /><span class="font_1">��Ա�������µ�Ȩ������ ��Ա����=>�û���=>Ȩ�޹��� �н������á�</span></td>
                <td><?=form_bool('modcfg[owner_post]', $modcfg['owner_post'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>ָ���������Ա��������Ѷ����:</strong>���������Ա��������Ѷ��ָ��1����������⣬���������´���ѡ��</td>
                <td>
                    <select name="modcfg[owner_category]">
                        <option value="0">û������</option>
                        <?=form_article_category()?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ָ����վ��ͨ��Ա��������Ѷ����:</strong>����վ��ͨ��Ա��������Ѷ��ָ��1����������⣬���������´���ѡ��</td>
                <td>
                    <select name="modcfg[member_category]">
                        <option value="0">û������</option>
                        <?=form_article_category()?>
                    </select>
                </td>
            </tr>
            -->
            <tr>
                <td class="altbg1"><strong>������ͨ��Ա��������:</strong>���ñ����ܺ���ͨ��Ա�ڷ�������ʱ�������������¹���Ĺ������⡣</td>
                <td><?=form_bool('modcfg[member_bysubject]', $modcfg['member_bysubject'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������۹���:</strong>��Ա���Զ����½��У���Ҫ��װ����ģ�飩</td>
                <td><?=form_bool('modcfg[post_comment]',$modcfg['post_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����Զ�����������:</strong>��ʽ��1|�ȵ��Ƽ���˵����1��ʾatt��ֵ��|����Ϊattֵ��˵����ÿ��һ����att��ֵ�����ظ���</td>
                <td>
                    <textarea name="modcfg[att_custom]" style="width:500px;height:100px;"><?=$modcfg['att_custom']?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������ַ����ƣ�</strong>�����������ݷ����ƣ�Ĭ��10-20000</td>
                <td><input type="text" name="modcfg[content_min]" value="<?=$modcfg['content_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[content_max]" value="<?=$modcfg['content_max']?>" class="txtbox5" /></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>��������ҳÿҳ�ַ�������</strong>������������ÿҳ��������ַ��������޶��ַ�����������з�ҳ���粻ѡ���ҳ�������ջ���дΪ0��Ĭ��ÿҳ����Ϊ1000�ַ���PS�������ַ�������HTML���룬��˽�������ֵ����1000</td>
                <td><input type="text" name="modcfg[page_word]" value="<?=$modcfg['page_word']?>" class="txtbox4" /></td>
            </tr>
            -->
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>�б�ҳ��ʾ����:</strong>���������б�ҳ�����µ���ʾ������Ĭ��Ϊ10����</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('10'=>'10��','20'=>'20��','40'=>'40��'),($modcfg['list_num']>0?$modcfg['list_num']:10))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����ѹ������������ʹ��������:</strong>���Ѿ���������������ţ������б���������ݣ��������������õ�������</td>
                <td><?=form_bool('modcfg[use_itemtpl]', $modcfg['use_itemtpl'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td class="altbg1" width="45%"><strong>����ǰ̨��ԱͶ��ʱѡ�����:</strong>ǰ̨��Ա��Ͷ��ʱ����ѡ�����������أ���ֹ��ǿ��ָ��Ϊ��ǰ���ڷ��ʷ�վ���С�</td>
                <td width="*"><?=form_bool('modcfg[select_city]', $modcfg['select_city']);?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>