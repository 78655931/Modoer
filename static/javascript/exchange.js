/**
* @author ��<service@cmsky.org>
* @copyright (c)2009-2012 ������
* @website www.cmsky.org
*/

function randnum(codelen) {
    if(!randnum) return;
    if (!is_numeric(codelen)) {alert('��Ч��ID'); return;}
	$.post(Url('exchange/ajax/do/randcode/op/make_randcode'),
	{ codelen:codelen,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$("#randcode").html(result);
			$("#randomcode").val(result);
		}
	});
}

function compare_randcode(giftid) {
    if (!is_numeric(giftid)) {alert('��Ч��ID'); return;}
	$.post(Url('exchange/ajax/do/randcode/op/compare_randcode'),
	{ giftid:giftid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('�齱���Ļ���ѡ��', result, 380, 120);
		}
	});
}