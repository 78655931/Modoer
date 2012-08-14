loadscript("mdialog");

//提交改变头像
function post_changeface() {
	var form = document.changefacefrm;
	var faceid = getRadio(form, 'faceid');
	if(!faceid) {
		alert('未选择头像。');
		return false;
	}
	$.post(Url('member/index'), {ac:"face",op:"change",in_ajax:1,faceid:faceid}, function(result) {
		if(result.match(/<input.+type="button".*>/)) {
			myAlert(result);
		} else if(result.trim() == '') {
			alert('头像修改失败。');
		} else {
			$("#face").attr("src",result);
		}
	});
	return false;
}

//浏览
function viewFacePage(num) {
    num = num != 1 ? -1 : 1;
    var p = face_page + num;
    if(p > max_face_page) {
        alert('已经没有下一页。');
        return;
    } else if(p < 1) {
        alert('貌似走的不到第0页。');
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