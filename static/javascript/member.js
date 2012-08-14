//打开登录对话框
function dialog_login () {
	var src = Url("member/login/op/ajax_login");
	$.post(src, { in_ajax:1 }, 
	function(data) {
        if(data == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(data)) {
            myAlert(data);
		} else {
			dlgOpen('会员登录',data,520);
		}
	});
}
function check_username(obj) {
	if(!obj.value) {
		$('#msg_username').html('<span class="font_1">请输入会员名称.</span>').show();
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
		$('#msg_email').html('<span class="font_1">请输入E-mail地址.</span>').show();
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
			dlgOpen('发送短信', result, 500, 285);
		}
	});
}

function checkpm() {
	var form = document.getElementById('pmform');
	
	if(form.recv_users.value == ''){
		msgOpen('未添加发送对象。');
		return false;
	} else if(form.subject.value == '') {
		msgOpen('未填写短信主题。');
		return false;
	} else if(form.subject.value.length > 60) {
		msgOpen('短信主题不能超过60个字符。');
		return false;
	} else if(form.content.value == '') {
		msgOpen('未填写短信内容。');
		return false;
	} else if(form.content.value.length > 500) {
		msgOpen('短信内容不能超过500个字符。');
		return false;
	}
	return true;
}

function add_friend(friend_uid) {
	if(!is_numeric(friend_uid)) { alert('无效的UID'); return; }
	$.post(Url('member/index/ac/friend/op/add'), { friend_uid:friend_uid, in_ajax:1}, 
	function(result) {
		if(result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else {
			dlgOpen('添加好友', result, 500, 220);
		}
	});
}

function post_addfriend() {
	var form = document.addfriendfrm;
	if(!is_numeric(form.friendid.value)){
		alert('会员不存在，无法添加。');
		return false;
	} else if(form.content.value.length > 300) {
		alert('对好友的留言不能超过300个字，请精简一下留言。');
		return false;
	}

	return true;
}

function check_mobile(obj) {
	var mobile = obj.value.trim();
	if(!mobile) {
		$('#msg_mobile').html('<span class="font_1">请输入手机号码.</span>').show();
		return;
	}
	$.post(Url('member/reg/op/check_mobile'), {'mobile':mobile,'in_ajax':1}, function(data) {
		if(data=='OK') {
			$('#msg_mobile').hide();
			$('#btn_mobile').show();
		} else if(data=='SUCCEED') {
			$('#msg_mobile').html('<span style="color:green">认证成功</span>').show();
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
	var ck_btn = $('<button type="button">提交</button>');
	var sd_btn = $('<button type="button">重新发送</button>').attr('disabled',true);
	content.append("<label>请输入手机收到的验证码:</lable><br />")
		.append(serial).append('&nbsp;')
		.append(ck_btn).append('&nbsp;')
		.append(sd_btn);
	ck_btn.click(function() {
		member_mobile_verify(serial.val());
	});
	sd_btn.click(function() {
		member_mobile_send(mobile, sd_btn);
	});
	dlgOpen('确认手机号',content,350,100);
	member_mobile_send(mobile, sd_btn);
}

function member_mobile_verify(serial) {
	$.post(Url('member/reg/op/check_mobile_verify'), {'serial':serial,'in_ajax':1}, function(data) {
		if(data=='OK') {
			if(mobile_verify_time_handle) clearInterval(mobile_verify_time_handle);
			$('#msg_mobile').html('<span style="color:green">认证成功</span>').show();
			$('#btn_mobile').hide();
			dlgClose();
			alert('短信验证成功！');
		} else {
			//alert(data);
			alert('短信验证失败！');
		}
	});
}

var mobile_verify_time = 59;
var mobile_verify_time_handle = null;
function member_mobile_send(mobile, sd_btn) {
	if(mobile_verify_time_handle) clearInterval(mobile_verify_time_handle);
	msgOpen('正在发送手机短信息...');
	$.post(Url('member/reg/op/send_verify'), {'mobile':mobile,'in_ajax':1}, function(data) {
		data = data.trim();
		//alert(data);
		msgClose();
		if(data=='OK'||is_numeric(data)) {
			if(is_numeric(data)) mobile_verify_time = data;
			mobile_verify_time_handle = window.setInterval(function() {
				if(mobile_verify_time <= 0) {
					sd_btn.text('重新发送').attr('disabled',false).css('color','#000');
					mobile_verify_time = 59;
					clearInterval(mobile_verify_time_handle);
				} else {
					sd_btn.text('重新发送('+mobile_verify_time--+')').attr('disabled',true).css('color','#808080');
					//mobile_verify_time_handle
				}
			}, 1000);
		} else if(is_message(data)) {
			myAlert(data);
		} else {
			alert('手机短信发送失败。');
		}
	});
}

//关注用户
function member_follow(uid, callback) {
    if (!is_numeric(uid)) {
        alert('无效的uid'); 
        return;
    }
	$.post(Url('member/index/ac/follow/op/add'), {'uid':uid,'in_ajax':1}, function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            if(callback) {
            	callback(uid);
            } else {
            	msgOpen('您已成功关注TA!');
            }
        }
	});
}

//取消关注
function member_unfollow(uid, callback) {
    if (!is_numeric(uid)) {
        alert('无效的uid'); 
        return;
    }
	$.post(Url('member/index/ac/follow/op/delete'), {'uid':uid,'in_ajax':1}, function(result) {
        if(result == null) {
            alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(result)) {
            myAlert(result);
        } else if(result=='OK') {
            if(callback) {
            	callback(uid);
            } else {
            	msgOpen('您已取消对TA的关注!');
            }
        }
	});
}