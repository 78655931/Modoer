var marker = Array(item_num);
var infow = Array(item_num);
var ifw = Array(item_num);
var map = null;

var myOptions = {
	zoom: 15,
	mapTypeControl: true,
	mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
	navigationControl: true,
	mapTypeId: google.maps.MapTypeId.ROADMAP,
	center: new google.maps.LatLng(city_p2,city_p1)
}

$(document).ready(function(){
	map = new google.maps.Map(document.getElementById('map_container'), myOptions);
    $('#subjects h3').each(function(i) {
        var name = $(this).text();
        var mappoint = eval('('+$(this).attr('mappoint')+')');
        addMarker(name, new google.maps.LatLng(mappoint.lat,mappoint.lng), i, $(this).attr('sid'));
    });
    if(marker[0]) {
        map.setCenter(marker[0].getPosition(), 15);
    }
});

function addMarker(name, point, index, sid) {

    marker[index] = new google.maps.Marker({
		title:name,
		position: point,
		map: map,
		draggable: false
    });

    google.maps.event.addListener(marker[index], 'click', function() {
		showMarker(sid,index);
    });


/*
    var myIcon = new BMap.Icon("http://api.map.baidu.com/img/markers.png", new BMap.Size(23, 25), {
        offset: new BMap.Size(10, 25),                  // 指定定位位置
        imageOffset: new BMap.Size(0, 0 - index * 25)   // 设置图片偏移
    });
    marker[index] = new BMap.Marker(point, {icon: myIcon});
    marker[index].addEventListener("click", function() {
        showMarker(sid,index);
    });
    map.addOverlay(marker[index]);
*/
}

function showMarker(sid,index,move) {
	for (var i=0; i<ifw.length; i++) {
		if(ifw[i]) ifw[i].close();
	}
    ifw[index] = new google.maps.InfoWindow({
		maxWidth: 400,
        content: '载入中...'
    });
	if(move==true) map.setCenter(marker[index].getPosition());
	ifw[index].open(map,marker[index]);
	getSubject(sid, ifw[index], index);
}

function getSubject(sid,ifw,index) {
    if (!is_numeric(sid)) {
        alert('无效的SID'); return;
    }
    if(infow[index]!=undefined) {
		ifw.setContent(infow[index]);
    } else {
        $.post(Url('item/map/op/detail'), { sid:sid, in_ajax:1}, 
        function(data) {
            infow[index] = data;
            ifw.setContent(infow[index]);
        });
    }
}