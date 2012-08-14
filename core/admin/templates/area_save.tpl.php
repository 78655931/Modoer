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
	html += '<button type="button" id="mapbtn1">标注坐标</button>&nbsp;';
	html += '<button type="button" id="mapbtn2">确定</button>';
	dlgOpen('选择地图坐标点', html, 470, 390);
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
				alert('您尚未完成标注。');
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
        <div class="subtitle">操作提示</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td>
                1. Modoe旗舰版安装分级为：1级-城市，2级-区/地级县市，3级-街道/路口/商业区；<br />
                2. 第一次添加城市后，必须在“核心设置->基本设置”页面选择默认访问城市；<br />
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
        <div class="subtitle"><?=$op=='edit'?'编辑地区':'添加地区'?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>地区名称：</strong>1级:城市，2级:区/地级县市，3级:街道/路口/商业区</td>
                <td width="*"><input type="text" name="area[name]" value="<?=$detail['name']?>" class="txtbox3" /></td>
            </tr>
            <?if(_get('level')==1 || $detail['level']==1 && $detail['pid']==0):?>
            <tr>
                <td class="altbg1"><strong>名称首字母：</strong>设置地区名称第一个字的首个字母</td>
                <td><input type="text" name="area[initial]" value="<?=$detail['initial']?>" class="txtbox5" /></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1"><strong>默认位置：</strong>地图标注时使用，用于确定地区的中心坐标位置</td>
                <td><input type="text" name="area[mappoint]" id="mappoint" value="<?=$detail['mappoint']?>" class="txtbox3" />&nbsp;<button type="button" class="btn2" onclick="map_mark('mappoint','','');">选择坐标点</button></td>
            </tr>
            <?if(_get('level')==1 || $detail['level']==1 && $detail['pid']==0):?>
            <input type="hidden" name="area[level]" value="1">
            <tr><td colspan="2" class="altbg2"><center><b>多城市功能设置</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>启用分站：</strong>允许游客进入本城市分站</td>
                <td><?=form_bool('area[enabled]', isset($detail['enabled'])?$detail['enabled']:1)?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>分站二级域名或城市目录名：</strong>设置分站的二级域名，通过二级域名访问，实现进入城市分站；也是城市目录名称，城市目录功能仅适用于开启URL改写功能，并且模式为目录形式。<br /><span class="font_1">注意：此处填写的内容不能为"index"以及各个模块标识(例如:item,review,article等)</span></td>
                <td><?=form_input('area[domain]', $detail['domain'], 'txtbox2')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>地图API接口：</strong>设置使用的地图接口需要申请key的，则每个二级域名都需要申请一个key。<br /><span class="font_2">目前谷歌地图v3，百度api，51地图都不需要key，可以仅在核心设置里填写地图api地址，此处可留空。</span></td>
                <td><?=form_input('area[config][mapapikey]', $detail['config']['mapapikey'], 'txtbox2')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>分站页面模板风格：</strong>设置当前城市分站的网站风格，设为"统一设置"，则由核心设置内决定。</td>
                <td>
                    <select name="area[templateid]">
                        <option value="0">统一设置</option>
                        <?=form_template('main', $detail['templateid'])?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><b>搜索引擎优化设置</b></center></td></tr>
            <tr>
                <td class="altbg1"><strong>标题：</strong>分站的主标题，留空为使用统一设置。</td>
                <td><?=form_input('area[config][sitename]', $detail['config']['sitename'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>关键字：</strong>分站首页的页面关键字 keywords，留空为使用统一设置。</td>
                <td><?=form_input('area[config][meta_keywords]', $detail['config']['meta_keywords'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>描述：</strong>分站首页的页面表述 description，留空为使用统一设置。</td>
                <td><?=form_input('area[config][meta_description]', $detail['config']['meta_description'], 'txtbox')?></td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="submit" name="dosubmit" value=" 提交 " class="btn" />&nbsp;
            <input type="button" value=" 返回 " onclick="javascript:history.go(-1);" class="btn" />
        </center>
    </form>
    </div>
</div>