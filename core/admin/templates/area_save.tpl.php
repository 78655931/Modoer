<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<script type="text/javascript">
loadscript('mdialog');
var maptext = '';
var point1 = point2 = '';
function map_mark(id, p1, p2) {
	maptext = id;
	point1 = p1;
	point2 = p2;
	var url = Url('modoer/index/act/map/width/450/height/300/p1/'+p1+'/p2/'+p2);
	if(point1 != '' && point2 != '') {
		url += '&show=yes';
	}
	var html = '<iframe src="' + url + '" frameborder="0" scrolling="no" width="450" height="310" id="ifupmap_map"></iframe>';
	html += '<button type="button" id="mapbtn1">��ע����</button>&nbsp;';
	html += '<button type="button" id="mapbtn2">ȷ��</button>';
	dlgOpen('ѡ���ͼ�����', html, 470, 390);
	$('#mapbtn1').click(
		function() {
			$(document.getElementById('ifupmap_map').contentWindow.document.body).find('#markbtn').click();
		}
	);
	$('#mapbtn2').click(
		function() {
			point1 = $(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point1').val();
			point2 = $(document.getElementById('ifupmap_map').contentWindow.document.body).find('#point2').val();
			if(point1 == '' || point2 == '') {
				alert('����δ��ɱ�ע��');
				return;
			}
			$('#'+maptext).val(point1 + ',' + point2);
			dlgClose();
		}
	);
}
</script>
<div id="body">
    <div class="space">
        <div class="subtitle">������ʾ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                1. Modoe�콢�氲װ�ּ�Ϊ��1��-���У�2��-��/�ؼ����У�3��-�ֵ�/·��/��ҵ����<br />
                2. ��һ����ӳ��к󣬱����ڡ���������->�������á�ҳ��ѡ��Ĭ�Ϸ��ʳ��У�<br />
        </td></tr>
        </table>
    </div>
    <div class="space">
    <form method="post" action="<?=cpurl($module, $act, $op)?>">
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="aid" value="<?=$aid?>" />
        <?else:?>
        <input type="hidden" name="area[pid]" value="<?=$pid?>" />
        <?endif;?>
        <div class="subtitle"><?=$op=='edit'?'�༭����':'��ӵ���'?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>�������ƣ�</strong>1��:���У�2��:��/�ؼ����У�3��:�ֵ�/·��/��ҵ��</td>
                <td width="*"><input type="text" name="area[name]" value="<?=$detail['name']?>" class="txtbox3" /></td>
            </tr>
            <?if(_get('level')==1 || $detail['level']==1 && $detail['pid']==0):?>
            <tr>
                <td class="altbg1"><strong>��������ĸ��</strong>���õ������Ƶ�һ���ֵ��׸���ĸ</td>
                <td><input type="text" name="area[initial]" value="<?=$detail['initial']?>" class="txtbox5" /></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1"><strong>Ĭ��λ�ã�</strong>��ͼ��עʱʹ�ã�����ȷ����������������λ��</td>
                <td><input type="text" name="area[mappoint]" id="mappoint" value="<?=$detail['mappoint']?>" class="txtbox3" />&nbsp;<button type="button" class="btn2" onclick="map_mark('mappoint','','');">ѡ�������</button></td>
            </tr>
            <?if(_get('level')==1 || $detail['level']==1 && $detail['pid']==0):?>
            <input type="hidden" name="area[level]" value="1">
            <tr><td colspan="2" class="altbg2"><center><b>����й�������</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>���÷�վ��</strong>�����οͽ��뱾���з�վ</td>
                <td><?=form_bool('area[enabled]', isset($detail['enabled'])?$detail['enabled']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��վ�������������Ŀ¼����</strong>���÷�վ�Ķ���������ͨ�������������ʣ�ʵ�ֽ�����з�վ��Ҳ�ǳ���Ŀ¼���ƣ�����Ŀ¼���ܽ������ڿ���URL��д���ܣ�����ģʽΪĿ¼��ʽ��<br /><span class="font_1">ע�⣺�˴���д�����ݲ���Ϊ"index"�Լ�����ģ���ʶ(����:item,review,article��)</span></td>
                <td><?=form_input('area[domain]', $detail['domain'], 'txtbox2')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��ͼAPI�ӿڣ�</strong>����ʹ�õĵ�ͼ�ӿ���Ҫ����key�ģ���ÿ��������������Ҫ����һ��key��<br /><span class="font_2">Ŀǰ�ȸ��ͼv3���ٶ�api��51��ͼ������Ҫkey�����Խ��ں�����������д��ͼapi��ַ���˴������ա�</span></td>
                <td><?=form_input('area[config][mapapikey]', $detail['config']['mapapikey'], 'txtbox2')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��վҳ��ģ����</strong>���õ�ǰ���з�վ����վ�����Ϊ"ͳһ����"�����ɺ��������ھ�����</td>
                <td>
                    <select name="area[templateid]">
                        <option value="0">ͳһ����</option>
                        <?=form_template('main', $detail['templateid'])?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><b>���������Ż�����</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>���⣺</strong>��վ�������⣬����Ϊʹ��ͳһ���á�</td>
                <td><?=form_input('area[config][sitename]', $detail['config']['sitename'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�ؼ��֣�</strong>��վ��ҳ��ҳ��ؼ��� keywords������Ϊʹ��ͳһ���á�</td>
                <td><?=form_input('area[config][meta_keywords]', $detail['config']['meta_keywords'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>������</strong>��վ��ҳ��ҳ����� description������Ϊʹ��ͳһ���á�</td>
                <td><?=form_input('area[config][meta_description]', $detail['config']['meta_description'], 'txtbox')?></td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="submit" name="dosubmit" value=" �ύ " class="btn" />&nbsp;
            <input type="button" value=" ���� " onclick="javascript:history.go(-1);" class="btn" />
        </center>
    </form>
    </div>
</div>