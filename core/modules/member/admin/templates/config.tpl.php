<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">ע�����¼</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">��������վ�˺�����</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1"><strong>�����Ա�����۵Ļ�������:</strong>���������Ա�����ۻ�������</td>
                <td>
                    <select name="modcfg[sellgroup_pointtype]">
                        <option value="">ѡ���������</option>
                        <?=form_member_pointgroup($modcfg['sellgroup_pointtype'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><div style="margin-left:20px;"><strong>�����Ա��ʹ������:</strong>���õ��ι����ʹ�����ޣ�Ĭ��Ϊ 30 ��</td>
                <td width="*">
                    <input type="text" name="modcfg[sellgroup_useday]" value="<?=$modcfg['sellgroup_useday']>0?$modcfg['sellgroup_useday']:30?>" class="txtbox4" />&nbsp;��
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������ԱFeed�¼�:</strong>���������ܺ�ϵͳ����ʱ��¼��Ա����Ҫ������Ϣ������ǰ̨��ʾ</td>
                <td><?=form_bool('modcfg[feed_enable]',$modcfg['feed_enable'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>������֤��:</strong>������֤�룬��ֹ����ע�� </td>
                <td width="*">
                    <div>ע��:<?=form_bool('modcfg[seccode_reg]', $modcfg['seccode_reg'])?></div>
                    <div>��¼:<?=form_bool('modcfg[seccode_login]', $modcfg['seccode_login'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������ͬIP����ע��:</strong>����һ��ʱ������ͬ��IP����ע���ʺţ�0��ʾ������</td>
                <td><?=form_input('modcfg[registered_again]', $modcfg['registered_again']>0?$modcfg['registered_again']:0, 'txtbox4')?>&nbsp;��</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ر����û�ע��:</strong>�����Ƿ�ر��ο�ע���Ϊ��վ��Ա</td>
                <td><?=form_bool('modcfg[closereg]',(bool)$modcfg['closereg'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ע��������֤:</strong>�û�ע��󣬽���ȴ���֤�û��飬ͨ��������֤��������ͨ�û��顣</td>
                <td><?=form_bool('modcfg[email_verify]',(bool)$modcfg['email_verify'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ע���ֻ���֤:</strong>�û�ע��ʱ��Ҫ��֤���ֻ��ţ��������ö��ŷ���ģ�顣</td>
                <td><?=form_bool('modcfg[mobile_verify]',(bool)$modcfg['mobile_verify'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>�ֻ���֤����Ϣ:</strong>���ñ���˵����<br />��վ����$sitename<br />��֤�룺$serial
                </td>
                <td>
                    <textarea name="modcfg[mobile_verify_message]" rows="5" cols="40"><?=$modcfg['mobile_verify_message']?$modcfg['mobile_verify_message']:lang('member_mobile_verify_message')?></textarea>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>�û���Ϣ�����ؼ���:</strong>�û������û���Ϣ(���û������ǳơ��Զ���ͷ�ε�)���޷�ʹ����Щ�ؼ��֡�ÿ���ؼ���һ�У���ʹ��ͨ��� "*" �� "*����*"(��������)</td>
                <td><?=form_textarea('modcfg[censoruser]',$modcfg['censoruser'],5,50)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ͬһ&nbsp;Email&nbsp;ע�᲻ͬ�û�:</strong>ѡ�񡰷񡱽�ֻ����һ�� Email ��ַֻ��ע��һ���û���</td>
                <td><?=form_bool('modcfg[existsemailreg]',(bool)$modcfg['existsemailreg'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>��ʾ���Э��:</strong>���û�ע��ʱ��ʾ���Э��
                </td>
                <td>
                    <input type="radio" name="modcfg[showregrule]" value="1"<?if($modcfg['showregrule'])echo' checked';?> onclick="document.getElementById('tr_regrule').style.display='';" /> ��&nbsp;&nbsp;<input type="radio" name="modcfg[showregrule]" value="0"<?if(!$modcfg['showregrule'])echo' checked';?> onclick="document.getElementById('tr_regrule').style.display='none';" />��
                </td>
            </tr>
            <tr id="tr_regrule"<?if(!$modcfg['showregrule'])echo' style="display:none;"';?> valign="top">
                <td class="altbg1"><strong>���Э������:</strong>���û�ע��ʱ��ʾ���Э��</td>
                <td><textarea name="modcfg[regrule]" rows="5" cols="40"><?=$modcfg['regrule']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>���ͻ�ӭ��Ϣ:</strong>��ѡ���Ƿ��Զ�����ע���û�����һ����ӭ��Ϣ</td>
                <td>
                    <input type="radio" name="modcfg[salutatory]" value="0"<?if(empty($modcfg['salutatory']))echo' checked';?> onclick="document.getElementById('tr_salutatory_msg').style.display='none';" /> ������&nbsp;
                    <input type="radio" name="modcfg[salutatory]" value="1"<?if($modcfg['salutatory'])echo' checked';?> onclick="document.getElementById('tr_salutatory_msg').style.display='';" /> ���ͻ�ӭ����Ϣ
                </td>
            </tr>
            <tr id="tr_salutatory_msg"<?if(empty($modcfg['salutatory']))echo' style="display:none;"';?>>
                <td class="altbg1" valign="top">
                    <strong>��ӭ��Ϣ����:</strong>���ñ���˵����<br />��վ����$sitename<br />ע���Ա����$username<br />��ǰʱ�䣺$time
                </td>
                <td><?=form_textarea('modcfg[salutatory_msg]',$modcfg['salutatory_msg'],8,40)?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>����������վ�ʺŵ�¼:</strong>ͨ��Modoer����������վ�ʺ�API���ܣ�ʵ�ֶ��ʺ����ӵ�¼���ܣ��򿪱����ܺ������������ʺ�</td>
                <td width="*">
                    <?=form_bool('modcfg[passport_login]', $modcfg['passport_login'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>�״�ͨ���������ʺŵ�¼ע��ʱ��Ҫ���ڱ�վ��������</strong>���û���ʹ�õ������ʺŵ�¼ʱ����Ҫ�ڱ�����վ���������룬�Ա��ʺſ�������������ʺŽ��е�¼</td>
                <td width="*">
                    <?=form_bool('modcfg[passport_pw]', $modcfg['passport_pw'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top" width="45%"><strong>��Ҫʹ�õĵ�������վ�ʺ�:</strong>�����ڱ�վ��¼�ĵ�������վ�ʺš�</td>
                <td width="*">
                    <?=form_check('modcfg[passport_list][]', array('weibo'=>'����΢��','qq'=>'��ѶQQ','taobao'=>'�Ա���'), $modcfg['passport_list'],'','&nbsp;')?>
                </td>
            </tr>
            <input type="hidden" name="modcfg[passport_weibo_name]" value="weibo">
            <input type="hidden" name="modcfg[passport_qq_name]" value="qq">
			<input type="hidden" name="modcfg[passport_taobao_name]" value="taobao">
            <tr class="altbg2"><td colspan="2"><center><b>����΢��</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>����΢��ǰ̨��ʾ����:</strong>����վǰ̨��ʾ������</td>
                <td>
                    <?=form_input('modcfg[passport_weibo_title]', $modcfg['passport_weibo_title']?$modcfg['passport_weibo_title']:'΢���ʺ�', 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����΢�� App Key:</strong>�� http://open.weibo.com ��¼�����ȡ</td>
                <td>
                    <?=form_input('modcfg[passport_weibo_appkey]', $modcfg['passport_weibo_appkey'], 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����΢�� App Secret:</strong>��ȡλ��ͬ��</td>
                <td>
                    <?=form_input('modcfg[passport_weibo_appsecret]', $modcfg['passport_weibo_appsecret'], 'txtbox2')?>
                </td>
            </tr>
            <tr class="altbg2"><td colspan="2"><center><b>��ѶQQ</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>��ѶQQǰ̨��ʾ����:</strong>����վǰ̨��ʾ������</td>
                <td>
                    <?=form_input('modcfg[passport_qq_title]', $modcfg['passport_qq_title']?$modcfg['passport_qq_title']:'QQ�ʺ�', 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ѷ΢�� App ID</strong>�� http://connect.qq.com/ ��¼�����ȡ</td>
                <td>
                    <?=form_input('modcfg[passport_qq_appid]', $modcfg['passport_qq_appid'], 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ѷ΢�� App KEY:</strong>��ȡλ��ͬ��</td>
                <td>
                    <?=form_input('modcfg[passport_qq_appkey]', $modcfg['passport_qq_appkey'], 'txtbox2')?>
                </td>
            </tr>
			<tr class="altbg2"><td colspan="2"><center><b>�Ա���</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>�Ա���ǰ̨��ʾ����:</strong>����վǰ̨��ʾ������</td>
                <td>
                    <?=form_input('modcfg[passport_taobao_title]', $modcfg['passport_taobao_title']?$modcfg['passport_taobao_title']:'�Ա����˺�', 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ա��� App Key:</strong>�� http://open.taobao.com ��¼�����ȡ</td>
                <td>
                    <?=form_input('modcfg[passport_taobao_appkey]', $modcfg['passport_taobao_appkey'], 'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�Ա��� App Secret:</strong>��ȡλ��ͬ��</td>
                <td>
                    <?=form_input('modcfg[passport_taobao_appsecret]', $modcfg['passport_taobao_appsecret'], 'txtbox2')?>
                </td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>