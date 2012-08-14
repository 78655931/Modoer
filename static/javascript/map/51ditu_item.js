var marker = Array(item_num);
var infow = Array(item_num);
var ifw = Array(item_num);
var map = new LTMaps( 'map_container' );
var control = new LTStandMapControl( );
city_p1 *= 100000;
city_p2 *= 100000;
$(document).ready(function(){
	map.addControl(control);
    $('#subjects h3').each(function(i) {
        var name = $(this).text();
        var mappoint = eval('('+$(this).attr('mappoint')+')');
        addMarker(name, new LTPoint(mappoint.lng*100000,mappoint.lat*100000), i, $(this).attr('sid'));
    });
    if(marker[0]) {
        map.cityNameAndZoom(marker[0].getPoint(), 2);
    } else {
        map.cityNameAndZoom(new LTPoint(city_p1,city_p2) , 2);
    }
});

function addMarker(name, point, index, sid) {
	marker[index] = new LTMarker(point);
	map.addOverLay(marker[index]);
	LTEvent.addListener(marker[index], "click", function() {
		showMarker(sid,index);
	});
}

function showMarker(sid,index,move) {
	for (var i=0; i<ifw.length; i++) {
		if(ifw[i]) ifw[i].closeInfoWindow();
	}
    ifw[index] = marker[index].openInfoWinHtml('载入中...');
	ifw[index].setTitle('主题信息');
	ifw[index].setWidth(400);
	ifw[index].setHeight(120);
	if(move==true) {
		map.setCenterAtLatLng(marker[index].getPoint());
	}
	//ifw[index].open(map,marker[index]);
	getSubject(sid, ifw[index], index);
}

function getSubject(sid,ifw,index) {
    if (!is_numeric(sid)) {
        alert('无效的SID'); return;
    }
	ifw.moveToShow();
    if(infow[index]!=undefined) {
        ifw.setLabel(infow[index]);
    } else {
        $.post(Url('item/map/op/detail'), { sid:sid, in_ajax:1}, 
        function(data) {
            infow[index] = data;
            ifw.setLabel(data);
        });
    }
}
