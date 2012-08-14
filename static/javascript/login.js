/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/

var flash_window_step = 0;
var flash_window_title = document.title;
var flash_window_time = null;
function flash_window(title) {
	flash_window_time = setInterval(function() {
		flash_window_step++;
		if (flash_window_step>=3) {flash_window_step=1}; //　//
		var space = new Array(title.length+1).join('　');
		if (flash_window_step==1) {document.title='【'+space+'】'+flash_window_title};
		if (flash_window_step==2) {document.title='【'+title+'】'+flash_window_title};
	}, 1000);
}

function login_init() {
	$('#login_0').show();
	$('#login_1').hide();
	$('#login_2').hide();
	$('#login_btn_0').show();
	$('#main_username').val('请输入用户名').css('color','#808080');
	$('#main_password').keydown(function(event) {
		if(event.keyCode == 13) {
			main_login();
		}
	});
}

function login_read() {
	var activationauth = get_cookie('activationauth');
	var hash = get_cookie('hash');
	var myauth = get_cookie('myauth');

	if(!activationauth && !hash && !myauth) {
		login_init();
		return;
	}

	$.post(Url('member/login/op/check'), { in_ajax:1 }, 
	function(result) {
		if(result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
			myAlert(result);
		} else if(result) {
			var login = eval('('+result+')');
			if(login.type == 'activationauth') {
				$('#login_activation').html(login.username);
				var href = $('#login_activation_a').attr('href');
				href = href.replace('_activationauth_', get_cookie('activationauth'));
				$('#login_activation_a').attr('href', href);
				$('#login_0').hide();
				$('#login_btn_0').hide();
				$('#login_1').hide();
				$('#login_2').show();
			} else {
				$('#login_name').html(login.username);
				if(login.newmsg > 0) $('#login_newmsg').html('('+login.newmsg+')').css('display','').toggleClass('font_1');
				if(login.task > 0) $('#login_task').html('('+login.task+')').css('display','').toggleClass('font_1');
				if(login.notice > 0) $('#login_notice').html('('+login.notice+')').css('display','').toggleClass('font_1');
				$('#login_point').html(login.point).toggleClass('font_2');
				$('#login_group').html(login.group).toggleClass('font_2');
				$('#login_0').hide();
				$('#login_btn_0').hide();
				$('#login_2').hide();
				$('#login_1').show();
				//闪烁标题栏
				if(login.newmsg > 0) {
					flash_window('新消息');
				} else if (login.task > 0) {
					flash_window('任务完成');
				}
			}
		} else {
			login_init();
		}
	});
}

function main_username_check(obj) {
	var i = $(obj);
	i.attr('input',i.attr('input')=='1'?'':'1');
	if(i.attr('input')=='1') {
		if(i.val() == '请输入用户名') {
			i.val('');
		}
		i.css('color','black');
	} else {
		if(i.val() == '') i.val('请输入用户名').css('color','gray');
	}
}

function main_login() {
	var name = $('#main_username').val();
	var pw = $('#main_password').val();
	if(name == '请输入用户名') name='';
	if(name==''&& pw=='') {
		//document.location = Url("member/login");
		dialog_login();
		return;
	}
	$('#main_frm_login')[0].submit();
}

function ajax_login_after(s,d) {
	alert(d);
}

//首页头部登录验证码
function main_show_seccode(obj,id) {
	var div = $('#'+id);
	if(!div[0]) return;
	if(div.attr('show')=='Y') return;
	
    var pos = find_pos(obj);

	div.css('visibility','visible').attr('show','Y').
		css("left",pos.x-195).css("top", pos.y - 4).
		css('zIndex',100).css('display','');

	show_seccode('login_seccode');
	var seccode = $("[name=seccode]");
	if(!seccode[0]) return;
	var txt = '验证码';
	if(seccode!=''||seccode!=txt) seccode.val(txt).css('color','gray');
	seccode.focus(function() {
		if($(this).val()==''||$(this).val()==txt) $(this).val('');
	}).keydown(function(event) {
		if(event.keyCode == 13) {
			main_login();
		}
	});
}

//加载我的助手下拉菜单
$("#assistant_menu").powerFloat({reverseSharp:true});
$("#assistant_point").powerFloat({targetMode:"ajax",position:"3-2"});

//第三方帐号登录
var passport_list = $('<ul></ul>').addClass('passport_api_list').attr('id','passport_menu_list');
$('#passport_api').find('.none').each(function() {
	passport_list.append($('<li></li>').append($(this).show()));
});
if(passport_list.find('li').length>0) {
	$('#passport_api').powerFloat({target:passport_list,offsets:{x:0,y:-1}});
	$(document.body).append(passport_list);
}

(function() {
    var $backToTopTxt = "返回顶部";
	$backToTopEle = $('<div class="back2top"></div>').appendTo($("body"))
        .text($backToTopTxt).attr("title", $backToTopTxt).click(function() {
            $("html, body").animate({ scrollTop: 0 }, 120);
    });
	$backToTopFun = function() {
        var st = $(document).scrollTop(), winh = $(window).height();
        (st > 0)? $backToTopEle.show(): $backToTopEle.hide();    
        //IE6下的定位
        if (!window.XMLHttpRequest) {
            $backToTopEle.css("top", st + winh - 166);    
        }
    };
    $(window).bind("scroll", $backToTopFun);
    $(function() { $backToTopFun(); });
})()

//登录检测ajax
login_read();