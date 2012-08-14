<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function showhide(id) {
    var obj = $('#'+id);
    if(obj.css('display') == 'none') {
        obj.show();
    } else {
        obj.hide();
    }
}

function fieldtype() {
    var obj = $('#t_field_type');
    if(obj.val()=='') return;
    $.post("<?=cpurl($module, 'field_type')?>", 
	{modelid:"<?=$t_field['modelid']?$t_field['modelid']:$_GET['modelid']?>", fieldid:"<?=$_GET['fieldid']?>", fieldtype:obj.val(), in_ajax:1},
        function(data) {
            if(data.length == 0) {
                $('#t_fieldtype_setting_tr').hide();
            } else {
                $('#t_fieldtype_setting').html(data);
                $('#t_fieldtype_setting_tr').show();
            }
        }
    ); 
}

window.onload = function () {
    fieldtype();
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module, $act, $op)?>">
    <div class="space">
        <div class="subtitle">����/�༭�ֶ�</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,'model_list')?>" onfocus="this.blur()">ģ�͹���</a></li>
            <li><a href="<?=cpurl($module,'model_edit','',array('modelid'=>$modelid))?>" onfocus="this.blur()">�༭ģ��</a></li>
            <li><a href="<?=cpurl($module,'field_list','',array('modelid'=>$modelid))?>" onfocus="this.blur()">�Զ����ֶι���</a></li>
            <li class="selected"><a href="#" onfocus="this.blur()">�½��ֶ�</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="1" cellpadding="1">
            <tr>
                <td class="altbg1" width="45%"><strong>�ֶ����ƣ�</strong>�������������ģ��ʱ��ͨ���������ȡ���ֶε�ֵ����Ӣ��Сд��ĸ�����ֺ��»�����ɣ�ϵͳ���Զ����ֶλ�Ĭ������ǰ׺"c_���Է�ֹ��ϵͳ�����ݿ�ؼ���������<span class="font_1">������д���޷��ٴθĶ�</span></td>
                <td width="*">
                    <?if($op=='add'):?>c_<?endif;?><input type="text" class="txtbox4" name="t_field[fieldname]" value="<?=$t_field['fieldname']?>"<?=$disabled?> />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶα��⣺</strong>�ֶε����ã����磺����������ַ�����ó�ν��</td>
                <td><input type="text" class="txtbox3" name="t_field[title]" value="<?=$t_field['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶε�λ��</strong>��д�ֶεĵ�λ������۸��ֶ�ʱ����д����Ԫ��</td>
                <td><input type="text" class="txtbox3" name="t_field[unit]" value="<?=$t_field['unit']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶ���ʾģ�壺</strong>�������ֶ���ǰ̨Ĭ����ʾ��Ч�������������������Ч����IM��������ͼ��ȣ��������£�<br />{value}���ֶε�ֵ<br />{urlcode:value}������URL���ܵ��ֶ�ֵ������URL����<br />
				{city_name}����������<br />
				{display:����}:֧��ģ���display�ຯ��(core/modules/[module]/inc/disply.php)</td>
                <td><textarea name="t_field[template]" rows="5" cols="50"><?=_T($t_field['template'])?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶ�˵����</strong>�����ڻ�Ա�Ǽ��µ�������ʱ�����ڽ��͵�ǰ�ֶεĺ��壬һ��������ֶ��������Ҳ�</td>
                <td><textarea name="t_field[note]" rows="5" cols="50"><?=_T($t_field['note'])?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong>�ֶ����ͣ�</strong>��ǰ�ֶε����ͣ�һ�����õ��ֶΣ��������ݿ��ȶ���ǰ��<br />
                    <span class="font_1">������д���޷��ٴθĶ�</span>
                </td>
                <td>
                    <select name="t_field[type]" id="t_field_type" onchange="fieldtype();"<?=$disabled?>>
                        <option value=""<?if(empty($t_field))echo' selected="selected"'?>>==ѡ���ֶ�����==</option>
                        <?foreach($F->fieldtypes as $key => $val) { $exp = explode('|',$val); if($exp[1]=='N' && !$t_field) continue; ?>
                        <option value="<?=$key?>"<?if($t_field['type']==$key||$_GET['fieldtype']==$key)echo' selected="selected"'?>><?=$exp[0]?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr id="t_fieldtype_setting_tr" style="display:none;">
                <td colspan="2"><div id="t_fieldtype_setting"></div></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����У��������ʽ��</strong>���ύ������ʹ��������ʽ����У�顣</td>
                <td><input type="text" class="txtbox2" name="t_field[regular]" value="<?=$t_field['regular']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����У��ʧ����ʾ��Ϣ��</strong>��У�������ʧ�ܣ���ƥ�䣩ʱ����ʾ�û�����Ϣ��</td>
                <td><input type="text" class="txtbox" name="t_field[errmsg]" value="<?=$t_field['errmsg']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����Ϊ�գ�</strong>�����Ա����д��������</td>
                <td><?=form_bool('t_field[allownull]', $t_field['allownull'])?></td>
            </tr>
            <!--
            <tr>
                <td class="altbg1"><strong>����������</strong>�Ƿ��ڸ߼���������Ϊһ�������������������������Զ����ֶν�����ϵͳ��Դ������ʹ����������Ϊ���ֻ�С��50���ַ�����</td>
                <td><?=form_bool('t_field[enablesearch]', $t_field['enablesearch'])?></td>
            </tr>
            -->
            <tr>
                <td class="altbg1"><strong>���б�ҳ��ʾ��</strong>���б�ҳ��ʾ���ֶ���Ϣ�����¼�������б�ҳ��ʾ�������ݺ͹�����ֶ����ݣ��磺�ı������ֶΣ�����������ֶλ�Ӱ��ϵͳЧ�ʡ�</td>
                <td><?=form_bool('t_field[show_list]', $t_field['show_list'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������ҳ����ʾ��</strong>����������ҳ����Ϣ�������ʾ���ֶ���Ϣ������������ֶΣ������ı����ʺ���ʾ�ڱ����ֶΣ��������б༭ģ�壬�벻Ҫ�������ֶ���Ϣ��ʾ��</td>
                <td><?=form_bool('t_field[show_detail]', $t_field['show_detail'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ڲ������ʾ��</strong>�ڹ��������ģ������ҳ��ʾ������Ϣ�����磺��Ʒ����ҳ���Ż�ȯ����ҳ�ȣ�����������ֶΣ������ı����ʺ���ʾ�ڱ����ֶΣ��������б༭ģ�壬�벻Ҫ�������ֶ���Ϣ��ʾ��</td>
                <td><?=form_bool('t_field[show_side]', $t_field['show_side'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶα༭Ȩ�ޣ�</strong>���õ�ǰ�ֶ�����ӱ༭Ȩ�ޣ�<br /><span class="font_1">ע:����ѡ����һ�����ͣ���̨�û����ɱ༭�����ֶ�.</span></td>
                <td><?=form_radio('t_field[isadminfield]', array(
                    '0'=>'ǰ̨��ͨ�ֶ�<span class="font_2">(ǰ̨��ͨ�û���ӱ༭ʱ�ɼ�)</span>',
                    '2'=>'�������Ա�ֶ�<span class="font_2">(ֻ���������Ĺ���Ա�༭ʱ�ɼ�)</span>',
                    '1'=>'��̨�ֶ�<span class="font_2">(��ϵͳ��̨�û��ɱ༭)</span>'), 
                    $t_field['isadminfield'], '','<br />')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ֶ�����</strong>�ֶ���ģ�������ֶ��е�����λ�á�</td>
                <td><input type="text" class="txtbox4" name="t_field[listorder]" value="<?=$t_field['listorder']?$t_field['listorder']:0?>" /></td>
            </tr>
        </table>
        <center>
            <?if(!$isedit){?>
            <input type="hidden" name="modelid" value="<?=$_GET['modelid']?>" />
            <input type="hidden" name="t_field[tablename]" value="<?=$t_model['tablename']?>" />
            <?} else {?>
            <input type="hidden" name="isedit" value="yes" />
            <input type="hidden" name="modelid" value="<?=$t_field['modelid']?>" />
            <input type="hidden" name="fieldid" value="<?=$_GET['fieldid']?>" />
            <?}?>
            <button type="submit" name="dosubmit" value="yes" class="btn" /><?=lang('global_submit')?></button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);" /><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>