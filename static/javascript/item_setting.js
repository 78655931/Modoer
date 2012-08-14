/**
* @author moufer<moufer@163.com>
* @copyright www.modoer.com
*/
$(document).ready(function() {
	item_style_init_operation();
	$("#item_title").item_style_title_move({sid:sid});
});

function item_style_init_operation () {
	var operation = $('#item-manage-operation');
	if(!operation[0]) {
		operation = $('<div></div>').attr('id','item-manage-operation').addClass('item-manage').hide();
		$('<a href="javascript:;">�������</a>')
			.attr('id','item-banner-op')
			.click(function() {
				item_change_banner(sid)
			}).appendTo(operation);
		$('<span class="split">|</span>').appendTo(operation);
		$('<a href="javascript:;">�������</a>')
			.attr('id','item-bcastr-op')
			.click(function() {
				item_manage_bcastr(sid)
			}).appendTo(operation);
	}
	$('#item_banner').append(operation)
	.mouseover(function() {
		operation.show();
	})
	.mouseout(function() {
		operation.hide();
	});
}

(function($) {

	$.fn.item_style_title_move = function(options) {

		$.fn.item_style_title_move.defaults = {
			sid:0,
			foo_w:950,
			foo_h:150,
			color:'#FFF'
		};
		var opts = $.extend({}, $.fn.item_style_title_move.defaults, options);
		var my = $(this);
		var t = my.find('h1');
		var _move = false;//�ƶ����
		var __move = false;
		var _x,_y;//�����ؼ����Ͻǵ����λ��
		var _time = null;
		var _top = parseInt(my.css("top"));
		var _left = parseInt(my.css("left"));
		var _color = my.find('h1').css('color');
		var _op = $('#'+my.attr('id')+'_op');

		var _w = my.width();
		var _h = my.height();

		t.css('cursor','move');
		my.mouseover(function(e) {
			if(!_op[0]) {
				_op = $("<div class='item-manage-move-title-op'></div>");
				var s = new Array();
				s[0] = $("<span class='click'>Ĭ��λ��</span>").click(_reset_offset);
				s[1] = $("<span class='click'>������ʾ</span>").click(_center_offset);
				s[3] = $("<span class='click'>��������</span>").click(_save_offset);
				s[2] = $("<span class='click'>�����ɫ</span>").colorpicker({
					fillcolor:true,
				    success:function(o,color) {
				        _change_color(0,color);
				    }
				});
				for (var i=0; i<s.length; i++) {
					if(i>0) _op.append("<span class='split'>|</span>");
					_op.append(s[i]);
				}
				my.append(_op);
			}
			_op.show();
		}).mouseout(function(e) {
			_op.hide();
		});
		t.mousedown(function(e) {
			if(_time) window.clearTimeout(_time);
			_move = true;
			_x = e.pageX - _left;
			_y = e.pageY - _top;
			my.fadeTo(20, 0.25);//�����ʼ�϶���͸����ʾ
		});

		t.mousemove(function(e) {
		 if(_move) {
				var x = e.pageX - _x; //�ƶ�ʱ�������λ�ü���ؼ����Ͻǵľ���λ��
				var y = e.pageY - _y;
				if(x < 0) x = 0;
				if(y < 0) y = 0;
				if(x > opts.foo_w - _w) x = opts.foo_w - _w;
				if(y > opts.foo_h - _h) y = opts.foo_h - _h;
				_set_offset(x,y);
			}
		}).mouseup(function() {
			_move = false;
			my.fadeTo("fast", 1); //�ɿ�����ֹͣ�ƶ����ָ��ɲ�͸��
			if(__move) {
				/*
				_time = window.setTimeout(function() {
					_save_offset();
				},1200);
				*/
				__move = false;
			}
		});

		function _reset_offset() {
			x = 30;
			y = 40;
			_set_offset(x,y);
		}

		function _center_offset() {
			x = opts.foo_w/2-_w/2;
			y = opts.foo_h/2-_h/2;
			_set_offset(x,y);
		}

		function _set_offset(x,y) {
			my.css({top:y, left:x}); //�ؼ���λ��
			_top = y;
			_left = x;
			__move = true;
		}

		function _save_offset() {
			if(!is_numeric(opts.sid)||opts.sid<1) { return false; }
			$.mytip.show('���ڱ���...');
			$.post(Url("item/member/ac/setting/op/save_title"), { sid:opts.sid, top:_top, left:_left, color:_color, in_ajax:1 }, function (data) {
				if(data == null) {
					alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
				} else if (is_message(data)) {
					myAlert(data);
				} else if(data=='OK') {
					$.mytip.close('�ѱ���');
				} else {
					$.mytip.close('δ֪����');
				}
			});
			if(_time) window.clearTimeout(_time);
		}

		function _change_color(o, color) {
			my.find('h1').css('color',color);
			_color = color;
		}
	}

})(jQuery);

function item_change_banner(sid) {
	if(!is_numeric(sid)) { alert('��Ч��SID'); return false; }
	$.post(Url("item/member/ac/setting/op/change_banner"), { sid:sid,in_ajax:1 }, function (data) {
        if(data == null) {
			alert('��Ϣ��ȡʧ�ܣ���������æµ�����Ժ��ԡ�');
        } else if (is_message(data)) {
            myAlert(data);
		} else {
			dlgOpen('����banner',data,450,160);
		}
	});
}
function item_manage_bcastr(sid) {
	if(!is_numeric(sid)) { alert('��Ч��SID'); return false; }
	var src = Url("item/member/ac/setting/op/bcastr_list/sid/"+sid);
	var content = $('<div></div>');
	var iframe = $("<iframe></iframe>").attr('src',src).attr({
			'frameborder':'no','border':'0','marginwidth':'0','marginheight':'0','scrolling':'no','allowtransparency':'yes'
		}).css({"width":"100%","height":"350px"});
	content.append(iframe);
	dlgOpen('�������ͼƬ',content,600,400);
	if($.browser.msie && $.browser.version.substr(0,1)=='6' ) {
		window.setTimeout(function() {
			iframe.attr('src', src);
		},1200);
	}
}