<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">��������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>�ر�ȫ������:</strong>һ���Թر��������ۣ��������������������ʱ�ڡ�</td>
                <td><?=form_bool('modcfg[disable_comment]',$modcfg['disable_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ر�������ʾ������:</strong>�ر�������ʾ��������Ϣ��</td>
                <td><?=form_bool('modcfg[hidden_comment]',$modcfg['hidden_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ƿ����ο�����:</strong>�����οͽ��е���</td>
                <td><?=form_bool('modcfg[guest_comment]',$modcfg['guest_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������:</strong>�ύ������ֻ�о�����̨��˲�����ʾ����վǰ̨��</td>
                <td><?=form_bool('modcfg[check_comment]',$modcfg['check_comment']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ʹ�ùؼ��ʹ���:</strong>���˵Ĺؼ��ʿ����λ���� ��վ����=&gt;������˹���</td>
                <td><?=form_bool('modcfg[filter_word]',$modcfg['filter_word']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ҳ����ʱ����:</strong>���õ�ҳ�ڶ������֮���ʱ������Ĭ��Ϊ 10 �룬���ܵ��� 5 �롣</td>
                <td><input name="modcfg[comment_interval]" value="<?=$modcfg['comment_interval']?$modcfg['comment_interval']:10?>" class="txtbox5" />&nbsp;��</td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>����������ʾ��֤��:</strong>��������ʱ��������д��֤�� </td>
                <td width="*">
                    <div>��Ա:<?=form_bool('modcfg[member_seccode]', $modcfg['member_seccode'])?></div>
                    <div>�ο�:<?=form_bool('modcfg[guest_seccode]', $modcfg['guest_seccode'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������������� </strong>�����������ݵ��ַ�����</td>
                <td>
                    <input type="text" name="modcfg[content_min]" value="<?=$modcfg['content_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[content_max]" value="<?=$modcfg['content_max']?>" class="txtbox5" />
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>���۵�ҳ��ʾ����:</strong>����ҳ���������ʾ�������</td>
                <td width="*"><?=form_radio('modcfg[list_num]',array('5'=>'5��','10'=>'10��','20'=>'20��','40'=>'40��','50'=>'50��'),$modcfg['list_num'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������ʽ:</strong>����������ʾ˳��</td>
                <td><?=form_radio('modcfg[listorder]',array('asc'=>'����������ǰ','desc'=>'����������ǰ'),$modcfg['addtime']?$modcfg['addtime']:'asc')?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>