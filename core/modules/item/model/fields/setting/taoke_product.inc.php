<?php
    $_G['loader']->helper('form','item');
?>
<table class="subtable" border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
        <td class="altbg1"><strong>�����ǳ��ֶΣ�</strong>��ȡ���������µ��ƹ��Ʒ</td>
        <td>
            <select name="t_cfg[nick_field]">
                <option value="">==ѡ������ǳ��ֶ�==</option>
				<?=form_item_fields($modelid, $t_cfg['nick_field'], 'text')?>
            </select>
		</td>
    </tr>
    <tr>
        <td class="altbg1"><strong>��Ʒ����ؼ��֣�</strong>�����ֶ��������ߵ�������ؼ���<br />
		<span class="font_1">ע��:�����ǳ����������̹ؼ�������ֻ�ܶ�ѡһʹ�ã����߶����õģ�ʹ�����̱���ؼ���</span></td>
        <td>
			<input type="radio" name="t_cfg[q_type]" value=""<?if(!$t_cfg['q_type'])echo" checked";?> />��ʹ�����̱�������<br />
            <input type="radio" name="t_cfg[q_type]" value="q_field"<?if($t_cfg['q_type']=='q_field')echo" checked";?> />ʹ���ֶ�ֵ��������
			<select name="t_cfg[q_field]">
                <option value="">==ѡ�������ֶ�==</option>
				<?=form_item_fields($modelid, $t_cfg['q_field'], 'text')?>
            </select><br />
			<input type="radio" name="t_cfg[q_type]" value="q_text"<?if($t_cfg['q_type']=='q_text')echo" checked";?> />�ṩ�ı�����д�ؼ���
		</td>
    </tr>
    <tr>
        <td class="altbg1" width="45%"><strong>ѡ����Ʒ��Ŀ��</strong>ѡ����Ʒ������Ŀ������ϡ���Ʒ����ؼ��֡�����</td>
        <td width="*">
            <select name="t_cfg[cid]">
                <option value="">==ѡ����Ʒ��Ŀ==</option>
				<?=taoke_item_root_cats($t_cfg['cid'])?>
            </select>
        </td>
    </tr>
    <tr>
        <td class="altbg1"><strong>��ʾ������</strong>��ȡ���̵�������Ĭ��Ϊ10��</td>
        <td><input type="text" class="txtbox4" name="t_cfg[num]" value="<?=$t_cfg['num']>0?$t_cfg['num']:10?>" /></td>
    </tr>
</table>
<?php
function taoke_item_root_cats($select='') {
    global $_G, $MOD;
	
    $TB =& $_G['loader']->lib('taobao');
    $TB->set_appkey($MOD['taoke_appkey'], $MOD['taoke_appsecret'], $MOD['taoke_sessionkey']);
    $TaobaokeData = $TB->set_method('taobao.itemcats.get')
        ->set_param('fields','cid,parent_cid,name')
        ->set_param('parent_cid', '0')
        ->get_data();

    $content = '';
    if($TaobaokeData['item_cats']['item_cat']) {
        $content = '';
        foreach($TaobaokeData['item_cats']['item_cat'] as $v) {
            $selected = $v['cid'] == $select ? ' selected' : '';
            $content .= "\t<option value=\"{$v['cid']}\"$selected>$v[name]</option>\r\n";
        }
    }

    return $content;
}
?>