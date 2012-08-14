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
    //返回格式 1=abc\r\n2=ccc\r\n3=ggg\r\n
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
        dstObj.options[dstObj.length-1].text = '=='+level+'级菜单==';
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
        <div class="subtitle"><?if($op=='edit'){?>编辑<?}else{?>增加<?}?>菜单</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%"><strong>所属菜单组:</strong>选择一个菜单的上级目录</td>
                <td width="*" id="menu_select">
                    <select name="menu[parentid]">
                        <option value="0">父菜单组(根目录)</option>
                        <?=$select_menu?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>菜单名称:</strong>各个模块的网站路径为“模块标识”，标识名可在模块列表中查看到，属性包括：name(名称)；填写"<span class="font_1">{member-name}</span>"则为网站"<span class="font_1"><?=$_G['modules']['member']['name']?></span>"模块</td>
                <td width="*"><input type="text" name="menu[title]" class="txtbox2" value="<?=$menu['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>菜单类型:</strong>填写菜单组则可以继续增加新子菜单<br /><span class="font_1">本选项设置后不可再次修改</span></td>
                <td width="*">
                    <?=form_radio('menu[isfolder]', array('菜单','菜单组'), $menu['isfolder'], $extra)?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>排序:</strong></td>
                <td><input type="text" name="menu[listorder]" class="txtbox2" value="<?=(int)$menu['listorder']?>" /></td>
            </tr>
            <tr id="tr_url"<?if($menu['isfolder']):?>style="display:none;"<?endif;?>>
                <td class="altbg1" width="45%"><strong>目标地址:</strong>填写目标地址，例如"<span class="font_1">item</span>"则为商铺点评地址，填写"<span class="font_1">modoer</span>"则显示网站主域名；如果您要填写的不是系统模块，请填写完整的地址，例如：http://www.modoer.com</td>
                <td width="*"><input type="text" name="menu[url]" class="txtbox2" value="<?=$menu['url']?>" /></td>
            </tr>
            <tr id="tr_target"<?if($menu['isfolder']):?>style="display:none;"<?endif;?>>
                <td class="altbg1" width="45%"><strong>目标框架:</strong>可以设置页面打开到目标位置</td>
                <td width="*"><input type="text" name="menu[target]" class="txtbox2" value="<?=$menu['target']?>" />&nbsp;例如: _blank, _self</td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>当页标识:</strong>利用与每个页面SCRIPTANV（标识当前打开的是那个页面）变量对应起来，便可以达到Tab样式菜单的当前页突出显示</td>
                <td width="*"><input type="text" name="menu[scriptnav]" class="txtbox2" value="<?=$menu['scriptnav']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>菜单图标:</strong>如果使用到带图标的菜单项时提供图标地址，默认图标存放在 ./static/images/menu 下</td>
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