var browser=navigator.appName;
var b_version=navigator.appVersion;
var trim_Version=b_version.replace(/[ ]/g,"");
var msie6 = browser == "Microsoft Internet Explorer" && trim_Version.indexOf('MSIE6.0')>0;

var map = new BMap.Map( map_id );
var local = null;

if(height < 300) {
	var opts = {type: BMAP_NAVIGATION_CONTROL_SMALL};
} else {
	var opts = null;
}
map.addControl(new BMap.NavigationControl(opts)); 

if(p1 != '' && p2 != '') {
	var c = new BMap.Point(p1, p2); 
    map.centerAndZoom(c, view_level);
	if(show > 0) {
		var marker = new BMap.Marker(c);
		var lbl = new BMap.Label(title);
		lbl.setStyle({color:"red", fontSize:"12px"});
		marker.setLabel(lbl);
		map.addOverlay(marker); 
	} else {
		window.onload = function  () {
			if(height > 300) {
				create_search_ui();
			}
		}
	}
} else {
	map.centerAndZoom(new BMap.Point('120.1519','30.2448'), view_level);
}

function markmap() {
	map.clearOverlays();
	var myPushpin = new BMap.PushpinTool(map);
	myPushpin.addEventListener("markend", function(e){
		document.getElementById('point1').value = e.marker.getPoint().lng;
		document.getElementById('point2').value = e.marker.getPoint().lat;
		myPushpin.close();
	});
	myPushpin.open();
}

function create_search_ui () {
	//��������
	local = new BMap.LocalSearch(map, {
	  renderOptions:{map: map}
	});
	local.setInfoHtmlSetCallback(open_info);
	local.setSearchCompleteCallback(search_result);
	local.disableFirstResultSelection();
	local.enableAutoViewport();
	//������
	var _abc= document.createElement('div');
	var p = document.getElementById(map_id);
	_abc.style.fontSize = '12px';
	_abc.style.padding = '2px 5px';
	document.body.appendChild(_abc);
	_abc.innerHTML = '������ص㣺<input type="text" id="k"> <input type="button" value="����" onclick="local_search();">';
	if(msie6) {
		p.style.height = p.offsetHeight - 40;
		_abc.style.left = '60px';
		_abc.style.width = "280px";
		_abc.style.margin = "0 auto";
	} else {
		_abc.style.position = 'absolute';
		_abc.style.backgroundColor = '#FFF';
		_abc.style.border ='1px solid #ccc';
		var top = p.offsetHeight-_abc.offsetHeight-5;
		var left = Math.round(p.offsetWidth/2-_abc.offsetWidth/2);
		_abc.style.top = '3px';
		_abc.style.left = left + 'px';
		_abc.style.zIndex = 999;
	}
}

function local_search(name) {
	var k = document.getElementById('k');
	if(k.value=='') return;
	local.clearResults();
	local.search(k.value);
}

function search_result(results) {
	if(results.getNumPois()==0) {
		alert('û�ҵ����ϵĵص����ꡣ');
	}
}

function open_info (poi,html) {
	var content = '<input type="button" value="��������Ϊ��ע��" onclick="set_location(this,'+poi.marker.getPoint().lng+','+poi.marker.getPoint().lat+');">';
	html.innerHTML = content;
}

function set_location(obj,lng,lat) {
	document.getElementById('point1').value = lng;
	document.getElementById('point2').value = lat;
	alert('��ע�������ã��밴��ȷ������ť�رնԻ���');
	var p = obj.parentNode;
	p.removeChild(obj);
	var info = document.createElement('div');
	info.innerHTML = '<span style="color:#808080;">������Ϊ��������</span>';
	p.appendChild(info);
}
