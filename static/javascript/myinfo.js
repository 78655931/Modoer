loadscript("mdialog");

//�ύ�ı�ͷ��
function post_changeface() {
	var form = document.changefacefrm;
	var faceid = getRadio(form, 'faceid');
	if(!faceid) {
		alert('δѡ��ͷ��');
		return false;
	}
	$.post(Url('member/index'), {ac:"face",op:"change",in_ajax:1,faceid:faceid}, function(result) {
		if(result.match(/<input.+type="button".*>/)) {
			myAlert(result);
		} else if(result.trim() == '') {
			alert('ͷ���޸�ʧ�ܡ�');
		} else {
			$("#face").attr("src",result);
		}
	});
	return false;
}

//���
function viewFacePage(num) {
    num = num != 1 ? -1 : 1;
    var p = face_page + num;
    if(p > max_face_page) {
        alert('�Ѿ�û����һҳ��');
        return;
    } else if(p < 1) {
        alert('ò���ߵĲ�����0ҳ��');
        return;
    }
    face_page = p;
	$.post(Url('member/index'), {ac:"face",op:"browse",in_ajax:1,page:face_page}, function(result) {
		if (result.match(/<li>.*<\/li>/)) {
			$("#select_facelist").html( result );
		} else {
			myAlert(result);
		}
	});
}