//�򿪵�¼�Ի���
function dialog_login () {
	var src = Url("member/login/op/ajax_login");
	$.post(src, { in_ajax:1 }, 
	function(data) {
        if(data == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (is_message(data)) {
            myAlert(data);
		} else {
			dlgOpen('��Ա��¼',data,520);
		}
	});
}
function check_username(obj) {
	if(!obj.value) {
		$('#msg_username').html('<span class="font_1">�������Ա����.</span>').show();
		return;
	}
	$.post(Url('member/reg/op/check_username'), {'username':obj.value,'in_ajax':1}, function(data) {
		if(is_message(data)) {
			myAlert(data);
		} else {
			$('#msg_username').html(data).show();
		}
	});
}

function check_email(obj) {
	if(!obj.value) {
		$('#msg_email').html('<span class="font_1">������E-mail��ַ.</span>').show();
		return;
	}
	$.post(Url('member/reg/op/check_email'), {'email':obj.value,'in_ajax':1}, function(data) {
		if(is_message(data)) {
			myAlert(data);
		} else {
			$('#msg_email').html(data).show();
		}
	});
}

function send_message(recvuid, subject) {
	if(!subject) subject = '';
	$.post(Url('member/index/ac/pm/op/write'), { recvuid:recvuid, subject:subject, in_ajax:1 }, 
	function(result) {
		if(result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else {
			dlgOpen('���Ͷ���', result, 500, 285);
		}
	});
}

function checkpm() {
	var form = document.getElementById('pmform');
	
	if(form.recv_users.value == ''){
		msgOpen('δ��ӷ��Ͷ���');
		return false;
	} else if(form.subject.value == '') {
		msgOpen('δ��д�������⡣');
		return false;
	} else if(form.subject.value.length > 60) {
		msgOpen('�������ⲻ�ܳ���60���ַ���');
		return false;
	} else if(form.content.value == '') {
		msgOpen('δ��д�������ݡ�');
		return false;
	} else if(form.content.value.length > 500) {
		msgOpen('�������ݲ��ܳ���500���ַ���');
		return false;
	}
	return true;
}

function add_friend(friend_uid) {
	if(!is_numeric(friend_uid)) { alert('��Ч��UID'); return; }
	$.post(Url('member/index/ac/friend/op/add'), { friend_uid:friend_uid, in_ajax:1}, 
	function(result) {
		if(result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else {
			dlgOpen('��Ӻ���', result, 500, 220);
		}
	});
}

function post_addfriend() {
	var form = document.addfriendfrm;
	if(!is_numeric(form.friendid.value)){
		alert('��Ա�����ڣ��޷���ӡ�');
		return false;
	} else if(form.content.value.length > 300) {
		alert('�Ժ��ѵ����Բ��ܳ���300���֣��뾫��һ�����ԡ�');
		return false;
	}

	return true;
}

function check_mobile(obj) {
	var mobile = obj.value.trim();
	if(!mobile) {
		$('#msg_mobile').html('<span class="font_1">�������ֻ�����.</span>').show();
		return;
	}
	$.post(Url('member/reg/op/check_mobile'), {'mobile':mobile,'in_ajax':1}, function(data) {
		if(data=='OK') {
			$('#msg_mobile').hide();
			$('#btn_mobile').show();
		} else if(data=='SUCCEED') {
			$('#msg_mobile').html('<span style="color:green">��֤�ɹ�</span>').show();
			$('#btn_mobile').hide();
		} else {
			$('#msg_mobile').html(data).show();
			$('#btn_mobile').hide();
		}
	});
}

function member_mobile_verify_dialog(mobile) {
	var content = $('<div></div>');
	var serial = $('<input type="text" name="serial">');
	var ck_btn = $('<button type="button">�ύ</button>');
	var sd_btn = $('<button type="button">���·���</button>').attr('disabled',true);
	content.append("<label>�������ֻ��յ�����֤��:</lable><br />")
		.append(serial).append('&nbsp;')
		.append(ck_btn).append('&nbsp;')
		.append(sd_btn);
	ck_btn.click(function() {
		member_mobile_verify(serial.val());
	});
	sd_btn.click(function() {
		member_mobile_send(mobile, sd_btn);
	});
	dlgOpen('ȷ���ֻ���',content,350,100);
	member_mobile_send(mobile, sd_btn);
}

function member_mobile_verify(serial) {
	$.post(Url('member/reg/op/check_mobile_verify'), {'serial':serial,'in_ajax':1}, function(data) {
		if(data=='OK') {
			if(mobile_verify_time_handle) clearInterval(mobile_verify_time_handle);
			$('#msg_mobile').html('<span style="color:green">��֤�ɹ�</span>').show();
			$('#btn_mobile').hide();
			dlgClose();
			alert('������֤�ɹ���');
		} else {
			//alert(data);
			alert('������֤ʧ�ܣ�');
		}
	});
}

var mobile_verify_time = 59;
var mobile_verify_time_handle = null;
function member_mobile_send(mobile, sd_btn) {
	if(mobile_verify_time_handle) clearInterval(mobile_verify_time_handle);
	msgOpen('���ڷ����ֻ�����Ϣ...');
	$.post(Url('member/reg/op/send_verify'), {'mobile':mobile,'in_ajax':1}, function(data) {
		data = data.trim();
		//alert(data);
		msgClose();
		if(data=='OK'||is_numeric(data)) {
			if(is_numeric(data)) mobile_verify_time = data;
			mobile_verify_time_handle = window.setInterval(function() {
				if(mobile_verify_time <= 0) {
					sd_btn.text('���·���').attr('disabled',false).css('color','#000');
					mobile_verify_time = 59;
					clearInterval(mobile_verify_time_handle);
				} else {
					sd_btn.text('���·���('+mobile_verify_time--+')').attr('disabled',true).css('color','#808080');
					//mobile_verify_time_handle
				}
			}, 1000);
		} else if(is_message(data)) {
			myAlert(data);
		} else {
			alert('�ֻ����ŷ���ʧ�ܡ�');
		}
	});
}

//��ע�û�
function member_follow(uid, callback) {
    if (!is_numeric(uid)) {
        alert('��Ч��uid'); 
        return;
    }
	$.post(Url('member/index/ac/follow/op/add'), {'uid':uid,'in_ajax':1}, function(result) {
        if(result == null) {
            alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            if(callback) {
            	callback(uid);
            } else {
            	msgOpen('���ѳɹ���עTA!');
            }
        }
	});
}

//ȡ����ע
function member_unfollow(uid, callback) {
    if (!is_numeric(uid)) {
        alert('��Ч��uid'); 
        return;
    }
	$.post(Url('member/index/ac/follow/op/delete'), {'uid':uid,'in_ajax':1}, function(result) {
        if(result == null) {
            alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            if(callback) {
            	callback(uid);
            } else {
            	msgOpen('����ȡ����TA�Ĺ�ע!');
            }
        }
	});
}