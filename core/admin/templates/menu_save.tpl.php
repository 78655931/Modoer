<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function select_isfolder(value) {
    $('#tr_url').css("display",value!=1?'':'none');
    $('#tr_target').css("display",value!=1?'':'none');
}

function get_child(obj) {
    var level = $(obj).attr('level');
    var ix = 0;
    $("#menu_select > select").each(function(i) {
        ix++;
        if (ix > level) {
            $(this).remove();
        }
    });
    if($(obj).val()=='') return;

	$.post("<?=cpurl($module,$act,'get_child')?>", {parentid:$(obj).val(), in_ajax:1}, function(result) {
		if(result.match(/<input.+type="button".*>/)) {
			myAlert(result);
		} else if(result == '') {
			//dstObj.style.display = 'none';
		} else {
            level++;
			create_child(level,result);
		}
	});
}

function get_child_s(event) {
    get_child(document.getElementById(event.data.dstid), 0);
}

function create_child(level, data) {
    //���ظ�ʽ 1=abc\r\n2=ccc\r\n3=ggg\r\n
    var select = $("<select></select");
    select.attr("id","menu_" + level);//level
    select.attr("name","menu_" + level);//level
    select.attr("level", level);
    $('#menu_select').append(select);

    dstObj = document.getElementById("menu_" + level);
    select.bind("change", {dstid: "menu_" + level}, get_child_s);

    var subcat = data.split("\r\n");
    for (var i=0; i<subcat.length; i++) {
        if(subcat[i].length == 0) continue;
        var cdinfo = subcat[i].split("=");
        dstObj.options.add(document.createElement("OPTION"));
        dstObj.options[dstObj.length-1].text = '=='+level+'���˵�==';
        dstObj.options[dstObj.length-1].value = '';
        dstObj.options.add(document.createElement("OPTION"));
        dstObj.options[dstObj.length-1].text = cdinfo[1]; // (classname)
        dstObj.options[dstObj.length-1].value = cdinfo[0]; // (areacode)
    }
}
</script>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module, $act, $op)?>">
    <div class="space">
        <div class="subtitle"><?if($op=='edit'){?>�༭<?}else{?>����<?}?>�˵�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>�����˵���:</strong>ѡ��һ���˵����ϼ�Ŀ¼</td>
                <td width="*" id="menu_select">
                    <select name="menu[parentid]">
                        <option value="0">���˵���(��Ŀ¼)</option>
                        <?=$select_menu?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>�˵�����:</strong>����ģ�����վ·��Ϊ��ģ���ʶ������ʶ������ģ���б��в鿴�������԰�����name(����)����д"<span class="font_1">{member-name}</span>"��Ϊ��վ"<span class="font_1"><?=$_G['modules']['member']['name']?></span>"ģ��</td>
                <td width="*"><input type="text" name="menu[title]" class="txtbox2" value="<?=$menu['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>�˵�����:</strong>��д�˵�������Լ����������Ӳ˵�<br /><span class="font_1">��ѡ�����ú󲻿��ٴ��޸�</span></td>
                <td width="*">
                    <?=form_radio('menu[isfolder]', array('�˵�','�˵���'), $menu['isfolder'], $extra)?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>����:</strong></td>
                <td><input type="text" name="menu[listorder]" class="txtbox2" value="<?=(int)$menu['listorder']?>" /></td>
            </tr>
            <tr id="tr_url"<?if($menu['isfolder']):?>style="display:none;"<?endif;?>>
                <td class="altbg1" width="45%"><strong>Ŀ���ַ:</strong>��дĿ���ַ������"<span class="font_1">item</span>"��Ϊ���̵�����ַ����д"<span class="font_1">modoer</span>"����ʾ��վ�������������Ҫ��д�Ĳ���ϵͳģ�飬����д�����ĵ�ַ�����磺http://www.modoer.com</td>
                <td width="*"><input type="text" name="menu[url]" class="txtbox2" value="<?=$menu['url']?>" /></td>
            </tr>
            <tr id="tr_target"<?if($menu['isfolder']):?>style="display:none;"<?endif;?>>
                <td class="altbg1" width="45%"><strong>Ŀ����:</strong>��������ҳ��򿪵�Ŀ��λ��</td>
                <td width="*"><input type="text" name="menu[target]" class="txtbox2" value="<?=$menu['target']?>" />&nbsp;����: _blank, _self</td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>��ҳ��ʶ:</strong>������ÿ��ҳ��SCRIPTANV����ʶ��ǰ�򿪵����Ǹ�ҳ�棩������Ӧ����������ԴﵽTab��ʽ�˵��ĵ�ǰҳͻ����ʾ</td>
                <td width="*"><input type="text" name="menu[scriptnav]" class="txtbox2" value="<?=$menu['scriptnav']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>�˵�ͼ��:</strong>���ʹ�õ���ͼ��Ĳ˵���ʱ�ṩͼ���ַ��Ĭ��ͼ������ ./static/images/menu ��</td>
                <td width="*"><input type="text" name="menu[icon]" class="txtbox2" value="<?=$menu['icon']?>" /></td>
            </tr>
        </table>
        <center>
            <?if($op=='edit'){?>
            <input type="hidden" name="menu[isfolder]" value="<?=$menu['isfolder']?>" />
            <input type="hidden" name="menuid" value="<?=$menuid?>" />
            <?}?>
            <?=form_submit('dosubmit', lang('global_submit'), 'yes', 'btn')?>&nbsp;
            <?=form_button_return(lang('global_return'), 'btn')?>
        </center>
    </div>
</form>
</div>