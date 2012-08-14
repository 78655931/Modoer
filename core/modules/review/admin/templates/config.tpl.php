<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle"><?=$MOD['name']?>&nbsp;ģ������</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">��������</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">��ʾ����</a></li>
            <li id="btn_config3"><a href="#" onclick="tabSelect(3,'config');" onfocus="this.blur()">������������</a></li>
            <li id="btn_config4"><a href="#" onclick="tabSelect(4,'config');" onfocus="this.blur()">������������</a></li>
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
                <td width="45%" class="altbg1" valign="top"><strong>����֤��:</strong>������֤��ɼ��ٹ����ύ��Ϣ������Ҳ���û�Ա�е�����</td>
                <td>
                    <div>��������(��Ա):<?=form_bool('modcfg[seccode_review]', $modcfg['seccode_review'])?></div>
                    <div>��������(�ο�):<?=form_bool('modcfg[seccode_review_guest]', $modcfg['seccode_review_guest'])?></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������������� </strong>����������ݵ��ַ�����</td>
                <td><input type="text" name="modcfg[review_min]" value="<?=$modcfg['review_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[review_max]" value="<?=$modcfg['review_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��Ӧ������������</strong>�����Ӧ�����ַ�����</td>
                <td><input type="text" name="modcfg[respond_min]" value="<?=$modcfg['respond_min']?>" class="txtbox5" /> - <input type="text" name="modcfg[respond_max]" value="<?=$modcfg['respond_max']?>" class="txtbox5" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��˻�Ӧ����:</strong>������˹��ܺ�δ��˵���Ϣ����ʱ����ǰ̨��ʾ�Ͳ�����</td>
                <td>
                    <?=form_bool('modcfg[respondcheck]', $modcfg['respondcheck'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>���÷�Ĭ��ͷ�����</strong>�û���������һ����Ĭ��ͷ�����ܽ��е���
                </td>
                <td><?=form_bool('modcfg[avatar_review]', $modcfg['avatar_review'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>���ݿո��ǩ�ָ���</strong>����1.x��ʹ�ÿո������ţ����������ʹ�ÿո���ʵ�ַָ���ǩ. ע��:�ո���ж�Ӣ�Ķ���ı�ǩ��
                </td>
                <td><?=form_bool('modcfg[tag_split_sp]', $modcfg['tag_split_sp'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>����Ĭ����</strong>���õ����������������Ĭ��ѡ�����
                </td>
                <td>
                    <select name="modcfg[default_grade]">
                        <option value="0">����</option>
                        <?foreach (lang('review_grade_array') as $key => $value):?>
                        <option value="<?=$key?>"<?=($modcfg['default_grade']==$key?' selected="selected"':'') ?>><?=$value?>[<?=$key?>��]</option>
                        <?endforeach;?>
                    </select>
                </td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <tr>
               <td width="45%" class="altbg1"><strong>��Ӧ��ʾ��:</strong>��Ӧ��ÿҳ��ʾ��Ӧ��Ŀ</td>
                <td><?=form_radio('modcfg[respond_num]',array('5'=>'5��','10'=>'10��','20'=>'20��'),$modcfg['respond_num'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>ģ����ҳ����ò�������:</strong>��ʾ�ڵ���ģ����ҳ������ò��������Ե�������Ĭ�Ϸֱ�1��</td>
                <td><input type="text" name="modcfg[index_pk_rand_num]" value="<?=$modcfg['index_pk_rand_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>ģ����ҳ�����������:</strong>��ʾ�ڵ���ģ����ҳ�������������,Ĭ�Ϸֱ�2��</td>
                <td><input type="text" name="modcfg[index_digst_rand_num]" value="<?=$modcfg['index_digst_rand_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>������ģ����ҳ��ʾ�ķ���:</strong>����1�������6��</td>
                <td><?=form_item_category_main_check('modcfg[index_review_pids][]',$modcfg['index_review_pids']);?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>�Ƿ�������ģ����ҳ��ʾ�ع�̨:</strong>�����Ƿ���ģ����ҳ�ع�̨���������ϣ�</td>
                <td><?=form_bool('modcfg[index_show_bad_review]', $modcfg['index_show_bad_review'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>ģ����ҳ���µ������ع�̨��ʾ����������:</strong>Ĭ�Ϸֱ�5��</td>
                <td><input type="text" name="modcfg[index_review_num]" value="<?=$modcfg['index_review_num']?>" class="txtbox5" /></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>ģ����ҳ���µ������ع�̨���ݻ�ȡ��ʽ:</strong>��ȡ�������ݵķ�ʽ<span class="font_1">�����������϶���û���Ҫʹ�������ȡ���ţ��������Ӱ�����ݿ�Ч��</span></td>
                <td><?=form_radio('modcfg[index_review_gettype]',array('new'=>'�����·�������','rand'=>'�����ȡ'),$modcfg['index_review_gettype'])?></td>
            </tr>
            <tr>
               <td class="altbg1"><strong>������ģ����ҳ�����ʾ�ķ������а�:</strong>����1�������6��</td>
                <td><?=form_item_category_main_check('modcfg[index_top_pids][]',$modcfg['index_top_pids']);?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config3" style="display:none;">
            <tr>
                <td width="45%"  valign="top" class="altbg1"><strong>�ܷ���ʽ��</strong>�б�ҳ����ϸҳ����ʾ����ĸ������������ֵ��ʽ��Ĭ��Ϊ�ٷ��ơ�</td>
                <td width="*"><?=form_select('modcfg[scoretype]',array('100'=>'�ٷ���','10'=>'ʮ����','5'=>'�����'),$modcfg['scoretype'])?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1"><strong>����С���㣺</strong>����÷ֵ���ʾ�Ƿ���ʾС���㡣</td>
                <td><?=form_select('modcfg[decimalpoint]',array('0'=>'����ʾ','1'=>'1λ','2'=>'2λ'),$modcfg['decimalpoint'])?></td>
            </tr>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config4" style="display:none;">
            <tr>
                <td width="45%" class="altbg1"><strong>���������ۼ�:</strong>���þ��������ļ۸��֧���������ͣ����ջ�0��ʾ�����ñ����ܡ�</td>
                <td width="*">
                    <input type="text" name="modcfg[digest_price]" value="<?=$modcfg['digest_price']?>" class="txtbox4" />
                    <select name="modcfg[digest_pointtype]">
                        <?=form_member_pointgroup($modcfg['digest_pointtype'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�����������߻���:</strong>�����û����򾫻�����ʱ������������Ӧ�õ����꣬��д�ٷֱ�0-100������д��0��ʾ���ṩ��</td>
                <td><input type="text" name="modcfg[digest_gain]" value="<?=$modcfg['digest_gain']?>" class="txtbox4" />%</td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>