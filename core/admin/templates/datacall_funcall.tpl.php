<?php 
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
if($_GET['cy_callid']) {
    $copy=1;
    $datacall = $D->read($_GET['cy_callid']);
    $datacall['expression']['params'] = '';
    if(is_array($datacall['expression'])) {
        foreach($datacall['expression'] as $key => $val) {
            if(empty($val)) continue;
            if(in_array($key, array('cachetime','row','order'))) continue;
            $datacall['expression']['params'] .= $split . $key . '=' . $val;
            $split = "\r\n";
        }
    }
    $datacall['name'] .= '_copy';
}
?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act,$op)?>">
    <input type="hidden" name="datacall[calltype]" value="fun" />
    <div class="space">
        <div class="subtitle">���Ӻ�������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>��������:</strong>�����ε��ø���һ�����ƣ�����ʱ������ģ���ǩ��ֱ��ʹ�õ������ƣ����ʹ�����ƣ��벻Ҫ�ٴ��޸����ƣ�������Ҳ���������Ϣ��</td>
                <td width="*">
                    <input type="text" name="datacall[name]" class="txtbox2" value="<?=$datacall['name']?>" />
                    <br /><span class="font_2">ʹ�����Ƶ��ã��벻Ҫ�޸�����</span>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ģ��:</strong>���ε��õĺ���������ģ�飬����ģ�鱻���û�ɾ��ʱ��������ε��á�</td>
                <td>
                    <select name="datacall[module]">
                        <option value="">==ѡ��ģ��==</option>
                        <?foreach($_G['modules'] as $key => $val) {?>
                            <option value="<?=$key?>"<?=$datacall['module']==$key?' selected':''?>><?=$val['name']?></option>
                        <?}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ú���:</strong>ͨ���������ȡ�õ��õ����ݣ������ԡ�query_��Ϊǰ׺������д������ʱ������Ҫ��д��query_�������磺����Ϊ��query_items������˴���дΪ��items����</td>
                <td><input type="text" name="datacall[fun]" class="txtbox2" value="<?=$datacall['fun']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���÷��ص����ݱ�ǩ��:</strong>���õ����ݲ�ѯ�󽫴��ڵı�ǩ�������ǩ��������ʾ���ݵ����ݡ����磺��ǩ��Ϊ��hot������ģ���ǩʹ��Ϊ��$_QUERY['hot']</td>
                <td><input type="text" name="datacall[var]" class="txtbox2" value="<?=$datacall['var']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����ʱ��:</strong>���õ����ݵĻ���ʱ�䣬����ʱ�䵽�ں󣬽��ٴβ�ѯ���ݿ⣻��С��ֵ��������ݿ����Ӱ�죬0Ϊ��ʹ�û��棬����������������Ϊ0���⽫����Ӱ�������Ч�ʣ���������ݵ����ͣ��趨һ���ʵ���ֵ��</td>
                <td><input type="text" name="datacall[cachetime]" class="txtbox4" value="<?=$datacall['cachetime']?>" />&nbsp;��</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong>�����ݿ��ȡ����������</td>
                <td><input type="text" name="datacall[expression][row]" class="txtbox4" value="<?=$datacall['expression']['row']?>" />&nbsp;��</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong>���ݲ�ѯ����ĳ���ֶ���Ϊ���������<br />�����Ҫ���������������ֶκ����" DESC"�����������ţ��ұ����д�����磺reviews DESC</td>
                <td><input type="text" name="datacall[expression][order]" class="txtbox2" value="<?=$datacall['expression']['order']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ģ���ļ�:</strong>������ʾ���ݵ�ģ�壬����(ϵͳ)���ÿ�ֱ���ڵ��ô����ģ�壬�����JS��ʽ�����������д�����ݵ���ģ�������� ./templates/datacall Ŀ¼�¡�</td>
                <td>
                    <select name="datacall[tplname]" id="tplname">
                        <?=form_datacall_template_files($_G['cfg']['templateid'],$datacall['tplname'])?>
                    </select>
                    <input type="button" class="btn2" style="margin-left:5px;" value="����ģ��" onclick="window.open('<?=cpurl('modoer','template','manage',array('type'=>'datacall','templateid'=>$_G['cfg']['templateid']))?>')" />
                    <input type="button" class="btn2" value="�����б�" onclick="update_tpllist();" />
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������ģ���ļ�:</strong>�����ε��ò�ѯ������Ϊ��ʱ�����õ�ģ���ļ������ݵ���ģ��������� ./templates/datacall Ŀ¼�¡�</td>
                <td>
                    <select name="datacall[empty_tplname]" id="empty_tplname">
                        <?=form_datacall_template_files($_G['cfg']['templateid'],$datacall['empty_tplname'])?>
                    </select>
                    &nbsp;����ͬ��
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���ò�������:</strong>��ʽ��������=����ֵ��ÿ������Ϊһ��<br />����Ŀ��ò��������ݵ��ö��������<br />���õĿ��ò��������<a href="http://www.modoer.com/" target="_blank">Modoer�����̳�</a>��</td>
                <td><textarea name="datacall[expression][params]" rows="5" cols="50"><?=$datacall['expression']['params']?></textarea></td>
            </tr>
        </table>
        <center>
            <?if($op=='edit') {?>
            <input type="hidden" name="callid" value="<?=$_GET['callid']?>" />
            <button type="submit" name="dosubmit" value="yes" class="btn" />���µ���</button>&nbsp;
            <?} elseif($op=='add') {?>
            <button type="submit" name="dosubmit" value="yes" class="btn" />��ӵ���</button>&nbsp;
            <?}?>
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <button type="reset" class="btn" />��������</button>&nbsp;
            <button type="button" onclick="history.go(-1);" class="btn" />����</button>
        </center>
    </div>
</form>
</div>
<script type="text/javascript">
loadscript('mdialog');
function update_tpllist() {
	$.post("<?=cpurl($module,'datacall','tplist',array('in_ajax'=>1))?>", 
    { }, 
	function(data) {
		if(data) {
			var t1 = $('#tplname').val();
            var t2 = $('#empty_tplname').val();
            $('#tplname').empty().append(data).val(t1);
            $('#empty_tplname').empty().append(data).val(t2);
		} else {
			alert('û�п���ģ�壡');
		}
	});
}
</script>