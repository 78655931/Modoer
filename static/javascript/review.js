function review_rating (id,value) {
	var r = new Array('-','差','中','好','很好','非常好');
	if(!value) value = 0;
	$('#'+id+'_v').html('('+r[value]+')');
}

function review_pic_ui(sid) {
	var my_current_sid=89559;
	var html = '<form id="frm_reviewupload" method="post" action="'+Url('item/member/ac/pic_upload/in_ajax/1')+'" enctype="multipart/form-data">';
	html += '<input type="hidden" name="sid" value="'+sid+'" />';
	html += '<input type="hidden" name="do" value="review_upload" />';
	html += '<input type="hidden" name="multi" value="yes" />';
	html += '<input type="hidden" name="dosubmit" value="yes" />';
	html += '<input type="file" name="picture" />&nbsp;';
	html += '<button type="button" class="button" onclick="ajaxPost(\'frm_reviewupload\', '+sid+', \'review_pic_add\',1);">上传</button>';
	html += '</form>';
	dlgOpen('上传照片', html, 380, 100);
}

function review_pic_add(sid,data) {
	var data = eval('('+data+')'); //JSON转换
	var foo = $('<div></div>');
	foo.attr('id', 'pic_' + data.picid + '_foo');
	foo.addClass('review_picture_op');
	$('#review_picture').append(foo);
	foo.append("<input type=\"hidden\" name=\"review[pictures][]\" id=\"pic_"+data.picid+"\" value=\""+data.picid+"\" />");
	var img_a = $("<a href='"+urlroot + '/' + data.thumb+"'></a>");
	var img = $('<img />').attr('src', urlroot + '/' + data.thumb);
	img_a.append(img);
	foo.append(img_a);
	var del = $("<a href=\"javascript:;\">删除</a>");
	del.click(function() {
		review_pic_del(data.picid);
	})
	foo.append(del);
}

function review_pic_del(picid) {
	$.post(Url("item/member/ac/m_picture/op/delete"), { "picids[]":picid, in_ajax:1 }, function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
			var foo = $('#pic_' + picid + '_foo');
			foo.remove();
		} else {
			alert('AJAX解析错误.');
		}
	});
}

function post_review(idtype,id,fooid) {
    if (!is_numeric(id)) {
        alert('无效的ID');
		return;
    }
	$.post(Url('review/member/ac/add/type/'+idtype+'/id/'+id), { in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			if(fooid) {
				$('#'+fooid).html(result);
			} else {
				dlgOpen('点评', result, 600, 420);
				var t_height = $('#review_foot').attr('clientHeight') || $('#review_foot').attr('offsetHeight');
				var d_height = $('#'+DLOGID).attr('clientHeight') ||$('#'+DLOGID).attr('offsetHeight');
				$('#'+DLOGID).css('height',460 + t_height - d_height + 'px');
			}
		}
	});
	return false;
}

function post_respond(rid) {
    if (!is_numeric(rid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('review/member/ac/respond/op/add/rid/'+rid), 
	{ in_ajax:1 },
	function(result) {
		msgClose();
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('回复点评', result, 350, 260);
		}
	});
	msgOpen('载入中，请稍后......', 200, 80);
}

var c_page = 1;
function get_respond(data, page) {

	if(data=='') return;

	var ids = data.split('|');
	var rid = ids[0];
	var is_post = ids[1];

	if (!is_numeric(rid)) {
		return;
    }

	if(!page) page = 1;
	if(page == c_page && !is_post) {
		switch ($('#responds_'+rid).attr('show')) {
		case 'YES':
			$('#responds_'+rid).attr('show','NO').slideUp('fast');
			return;
		case 'NO':
			$('#responds_'+rid).attr('show','YES').slideDown('fast');
			return;
		}
	}

	var now_num = parseInt($('#respond_'+rid).text());
	if(!is_post && now_num < 1) {
		post_respond(rid);
		return;
	}

	$.post(getUrl('review','ajax','do=respond&op=get&in_ajax=1&page=' + page), 
	{ rid:rid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#responds_'+rid).css('display','none').html(result).attr('show','YES').slideDown('fast');
			c_page = page;
		}
	});

	if(is_post) {
		$('#respond_'+rid).html(now_num + 1);
	}
}

function delete_respond(respondid, rid) {
    if (!is_numeric(respondid)) {
        alert('无效的ID'); 
		return;
    }

	if(!confirm('确定要进行删除操作吗？')) return;

	$.post(Url('review/member/ac/respond/op/delete'), 
	{ respondids:respondid,in_ajax:1},
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else if (result == 'OK') {
			$('#respond_'+respondid+'_li').remove();
			$('#respond_'+rid).html(parseInt($('#respond_'+rid).text()) - 1);
			//get_respond(rid + '|POST');
		}
	});
}

function add_flower(rid) {
    if (!is_numeric(rid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(getUrl('review','ajax','do=review&op=add_flower&in_ajax=1'), 
	{ rid:rid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			var num_o = $('#flower_'+rid);
			num_o.html(parseInt(num_o.text())+1);
		}
	});
}

function get_flower(rid, page) {

	if (!is_numeric(rid)) {
		return;
    }

	if(!page) page = 1;
	switch ($('#flowers_'+rid).attr('show')) {
		case 'YES':
			$('#flowers_'+rid).attr('show','NO');
			$('#flowers_'+rid).css('display','none');
			return;
		case 'NO':
			$('#flowers_'+rid).attr('show','YES');
			$('#flowers_'+rid).css('display','');
			return;
	}

	$.post(getUrl('review','ajax','do=review&op=get_flower&in_ajax=1&page=' + page), 
	{ rid:rid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#flowers_'+rid).html(result);
			$('#flowers_'+rid).attr('show','YES');
		}
	});
}

function post_report(rid) {
    if (!is_numeric(rid)) {
        alert('无效的ID'); 
		return;
    }
	$.post(Url('review/ajax/do/review/op/post_report/in_ajax/1'), 
	{ rid:rid },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			dlgOpen('举报点评', result, 350, 270);
		}
	});
}

function check_form_report() {
	var form = document.frm_report;
	if(form.username && form.username.value.length == 0){
		msgOpen('请填写您的昵称。');
		return false;
	}
	if(form.email && form.email.value.length == 0){
		msgOpen('请输入您的电子邮件地址。');
		return false;
	} else if(form.email && !is_email(form.email.value)) {
		msgOpen('您输入的电子邮件地址格式不正确。');
		return false;
	}
	var sort = getRadio(form, 'sort');
	if(form.sort.value == '') {
		msgOpen('您选择举报类型。');
		return false;
	}
	if(form.reportcontent.value.length > 200){
		msgOpen('您填写的举报说明文字过多(200字以内)，请精简一下您的举报说明。');
		return false;
	}
	if(!is_numeric(form.rid.value) || !is_numeric(form.modelid.value)) {
		msgOpen('无法确定举报对象。');
		return false;
	}

	return true;
}

function get_review(idtype, id, filter, order, page) {
	if (!is_numeric(id)) {
		return;
    }
	if(!filter) filter = 'all';
	if(!order) order = 'posttime';
	if(!page) page = 1;

	var json = { 'idtype':idtype, 'id' : id };
	
	json.id = id;
	if(filter) json.filter = filter;
	if(order) json.order = order;

	$.post(Url('review/ajax/do/review/op/get/in_ajax/1/page/' + page), json,
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
		} else {
			$('#display').html(result);
			$("div[class=subrail] > span").each(function(i) {
				$(this).removeClass('selected');
			});
			$('#review_filter_'+filter).toggleClass('selected');
			$('#review_order_'+order).toggleClass('selected');
		}
	});
	$('#subtab li').each(function(i) {
		if(this.id=='tab_review') {
			$(this).addClass('selected');
		} else {
			$(this).removeClass('selected');
		}
	});
	return false;
}

function review_tab(id) {
	$("#review-index-tab > li").each(function(i) {
		if(this.id != id) {
			$(this).removeClass('selected');
			$('#'+this.id+'-foo').addClass('none');
		} else {
			$(this).addClass('selected');
			$('#'+this.id+'-foo').removeClass('none');
		}
	});
	
}

function review_view_digst(rid) {
    if (!is_numeric(rid)) {
        alert('无效的ID'); return;
    }
	$.post(Url('review/member/ac/digest/op/view'), 
	{ rid:rid,in_ajax:1 },
	function(result) {
        if(result == null) {
			alert('信息读取失败，可能网络忙碌，请稍后尝试。');
        } else if (is_message(result)) {
            myAlert(result);
		} else if (result=='OK') {
			review_open_detail(rid);
		} else {
			dlgOpen('购买精华点评', result, 320,160);
		}
	});
}

function review_open_detail(rid) {
	document.location = Url('review/detail/id/'+rid);
}

/*
 * jquery rating
 */
(function(a) {
	a.fn.review_rating = function(p) {
		var p = p || {};
		var b = p && p.rating_star_length ? p.rating_star_length: "5";
		var c = p && p.rating_function_name ? p.rating_function_name: "";
		var e = p && p.rating_initial_value ? p.rating_initial_value: "0";
		var d = p && p.directory ? p.directory: "images";
		var f = "";
		var g = a(this);
		b = parseInt(b);
		init();
		g.next("ul").children("li").hover(function() {
			$(this).parent().children("li").css('background-image', 'url(' + d + '/star-off.png)');
			var a = $(this).parent().children("li").index($(this));
			$(this).parent().children("li").slice(0, a + 1).css('background-image', 'url(' + d + '/star-on.png)');
			if (c != "") {
				eval(c + "('"+g.attr('id')+"'," + (a + 1) + ")");
			}
		},
		function() {});
		g.next("ul").children("li").click(function() {
			var a = $(this).parent().children("li").index($(this));
			f = a + 1;
			g.val(f);
			if (c != "") {
				eval(c + "('"+g.attr('id')+"'," + g.val() + ")")
			}
		});
		g.next("ul").hover(function() {},
		function() {
			if (f == "") {
				$(this).children("li").css('background-image', 'url(' + d + '/star-off.png)')
			} else {
				$(this).children("li").css('background-image', 'url(' + d + '/star-off.png)');
				$(this).children("li").slice(0, f).css('background-image', 'url(' + d + '/star-on.png)')
			}
			if (c != "") {
				eval(c + "('"+g.attr('id')+"'," + (f) + ")");
			}
		});
		function init() {
			$('<div style="clear:both;"></div>').insertAfter(g);
			g.css("float", "left");
			var a = $("<ul>");
			a.attr("class", "review-rating-c");
			for (var i = 1; i <= b; i++) {
				a.append('<li style="background-image:url(' + d + '/star-off.png)"><span>' + i + '</span></li>')
			}
			a.insertAfter(g);
			if (e != "") {
				f = e;
				g.val(e);
				g.next("ul").children("li").slice(0, f).css('background-image', 'url(' + d + '/star-on.png)');
				if (c != "") {
					eval(c + "('"+g.attr('id')+"'," + g.val() + ")")
				}
			}
		}
	}
})(jQuery);