/**
* @author 轩<service@cmsky.org>
* @copyright (c)2009-2012 风格店铺
* @website www.cmsky.org
*/

function randnum(codelen) {
    if(!randnum) return;
    if (!is_numeric(codelen)) {alert('无效的ID'); return;}
	$.post(Url('exchange/ajax/do/randcode/op/make_randcode'),
	{ codelen:codelen,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$("#randcode").html(result);
			$("#randomcode").val(result);
		}
	});
}

function compare_randcode(giftid) {
    if (!is_numeric(giftid)) {alert('无效的ID'); return;}
	$.post(Url('exchange/ajax/do/randcode/op/compare_randcode'),
	{ giftid:giftid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('抽奖消耗积分选择', result, 380, 120);
		}
	});
}