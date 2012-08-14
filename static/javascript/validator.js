/**
* @author moufer<moufer@163.com>
* @copyright (c)2001-2009 Moufersoft
* @website www.modoer.com
*/
//表单提交验证
function validator(form, callback) {
	for (var i=0; i<form.length; i++) {
		var t = $(form[i]);
		if(t.attr('validator_disable')=='Y') continue;
		var v = t.attr('validator');
		if(!v) continue;
		var vi = eval('('+v+')'); //JSON转换
		if(vi.empty == 'N') {
			var error = false;
			switch(t.attr('tagName')) {
				case 'INPUT':
					switch (t.attr('type')) {
						case 'text':
						case 'hidden':
						case 'password':
						case 'textarea':
							error = t.val() == '';
							break;
						case 'checkbox':
							error = !validator_checkbox(t);
							break;
						case 'radio':
							error = !validator_radiobox(t);
							break;
					}
					break;
				case 'SELECT':
					error = !validator_select(t);
					break;
				case 'TEXTAREA':
					error = !validator_textarea(t);
					break;
			}
			if(error) {
				alert(vi.errmsg); 
				return false;
			}
		}
	}
	return true;
}

validator_checkbox = function(t) {
	var check = document.getElementsByTagName('input');
	var name = t.attr('name');
	for (var i=0; i<check.length; i++) {
		if (check[i].type == 'checkbox' && check[i].checked && !check[i].disabled && check[i].name == name) {
			return true;
		}
	}
	return false;
}

validator_radiobox = function(t) {
	var radio = document.getElementsByTagName('input');
	var name = t.attr('name');
	for (var i=0; i<radio.length; i++) {
		if (radio[i].type == 'radio' && radio[i].checked && radio[i].name == name) {
			return true;
		}
	}
	return false;
}

validator_select = function(t) {
	var value = t.val();
	return value != null && value != '';
}

validator_textarea = function(t) {
	var value = t.val();
	return value != null && value != '';
}
